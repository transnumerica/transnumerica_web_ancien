function alertK(msg){
    let alertElt = document.querySelector("#alertModal .modal-content .modal-body");
    //console.log(`Alert : ${msg}`);
    if(alertElt !=null){
        alertElt.innerHTML = msg;
        $('#alertModal').modal('show');
    
    }
}

let chargBoutonsFct = {};

function showChargement({titre="Chargement...", msg, boutons={Ok: ()=>{}}}){
    let chargTitre = document.querySelector("#chargementModal .modal-content #chargementModalLabel");
    let chargMessage = document.querySelector("#chargementModal .modal-content .modal-body .modal-body-message");
    
    if(chargTitre !=null && chargMessage !=null){
        chargTitre.innerHTML = titre;
        chargMessage.innerHTML = msg;

        
        let footer = document.querySelector("#chargementModal .modal-content .modal-footer");
        var strFooter = "";
        for(let idBouton in boutons){
            chargBoutonsFct[idBouton] = boutons[idBouton];
            strFooter +=`<button type="button" class="btn btn-white" data-bs-dismiss="modal" onClick="chargBoutonsFct['${idBouton}']()">${idBouton}</button>`;
            
        }
        footer.innerHTML = strFooter;
        
        $('#chargementModal').modal('show');
    } 

    
}


function hideChargement(){
    $('#chargementModal').modal('hide');
}

function parseFloatWeb(str){
    if (typeof str === 'string' || str instanceof String){
        var strIn = str.replace(",", "");
        return parseFloat(strIn);
    }else{
        return str;
    }
    
}

let spinnerLoadingElt = document.querySelector(".spinner-loading");

document.querySelectorAll('.knumber-picker')?.forEach((numPicker)=>{
    let display = numPicker.querySelector('.display');

    function numberFromDisplay(min=0){
        var num = parseInt(display.value);
        if(isNaN(num)) num = min;
        if(num<min) num=min;
        
        return num;
    }
    numPicker.querySelector('.moins')?.addEventListener('click', (e)=>{
        display.value = numberFromDisplay(1)-1;
        display.click();
    });

    numPicker.querySelector('.plus')?.addEventListener('click', (e)=>{
        display.value = numberFromDisplay()+1;
        display.click();
    });
});


function myFetch({url, 
    method= "POST", 
    headers= {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }, 
    
    json = {}, thenFct = (res)=>{}, 
    catchFct = (res)=>{}, spinner = false}){
        if(spinner) spinnerLoadingElt?.classList?.remove('d-none');
            
        fetch(url, {
            headers,
            method: method,
            body: method!='GET'?JSON.stringify(json):null,
        })
        .then(response => {
            response.json().then(thenFct);
            if(spinner) spinnerLoadingElt?.classList?.add('d-none');
        })//then(thenFct)
        .catch((reason)=>{
            catchFct(reason);
            if(spinner) spinnerLoadingElt?.classList?.add('d-none');
        })
}
/*
var listeBus = [];
var confortFiltre = new Set();
var busFiltre = new Set();

var confortFiltreSelected = new Set();
var busFiltreSelected = new Set();

let reservationBusModalBtn = document.querySelector('.reservationBusModalBtn');
let reservationBusModal = document.querySelector("#reservationBusModal");
var busReservation = null;
*/
let factureModal = document.querySelector('#factureModal');





let countries = {
    source: document.querySelector('.select-country.country-source'),
    destination: document.querySelector('.select-country.country-destination'),
}

let towns = {
    source: document.querySelector('.select-town.town-source'),
    destination: document.querySelector('.select-town.town-destination'),
}

/*
function reservationBus(idxBus){
    let bus = listeBus[idxBus];
    busReservation = bus;

    if(bus !=null && reservationBusModalBtn !=null && reservationBusModal !=null){
        console.log(`in reservationBus(${idxBus})`);

        let operatorName = reservationBusModal.querySelector('.operator-name');
        if(operatorName !=null){
            operatorName.textContent = bus.name;
        }
        
        let economic = parseFloat(bus.prix);
        
        setSeatChart({price:{first: economic*3, second: economic*1.5, economic}, monnaie_symbol: bus.monnaie_symbol});
        reservationBusModalBtn.click();
    }
}
*/
function myFetchMMoney({data}){
    myFetch({url: `${window.location.origin}/m/buymmoney`, json: data, thenFct: (res)=>{
        console.log('après : ', res);

        var status = parseInt(res?.etat?.status);
        if([0,1,2].includes(status)){
            showChargement({titre:"Payement", msg: res?.etat?.message??"Encours...", boutons:{Ok: ()=>{}}});
            
            let timeOfintervalPaymentChecker = new Date().getTime();
            let intervalPaymentChecker = setInterval(()=>{
                if(new Date().getTime() - timeOfintervalPaymentChecker >60000){
                    clearInterval(intervalPaymentChecker);
                    hideChargement();
                    alertK("Délais d'attente dépassé.");
                }else{
                    myFetch({url: `${window.location.origin}/m/ticket/${res?.data?.sale_id??"-1"}`, thenFct: (resTicket)=>{
                            let statusTicket = parseInt(resTicket?.data?.Sale?.status);
                            let messageTicket = resTicket?.data?.Sale?.message;
                            
                            console.log({statusTicket, messageTicket});
                            if([0,1].includes(statusTicket));
                            else if(statusTicket == 2){
                                clearInterval(intervalPaymentChecker);

                                document.querySelector('#reservationModal .btn-annuler')?.click();
                                let dataWeb = res?.data?.SaleDB;
                                modalFacturation({dataWeb});
                                hideChargement();
                            }else{
                                clearInterval(intervalPaymentChecker);
                                hideChargement();
                                alertK(`Echec de payement ${messageTicket!=null?" : "+messageTicket:"" }`);
                                console.log("echec de payement ??= ", resTicket?.data);
                            }
                        }, 

                        catchFct: (resTicket)=>{},
                        //spinner: true,
                    });
                }
            }, 5000);
        }else{
            alertK(res?.etat?.message??"Echec lors de la transaction !");
        }
        
    }, 
        catchFct: (res)=>{},
        spinner: true,
    });
}

function modalFacturation({dataWeb}){
    if(dataWeb?.Sale?.status ==2 ){
        
        let date_depart= dataWeb?.Sale?.departure_date;
        let monnaie_symbol = dataWeb?.Currency?.symbol;
        

        let placeWeb = [];
        let placeVol = dataWeb?.Sale?.info?.place;
        //console.log('placeVol', placeVol);
        if(placeVol !=null){
            let mapPlaceVolTot = {}
            for(let mPlaceStr of placeVol){
                let mPlace = JSON.parse(mPlaceStr);
                //console.log('mPlace', mPlace);
                for(let idPlace in mPlace){
                    //console.log('idPlace', idPlace);
                    mapPlaceVolTot[idPlace] = mPlace[idPlace];
                }
            }

            placeWeb = Object.keys(mapPlaceVolTot);
        }

        let fact_list = JSON.parse(dataWeb?.Sale?.server_facture_list?.data??"[]");
        var total_details = "";

        
        let personnes= {};
            
        
        for(mapFact of fact_list){
            let strPrix = mapFact.prix??"0";
            let prix = parseFloat(strPrix);
            let strQuantite = mapFact.quantite??"0";
            let quantite = parseFloat(strQuantite);
            let strTours = mapFact.tours??"1";
            let tours = parseFloat(strTours);

            let titre = mapFact.titre??"";
            let strPlaces = (mapFact.places??"").split(', ');

            personnes[mapFact.name] = quantite;

            var miniTotal = prix*quantite*tours;

            total_details+= `<div class="list-group-item">${titre} (${strPlaces}) : ${strPrix} ${monnaie_symbol} x${strQuantite}= ${miniTotal} ${monnaie_symbol}</div>`;
        }

        var nbr_adulte = personnes.adult,
            nbr_enfant = personnes.kid,
            nbr_bebe = personnes.baby;


        
        

        let facture = {
            
            sale_id: dataWeb?.Sale?.id,
            qr_link: dataWeb?.Ticket[0]?.qr,
            total: dataWeb?.Sale?.total,
            monnaie_symbol,

            details: {
                n_billet: dataWeb?.Sale?.command,
                paiement: dataWeb?.Sale?.type,
                de: dataWeb?.From?.name,
                a: dataWeb?.To?.name,
                embarquement: dataWeb?.Schedule?.Destination?.from_point,
                date_depart,
                heure: dataWeb?.Schedule?.start,
                statut: (Date.parse(date_depart) - Date.now())>0?'Réservé':'Consommé',
                societe: (dataWeb?.Sale?.info?.company_name)??(dataWeb?.Schedule?.Destination?.Car?.Company?.name),
                model: dataWeb?.Schedule?.Destination?.Car?.name,
                numero_mm: dataWeb?.Sale?.phone,
                invoice: dataWeb?.Ticket[0]?.invoice,
                service: dataWeb?.Sale?.service,
                place: placeWeb.length>0?placeWeb.join(", "):undefined,
                date_achat: dataWeb?.Sale?.created,

                nbr_adulte,
                nbr_enfant,
                nbr_bebe,
            },

            
        

        };

        console.log(facture);

        
        if(factureModal !=null){
            $('#factureModal .operator-name')?.text(facture.details.societe);
            $('#factureModal .sale_id')?.text(facture.sale_id);
            $('#factureModal .qr_link')?.attr('src', facture.qr_link);
            $('#factureModal .total')?.text(""+facture.total+" "+facture.monnaie_symbol);
            
            $('#factureModal .total_details')?.html(`<div class="list-group">${total_details}</div>`);
            
            var detailsHtml = "";
            for(let titre in facture.details){
                let value = facture.details[titre];

                if(value != null) detailsHtml+= `
<div class="my-3 d-block text-center">
<span class="text-uppercase" style="color:#0008;">${titre}:</span>
<br/>
<span class=" text-3 text-uppercase" style="color:#000; " >${value}</span></span> 
</div>
`;
            }
            
            $('#factureModal .details')?.html(detailsHtml);


            $('#factureModal')?.modal('show');
            
        }
    }else{
        alertK("Cette Facture n'est pas valide !");
    }

}

function goHomeAfterModal({modal_query}){
    
    let modal = document.querySelector(modal_query);//('#alertModal');
    console.log("modal", modal);
    if(modal !=null){
        
        modal.setAttribute('data-bs-backdrop',"static");
        modal.querySelectorAll('[data-bs-dismiss="modal"]').forEach((btn)=>{
            console.log("data-bs-dismiss");
            
            //btn.classList.add("d-none");
            btn.removeAttribute('data-bs-dismiss');
            btn.addEventListener('click', (e)=>{
                e.preventDefault();
                window.location.href = `${window.location.origin}`;
                
            });
            
        });
    }

}

function goHomeAfterAlert(){
    goHomeAfterModal({modal_query: '#alertModal'});
}

function goHomeAfterFacture(){
    goHomeAfterModal({modal_query: '#factureModal'});
}


//test
if(typeof num_ticket_to_read !== 'undefined'){
    if(!isNaN(parseInt(num_ticket_to_read))){

        myFetch({url: `${window.location.origin}/m/ticket/`+num_ticket_to_read, method: 'GET',
            headers:{
                'Accept': 'application/json',
                //'Content-Type': 'application/json'
            },
            thenFct: (res)=>{
                console.log('après : ', res);
                
                let dataWeb = res?.data;

                if(res?.success){
                    goHomeAfterFacture();
                    modalFacturation({dataWeb});
                }else{
                    goHomeAfterAlert();
                    
                    alertK(`
<div class="text-center">
    <div class="">Ce ticket est invalide</div>
    <a class="btn btn-primary text-white-grey border-white border-light mt-2" href="${window.location.origin}" >Accueil</a>
</div>
`);
                } 
            }, 
                catchFct: (res)=>{
                    goHomeAfterAlert();
                    
                    alertK(`
<div class="text-center">
    <div class="">Erreur de connexion au serveur</div>
    <div class="d-flex">
        <a class="btn btn-primary text-white-grey border-white border-light mt-2 mx-1" href="${window.location.href}" >Réessayer</a>
        <a class="btn btn-primary text-white-grey border-white border-light mt-2 mx-1" href="${window.location.origin}" >Accueil</a>
    </div>
</div>
`);
                    
                },
                spinner: true,
        });
    
    }else{
        window.location.href = `${window.location.origin}`;
    }
}

/*
document.querySelector('.btn-acheter-billet-bus')?.addEventListener('click', (event)=>{
    if(user?.connected??false){
        var msgError = "";
        let total = $(`.bus-seats .wrapper.${timeWrapper} #total`)?.text()??"0";
        if(total=="0") msgError +="<li>Vous devez sélectionner au moins une place</li>";

        let departure_date = document.querySelector('#reservationBusModal #datepicker')?.value??"";
        if(departure_date=="") msgError +="<li>Vous devez choisir une date</li>";

        var mobileMoney = '';
        var mmoneyCorrect = true;
        var nombre = parseInt(document.querySelector('#reservationBusModal #mobile-money-prefix')?.value);
        console.log("nombre 1 : "+nombre);
        if(!isNaN(nombre)){
            if(nombre>0)mobileMoney+= "+"+nombre+"-";
            else mmoneyCorrect = false;
        } 
        else mmoneyCorrect = false;
        
        
        nombre = parseInt(document.querySelector('#reservationBusModal #mobile-money-suffix')?.value);
        console.log("nombre 2 : "+nombre);
        if(!isNaN(nombre)){
            if(nombre>=100000000) mobileMoney+= nombre;
            else mmoneyCorrect = false;
        } 
        else mmoneyCorrect = false;


        
        if(!mmoneyCorrect){
            msgError += "<li>Vous avez inséré un mobile money incorrect. Veuillez le vérifier : "+mobileMoney + "</li>";
        }

        let bus = busReservation;
        if(msgError.length==0){
            let data = {
                service : "bus",
                auth_id: user?.id,
                user_id: user?.id,
                total: total,
                rate_id: bus.rate_id??"",
                departure_date: departure_date,
                schedule_id: bus.schedule_id,
                place: JSON.stringify(placesPrixBus??{}), //placesBus
            
                company_name: bus.name??"",
                category_company_id: bus.company_id,
                from_country_mobile_code: "",
                from_town_id: bus.from_town_id,
                to_country_mobile_code: "",
                to_town_id: bus.to_town_id,
                currency_id: bus.currency_id,
                currency_iso: bus.currency_iso??"",
                user_total: total,
                user_facture_list_data: JSON.stringify(Object.values(mapFactureBus)),
                user_facture_list_type_billet: JSON.stringify({}),
                phone: mobileMoney,

                from_web: true,
            }

            console.log('avant : ', data);

            myFetch({url: "https://www.tnsarl.com/m/buymmoney", json: data, thenFct: (res)=>{
                console.log('après : ', res);
                document.querySelector('#reservationBusModal .btn-annuler')?.click();
                
                let dataWeb = res?.data?.SaleDB;


                modalFacturation({dataWeb});
            }, 
                catchFct: (res)=>{},
                spinner: true,
            });


        }else{
            alertK("<ul>"+msgError+"</ul>");
            
        }
            


    }else{
        alertK(`
<div class="text-center">
    <div class="">Veuillez vous connecter avant d'éffectuer un achat</div>
    <a class="btn btn-primary text-white-grey border-white border-light mt-2" href="https://www.tnsarl.com/users/login" >Se Connecter</a>
</div>
`);
    }
    
});
*/



(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner(0);
    
    
    // Initiate the wowjs
    new WOW().init();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 200) {
            $('.sticky-top').addClass('shadow-sm').css('top', '0px');
        } else {
            $('.sticky-top').removeClass('shadow-sm').css('top', '-100px');
        }
    });


    // Car Categories
    $(".categories-carousel").owlCarousel({
        autoplay: false,
        smartSpeed: 1000,
        dots: false,
        loop: true,
        margin: 25,
        nav : true,
        navText : [
            '<i class="fas fa-chevron-left"></i>',
            '<i class="fas fa-chevron-right"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:1
            },
            768:{
                items:1
            },
            992:{
                items:2
            },
            1200:{
                items:3
            }
        }
    });


    // testimonial carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        center: false,
        dots: true,
        loop: true,
        margin: 25,
        nav : false,
        navText : [
            '<i class="fa fa-angle-right"></i>',
            '<i class="fa fa-angle-left"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:1
            },
            768:{
                items:1
            },
            992:{
                items:2
            },
            1200:{
                items:2
            }
        }
    });

    // testimonial carousel
    $(".testimonial-carousel-kv").owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        dots: false,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:5
            }
        }
    });

    $('.owl-carousel').owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        stagePadding: 50,
        loop:true,
        margin:15,
        nav:true,
        //animateOut: 'fadeOut',
        //animateIn: 'flipInX',
        dots:false,
        navText : [
            `<button class="carousel-control-prev" type="button" >
            <div class="d-flex rounded-circle bg-primary"><span class="carousel-control-prev-icon m-2" aria-hidden="true"></span></div>
            
          </button>`,
            `<button class="carousel-control-next" type="button" >
            <div class="d-flex rounded-circle bg-primary"><span class="carousel-control-next-icon m-2" aria-hidden="true"></span></div>
          
        </button>`
        ],
        responsive:{
            0:{
                items:1
            },
            300:{
                items:1
            },
            400:{
                items:1
            },
            600:{
                items:1
            },
            800:{
                items:2
            },
            1000:{
                items:4
            }
        }
    });

    // Facts counter
    $('[data-toggle="counter-up"]').counterUp({
        delay: 5,
        time: 2000
    });


   // Back to top button
   $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
        $('.back-to-top').fadeIn('slow');
    } else {
        $('.back-to-top').fadeOut('slow');
    }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });

    
    
    const elementToStick = document.querySelector('.nav-stick');
    const stickyTop = (elementToStick)=>{
        if (window.scrollY > elementToStick.offsetTop) {
            elementToStick.classList.add('ksticky-top');
        } else {
            elementToStick.classList.remove('ksticky-top');
        }
    }

    window.addEventListener('scroll', () => {
        stickyTop(elementToStick)
    });
    stickyTop(elementToStick);

    var intYoutubeAutoPlay = setInterval(()=>{
        document.querySelector('.nextYoutube')?.click();
    }, 5000);

    var clickYoutube = ()=>{
        clearInterval(intYoutubeAutoPlay);
    }


    document.addEventListener('blur', ()=>{
          clickYoutube();
    })
    
    /*
    setTimeout(()=> alert(`screen w : ${window.innerWidth} - screen h : ${window.innerHeight}`),
        3000);

        */
    
    document.querySelectorAll('.select-country').forEach((countrySelect, key)=>{
        countrySelect.addEventListener('change', (event)=>{
            let countryId = event.target.value;
            let townSelect = countrySelect.parentElement.querySelector('.select-town');

            var firstChange = (optionValue)=>{
                townSelect.value=optionValue;
                //console.log(`${optionValue} : ${townSelect.value}`);
                firstChange = (optionValue)=>{}
            }
            for(const option of townSelect.children){
                if(option.getAttribute('country_id')==countryId){
                    firstChange(option.value);

                    option.style.display = "initial";
                }else{
                    option.style.display = "none";
                }
                
            }
        });

        const newEvent = new Event('change');
        countrySelect.dispatchEvent(newEvent);
        
    });

/*
    document.querySelector('.btn-recherche.recherche-bus')?.addEventListener('click', (event)=>{
        searchBus({});
    });


    

    function refreshResultatBus(){
        let listeBusDom = document.querySelector('.liste-resultat');
        if(listeBusDom !=null){
            var strResultat = "";
            for(let idxBus in listeBus){
                let bus = listeBus[idxBus];
                var canDisplay = false;
                
                var canDisplayTag = true;
                confortFiltreSelected.forEach((confort)=>{
                    if(bus.tags.has(confort)){
                        canDisplayTag &= true;
                    }else{
                        canDisplayTag &= false;
                    }
                });

                var canDisplayName = false;
                if(busFiltreSelected.has(bus.name)) canDisplayName = true;

                canDisplay = canDisplayTag && canDisplayName;

                if(canDisplay){
                    strResultat+= `
<div class="col  d-block p-3  ">
    <div class="p-3 rounded border-white border-light  bg-primary btn btn-primary btn-simple" onclick="reservationBus(${idxBus})" >
        <div class="d-flex justify-content-center mb-2"><h4 class="mb-2 text-white-grey"  style="overflow: hidden; text-overflow: ellipsis !important; white-space: nowrap !important;">${bus.name}</h4></div>
        <div class="d-flex justify-content-center mb-2"><h1 class="mb-2 text-white-grey  "><i class="fa fa-bus rounded-circle p-3 border-white border-light"></i></h1></div>
        <div class="d-flex justify-content-center"><h6 class="mb-1 text-white-grey"  style="overflow: hidden; text-overflow: ellipsis !important; white-space: nowrap !important;">${bus.type}</h6></div>
        
        <div class="d-flex justify-content-center"><div class="btn btn-white rounded-pill py-1 px-3 em1-1 m-2 border-grey">${bus.prix} ${bus.monnaie_symbol}</div></div>
    </div>
</div>
                    `;
                }

                
            }

            listeBusDom.innerHTML = strResultat;
        }
        
    }

*/
if(typeof setSelectedFilter !== 'undefined'){
    setSelectedFilter = ({set, target})=>{
        console.log(`text : ${target.textContent}`);
        if(target.classList.contains('selected')){
            target.classList.remove('selected');

            target.classList.remove('btn-primary', 'border-white');
            target.classList.add('btn-white', 'border-grey');
            set.delete(target.textContent);
        }else{
            target.classList.add('selected');

            target.classList.add('btn-primary', 'border-white');
            target.classList.remove('btn-white', 'border-grey');
            set.add(target.textContent);
        }
    }
}
/*
    function initFiltreBusConfort(){
        //repartition des tags dans le DOM
        let filtreConfort = document.querySelector('.filtre-confort');
        if(filtreConfort !=null){
            var strFiltre = "";
            for(let titre of confortFiltre){
                strFiltre += `<div class="align-self-center btn btn-white rounded-pill py-1 px-2 em1-1 m-2 border-light border-grey" onclick="this.">${titre}</div>`;
                //`<div class="align-self-center btn btn-primary rounded-pill py-1 px-2 em1-1 m-2 border-light border-white">${titre}</div>`;
            }
            filtreConfort.innerHTML = strFiltre;
            confortFiltreSelected.clear();

            for(const child of filtreConfort.children){
                child.addEventListener('click', (event)=>{
                    let target = event.target;
                    setSelectedFilter({
                        set: confortFiltreSelected,
                        target
                    });

                    refreshResultatBus();
                });
            }
            
        
        }
    }
    
    function initFiltreBusCompagnie(){
        //repartition des noms de compagnie dans le DOM
        let filtreCompagnie = document.querySelector('.filtre-compagnie');
        if(filtreCompagnie !=null){
            var strFiltre = "";
            for(let titre of busFiltre){
                //`<div class="align-self-center btn btn-white rounded-pill py-1 px-2 em1-1 m-2 border-light border-grey">${titre}</div>`;
                strFiltre += `<div class="align-self-center btn btn-primary rounded-pill py-1 px-2 em1-1 m-2 border-light border-white selected">${titre}</div>`;
            }
            filtreCompagnie.innerHTML = strFiltre;
            busFiltre.forEach((elt)=>{
                busFiltreSelected.add(elt);
            }) 

            for(const child of filtreCompagnie.children){
                child.addEventListener('click', (event)=>{
                    let target = event.target;
                    setSelectedFilter({
                        set: busFiltreSelected,
                        target
                    });

                    refreshResultatBus();
                });
            }

        }
    }




    function searchBus({
		form_date = '07/03/2024',
    }){
        let from_town_id = towns?.source?.value;
	    let to_town_id = towns?.destination?.value;

        console.log("searchbus : from "+from_town_id+" to "+to_town_id);

        if(from_town_id != null && to_town_id != null){
            
            myFetch({
                url: "https://www.tnsarl.com/m/searchbus", 
                json: {
                    from_town_id,
                    to_town_id,
                    form_date
                },
                thenFct: (res)=>{
                    //console.log(res);
    
                    listeBus = [];
                    confortFiltre = new Set();
                    busFiltre = new Set();
                    
                    for(let i in res.data){
                        let data = res.data[i];
                        
                        let busName = data['Destination']['Car']['Company']['name'];
                        let busType = data['Destination']['Car']['name'];
                        let busPrix = data['Rate']['amount'];
                        let busMonnaieSymbole = data['Currency']['symbol'];
                        
    
    
    
                        let lstObjTags = data['Destination']['Car']['Tag'];
                        var tags = new Set();
                        for(let i in lstObjTags){
                            let tagName = lstObjTags[i]['name'];
                            tags.add(tagName);
                            confortFiltre.add(tagName);
                        }
    
                        busFiltre.add(busName);
    
                        listeBus.push({
                            name: busName,
                            type: busType,
                            prix: busPrix,
                            monnaie_symbol: busMonnaieSymbole,
                            
                            schedule_start : data['Schedule']['start'],
                            schedule_arrets : data['Rate']['stop'],
                            rate_id : data['Rate']['id'],
                            schedule_id: data['Schedule']['id'],
                            company_id : data['Destination']['Company']['id'],
                            from_town_id: data['From']['id'],
                            to_town_id: data['To']['id'],
                            currency_id: data['Currency']['id'],
                            currency_iso: data['Currency']['iso'],
                            
    
                            tags
                            
                        });
                        
                        
                    }
    
                    console.log({listeBus, confortFiltre, busFiltre});
    
                    initFiltreBusConfort();
                    
                    initFiltreBusCompagnie();
    
                    
    
              
                    refreshResultatBus();
                    
                }, 
                spinner: true,
                
                
            });
        }

        
    }

    searchBus({});


    let btnResetFiltreBusConfort = document.querySelector('.reset-filtre-confort');
    if(btnResetFiltreBusConfort !=null){
        btnResetFiltreBusConfort.addEventListener('click', (event)=>{
            initFiltreBusConfort();
            refreshResultatBus();
        })

    }

    let btnResetFiltreBusCompagnie = document.querySelector('.reset-filtre-compagnie');
    if(btnResetFiltreBusCompagnie !=null){
        btnResetFiltreBusCompagnie.addEventListener('click', (event)=>{
            initFiltreBusCompagnie();
            refreshResultatBus();
        })

    }
*/
    $('.datepicker-bootstrap').datepicker({
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap5'
    });
   // $('#reservationBusModal .datepicker').datepicker();

   if(typeof search !== 'undefined'){
        search({});
   }

})(jQuery);

