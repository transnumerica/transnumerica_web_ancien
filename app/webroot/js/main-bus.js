var setSelectedFilter = ({set, target})=>{}

var listeBus = [];
var confortFiltre = new Set();
var busFiltre = new Set();

var confortFiltreSelected = new Set();
var busFiltreSelected = new Set();

let reservationModalBtn = document.querySelector('.reservationModalBtn');
let reservationModal = document.querySelector("#reservationModal");

var busReservation = null;

var factureList = [];
var reservationTotal = 0;
let eltPlaceNbrTotal = reservationModal.querySelector('.place-nbr-total');
let eltDatePicker = reservationModal.querySelector('#datepicker');

let eltPlaceCount = reservationModal.querySelector('.container-place-price .display');

function refreshPricesOnFacture(bus){
    let prix = bus?.prix??0;

    console.log('prix - ', prix);

    if(eltPlaceCount !=null) eltPlaceCount.textContent = `${prix}${bus?.monnaie_symbol}`;
    
}



function refreshTotalOnFacture(){
    
    
    let prix = parseFloat(busReservation.prix??0);
   
    let strQuantite = reservationModal?.querySelector('.container-place-price .display')?.value;
    let quantite = parseInt(strQuantite);
    if(isNaN(quantite)) quantite = 0;

    factureList = [{
        titre:"economic",
        name:"all",
        places: JSON.stringify(Array.from(new Set())),
        prix:`${prix}`,
        quantite:`${quantite}`,
        tours:"1"
    }];


    reservationTotal = quantite*prix;


    let totalElt = document.querySelector('.container-total .total');
    if(totalElt !=null){
        totalElt.textContent = `${reservationTotal}${busReservation?.monnaie_symbol??""}`;
    }


}

function redresserNbrPlaceChoisi(){
    var nbrActu = parseInt(eltPlaceCount?.value??0);
        
    let nbrPlaceRestant = parseInt(eltPlaceNbrTotal?.textContent);
    
    if(nbrActu<0){eltPlaceCount.value = 0}
    else if(nbrActu>nbrPlaceRestant){eltPlaceCount.value = nbrPlaceRestant}
    
}

function refreshNbrPlaceRestantOnHtml(nbr){
    eltPlaceNbrTotal.textContent = nbr;
    redresserNbrPlaceChoisi();
}

function refreshNbrPlaceVendues(){
    let date = eltDatePicker.value;
    let bus = busReservation;
    if(date.length>0){
        if(bus.nbr_place_vendues[date] !=null){
            let nbrPlaceVendues = bus.nbr_place_vendues[date];
            refreshNbrPlaceRestantOnHtml(bus.nbr_place - nbrPlaceVendues);
            
        }else{
            myFetch({
                url: `${window.location.origin}/m/bus_nbr_place_vendues`, //"https://www.tnsarl.com/m/bus_nbr_place_vendues", 
                json: {
                    departure_date: date, 
                    company_destination_id: bus.destination_id,
                },
                thenFct: (res)=>{
                    console.log({res_date:res});
                    if(res?.success==true){
                        bus.nbr_place_vendues[date] = parseInt(res?.data?.nbr_place_vendues);

                    }else{
                        bus.nbr_place_vendues[date] = 0;
                    }

                    let nbrPlaceVendues = bus.nbr_place_vendues[date];
                    refreshNbrPlaceRestantOnHtml(bus.nbr_place - nbrPlaceVendues);
                    
                    
                }, 
                spinner: true,
                
                
            });
        }
    }else{
        refreshNbrPlaceRestantOnHtml(bus.nbr_place);
    }
    
}

reservationModal?.querySelectorAll('.container-place-price .display')?.forEach((item)=>{
    item?.addEventListener('click', (e)=>{
        redresserNbrPlaceChoisi();
        refreshTotalOnFacture();
    });
});

var eltDatePickerFocusOut = false;
eltDatePicker?.addEventListener('focusout', (e)=>{
    eltDatePickerFocusOut = true;
});

eltDatePicker?.addEventListener('focusin', (e)=>{
    console.log('eltDatePicker-focusin')
    if(eltDatePickerFocusOut) refreshNbrPlaceVendues(e.target);
    eltDatePickerFocusOut = false;
});



function reservationBus(idxBus){
    let bus = listeBus[idxBus];
    busReservation = bus;

    if(bus !=null && reservationModalBtn !=null && reservationModal !=null){
        console.log(`in reservationBus(${idxBus})`);

        let operatorName = reservationModal.querySelector('.operator-name');
        if(operatorName !=null){
            operatorName.textContent = bus.name;
        }

        refreshNbrPlaceVendues();
        
        //let economic = parseFloat(bus.prix);

        refreshPricesOnFacture(bus);
        refreshTotalOnFacture();
        
        
        //setSeatChart({price:{first: economic*3, second: economic*1.5, economic}, monnaie_symbol: bus.monnaie_symbol});
        reservationModalBtn.click();
    }
}


document.querySelector('.btn-acheter-billet-bus')?.addEventListener('click', (event)=>{
    if(user?.connected??false){
        var msgError = "";
        let total = `${reservationTotal}`;//$(`.bus-seats .wrapper.${timeWrapper} #total`)?.text()??"0";
        if(total=="0") msgError +="<li>Vous devez sélectionner au moins une place</li>";

        let departure_date = eltDatePicker?.value??"";
        if(departure_date=="") msgError +="<li>Vous devez choisir une date</li>";

        var mobileMoney = '';
        var mmoneyCorrect = true;
        var nombre = parseInt(document.querySelector('#reservationModal #mobile-money-prefix')?.value);
        console.log("nombre 1 : "+nombre);
        if(!isNaN(nombre)){
            if(nombre>0)mobileMoney+= "+"+nombre+"-";
            else mmoneyCorrect = false;
        } 
        else mmoneyCorrect = false;
        
        
        nombre = parseInt(document.querySelector('#reservationModal #mobile-money-suffix')?.value);
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

        let nbr_place = eltPlaceCount?.value;

        if(msgError.length==0){
            let data = {
                service : "bus",
                auth_id: user?.id,
                user_id: user?.id,
                total: total,
                rate_id: bus.rate_id??"",
                departure_date: departure_date,
                schedule_id: bus.schedule_id,
                place: JSON.stringify({}), //placesBus
                nbr_place,
            
                company_name: bus.name??"",
                category_company_id: bus.company_id,
                company_destination_id: bus.destination_id,
                from_country_mobile_code: "",
                from_town_id: bus.from_town_id,
                to_country_mobile_code: "",
                to_town_id: bus.to_town_id,
                currency_id: bus.currency_id,
                currency_iso: bus.currency_iso??"",
                user_total: total,
                user_facture_list_data: JSON.stringify(factureList),//Object.values(mapFactureBus)),
                user_facture_list_type_billet: JSON.stringify({}),
                phone: mobileMoney,

                from_web: true,
            }

            console.log('avant : ', data);

            myFetchMMoney({data});
            delete bus.nbr_place_vendues[departure_date];
            


        }else{
            alertK("<ul>"+msgError+"</ul>");
            
        }
            


    }else{
        alertK(`
<div class="text-center">
    <div class="">Veuillez vous connecter avant d'éffectuer un achat</div>
    <a class="btn btn-primary text-white-grey border-white border-light mt-2" href="${window.location.origin}/users/login" >Se Connecter</a>
</div>
`);
    }
    
});


(function ($) {
    "use strict";

  
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
        console.log("relative http : ", `${window.location.origin}/m/searchbus`);
        let from_town_id = towns?.source?.value;
	    let to_town_id = towns?.destination?.value;

        console.log("searchbus : from "+from_town_id+" to "+to_town_id);

        if(from_town_id != null && to_town_id != null){
            
            myFetch({
                url: `${window.location.origin}/m/searchbus`, //"https://www.tnsarl.com/m/searchbus", 
                json: {
                    from_town_id,
                    to_town_id,
                    form_date,
                    from_web: true,
                },
                thenFct: (res)=>{
                    console.log(res);
    
                    listeBus = [];
                    confortFiltre = new Set();
                    busFiltre = new Set();
                    
                    for(let i in res.data){
                        let data = res.data[i];
                        
                        let busName = data['Destination']['Car']['Company']['name'];
                        let busType = data['Destination']['Car']['name'];
                        let busPrix = data['Rate']['amount'];
                        let busMonnaieSymbole = data['Currency']['symbol'];
                        let nbr_place = data['Destination']['nbr_place'];
                        //console.log({nbr_place});
                        
    
    
    
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
                            destination_id : data['Destination']['id'],
                            from_town_id: data['From']['id'],
                            to_town_id: data['To']['id'],
                            currency_id: data['Currency']['id'],
                            currency_iso: data['Currency']['iso'],
                            nbr_place,
                            nbr_place_vendues: {},
    
                            tags
                            
                        });
                        
                        
                    }
    
                    //console.log({listeBus, confortFiltre, busFiltre});
    
                    initFiltreBusConfort();
                    
                    initFiltreBusCompagnie();
    
                    
    
              
                    refreshResultatBus();
                    
                }, 
                spinner: true,
                
                
            });
        }

        
    }

    


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

    let search = searchBus;


})(jQuery);

