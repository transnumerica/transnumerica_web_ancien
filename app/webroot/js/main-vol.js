

var variables;

if(variables == null){
    variables = {
        service: "avion",
        type: "plane",
        fa: "fa-plane",
        search: "searchplane",
        Plane: "Plane",
        CompanyPlaneDestination: "CompanyPlaneDestination",
    }
}


var setSelectedFilter = ({set, target})=>{}


var listePlane = [];
var planeFiltre = new Set();

var planeFiltreSelected = new Set();

let reservationModalBtn = document.querySelector('.reservationModalBtn');
let reservationModal = document.querySelector("#reservationModal");

var planeReservation = null;

var prixAdult = null,
    prixEnfant = null,
    prixBebe = null;

var mapTypeBillet = {};
var factureList = [];
var reservationTotal = 0;

let eltAdultCount = reservationModal?.querySelector('.container-adult-price .display');
let eltKidCount = reservationModal?.querySelector('.container-kid-price .display');
let eltBabyCount = reservationModal?.querySelector('.container-baby-price .display');

function refreshPricesOnFacture(plane){
    mapTypeBillet = {};

    var type_billet = 'oneway';
    mapTypeBillet.alle_retour = {titre:"Allée Simple",name:"allee_simple"};
    reservationModal?.querySelectorAll(".container-allee-retour-radio input")?.forEach((elt)=>{
        if(elt?.id == "alle-retour-radio" && elt?.checked){
            type_billet = 'roundtrip';
            mapTypeBillet.alle_retour = {titre:"Allée - Retour",name:"allee_retour"};
        }
    });
    
    var classe_billet = 'economic';
    mapTypeBillet.niveau_classe = {titre:"Economic",name:"economic"};
    reservationModal?.querySelectorAll(".container-classe input")?.forEach((elt)=>{
        if(elt?.id == "firstclass-radio" && elt?.checked){
            classe_billet = 'firstclass';
            mapTypeBillet.niveau_classe = {titre:"First Class",name:"firstclass"};
        }
    });

    let lesPrix = plane??{};
    lesPrix = lesPrix.prixMap??{};
    lesPrix = lesPrix[classe_billet]??{};
    lesPrix = lesPrix[type_billet]??{};
    
    //console.log('alleRetourChecked', alleRetourChecked);
    //console.log('classeChecked', classeChecked);
    console.log('type_billet', type_billet);
    console.log('classe_billet', classe_billet);
    console.log('plane', plane);
    console.log('lesPrix', lesPrix);

    prixAdult = parseFloatWeb(lesPrix.adult);
    prixEnfant = parseFloatWeb(lesPrix.kid);
    prixBebe = parseFloatWeb(lesPrix.baby);
    
    let adultElt = reservationModal.querySelector('.container-adult-price');
    if(!isNaN(prixAdult)){
        adultElt?.classList.remove('d-none');
        let priceElt = adultElt?.querySelector('.adult-price');
        if(priceElt !=null) priceElt.textContent = `${prixAdult}${plane?.monnaie_symbol}`;
    }else adultElt?.classList.add('d-none');

    let kidElt = reservationModal.querySelector('.container-kid-price');
    if(!isNaN(prixEnfant)){
        kidElt?.classList.remove('d-none');
        let priceElt = kidElt?.querySelector('.kid-price');
        if(priceElt !=null) priceElt.textContent = `${prixEnfant}${plane?.monnaie_symbol}`;
    }else kidElt?.classList.add('d-none');

    let babyElt = reservationModal.querySelector('.container-baby-price');
    if(!isNaN(prixBebe)){
        babyElt?.classList.remove('d-none');
        let priceElt = babyElt?.querySelector('.baby-price');
        if(priceElt !=null) priceElt.textContent = `${prixBebe}${plane?.monnaie_symbol}`;
    }else babyElt?.classList.add('d-none');
}



function refreshTotalOnFacture(){
    factureList = [];

    reservationTotal = 0;
    console.log("ici refreshTotalOnFacture");
    if(!isNaN(prixAdult)){
        let strQuantite = eltAdultCount?.value;
        let quantite = parseInt(strQuantite);
        if(isNaN(quantite)) quantite = 0;

        factureList.push({
            titre:"Adulte",
            name:"adult",
            prix:`${prixAdult}`,
            quantite:`${quantite}`,
            tours:"1"
        });

        reservationTotal += quantite*prixAdult;
        console.log("total - adult : ", quantite*prixAdult);
    }

    if(!isNaN(prixEnfant)){
        let strQuantite = eltKidCount?.value;
        let quantite = parseInt(strQuantite);
        if(isNaN(quantite)) quantite = 0;

        factureList.push({
            titre:"Enfant",
            name:"kid",
            prix:`${prixEnfant}`,
            quantite:`${quantite}`,
            tours:"1"
        });

        reservationTotal += quantite*prixEnfant;
    }

    if(!isNaN(prixBebe)){
        let strQuantite = eltBabyCount?.value;
        let quantite = parseInt(strQuantite);
        if(isNaN(quantite)) quantite = 0;

        factureList.push({
            titre:"Bébé",
            name:"baby",
            prix:`${prixBebe}`,
            quantite:`${quantite}`,
            tours:"1"
        });

        reservationTotal += quantite*prixBebe;
    }

    let totalElt = document.querySelector('.container-total .total');
    if(totalElt !=null){
        totalElt.textContent = `${reservationTotal}${planeReservation?.monnaie_symbol??""}`;
    }


}

reservationModal?.querySelectorAll('.container-allee-retour-radio input')?.forEach((item)=>{
    item.addEventListener('click', (e)=>{
        console.log(e.target.checked, e.target);
        if(e.target.id == "alle-simple-radio"){
            reservationModal?.querySelector('.container-datepicker-retour')?.classList?.add('d-none');
        }else if(e.target.id == "alle-retour-radio"){
            reservationModal?.querySelector('.container-datepicker-retour')?.classList?.remove('d-none');
        }

        refreshPricesOnFacture(planeReservation);
        refreshTotalOnFacture();
    });
});

reservationModal?.querySelectorAll('.container-classe input')?.forEach((item)=>{
    item.addEventListener('click', (e)=>{
        refreshPricesOnFacture(planeReservation);
        refreshTotalOnFacture();
    });
});

reservationModal?.querySelectorAll('.container-adult-price .display, .container-kid-price .display, .container-baby-price .display')?.forEach((item)=>{
    item?.addEventListener('click', (e)=>{
        refreshTotalOnFacture();
    });
});

function reservationPlane(idxPlane){
    let plane = listePlane[idxPlane];
    planeReservation = plane;

    if(plane !=null && reservationModalBtn !=null && reservationModal !=null){

        let operatorName = reservationModal.querySelector('.operator-name');
        if(operatorName !=null){
            operatorName.textContent = plane.name;
        }
        
        refreshPricesOnFacture(plane);
        refreshTotalOnFacture();
        

        reservationModalBtn.click();
    }
}


document.querySelector(`.btn-acheter-billet-${variables.type}`)?.addEventListener('click', (event)=>{
    if(user?.connected??false){
        var msgError = "";
        let total = `${reservationTotal}`;
        if(total=="0") msgError +="<li>Vous devez être au moins une personne à voyager</li>";
        
        
        let departure_date = document.querySelector('#reservationModal #datepicker')?.value??"";
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

        let nbr_place = parseInt(eltAdultCount?.value??0) + parseInt(eltKidCount?.value??0) + parseInt(eltBabyCount?.value??0);

        let plane = planeReservation;
        if(msgError.length==0){
            let data = {
                service : variables.service,
                auth_id: user?.id,
                user_id: user?.id,
                total: total,
                rate_id: "",
                departure_date: departure_date,
                schedule_id: "",
                place: JSON.stringify({}), //placesPlane
                nbr_place,
            
                company_name: plane.name??"",
                category_company_id: plane.company_id,
                company_destination_id: plane.destination_id,
                from_country_mobile_code: "",
                from_town_id: plane.from_town_id,
                to_country_mobile_code: "",
                to_town_id: plane.to_town_id,
                currency_id: plane.currency_id,
                currency_iso: plane.currency_iso??"",
                user_total: total,
                user_facture_list_data: JSON.stringify(factureList),
                user_facture_list_type_billet: JSON.stringify(mapTypeBillet),
                phone: mobileMoney,

                from_web: true,

            }

            console.log('avant : ', data);

            myFetchMMoney({data});


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

    document.querySelector(`.btn-recherche.recherche-${variables.type}`)?.addEventListener('click', (event)=>{
        searchPlane({});
    });

   
    

    

    function refreshResultatPlane(){
        let listePlaneDom = document.querySelector('.liste-resultat');
        if(listePlaneDom !=null){
            var strResultat = "";
            
            console.log('planeFiltreSelected',planeFiltreSelected);

            for(let idxPlane in listePlane){
                let plane = listePlane[idxPlane];
                var canDisplay = false;
                

                var canDisplayName = false;
                if(planeFiltreSelected.has(plane.name)) canDisplayName = true;

                canDisplay = canDisplayName;

                if(canDisplay){
                    strResultat+= `
<div class="col  d-block p-3  ">
    <div class="p-3 rounded border-white border-light  bg-primary btn btn-primary btn-simple" onclick="reservationPlane(${idxPlane})" >
        <div class="d-flex justify-content-center mb-2"><h4 class="mb-2 text-white-grey"  style="overflow: hidden; text-overflow: ellipsis !important; white-space: nowrap !important;">${plane.name}</h4></div>
        <div class="d-flex justify-content-center mb-2"><h1 class="mb-2 text-white-grey  "><i class="fa ${variables.fa} rounded-circle p-3 border-white border-light"></i></h1></div>
        <div class="d-flex justify-content-center"><h6 class="mb-1 text-white-grey"  style="overflow: hidden; text-overflow: ellipsis !important; white-space: nowrap !important;">${plane.type}</h6></div>
        
        <div class="d-flex justify-content-center ${plane.prix==null?'d-none ':''}"><div class="btn btn-white rounded-pill py-1 px-3 em1-1 m-2 border-grey">${plane.prix} ${plane.monnaie_symbol}</div></div>
    </div>
</div>
                    `;
                }

                
            }

            
            listePlaneDom.innerHTML = strResultat;
        }
        
    }


    
    function initFiltrePlaneCompagnie(){
        //repartition des noms de compagnie dans le DOM
        let filtreCompagnie = document.querySelector('.filtre-compagnie');
        console.log('filtreCompagnie', filtreCompagnie);
        if(filtreCompagnie !=null){
            var strFiltre = "";
            for(let titre of planeFiltre){
                //`<div class="align-self-center btn btn-white rounded-pill py-1 px-2 em1-1 m-2 border-light border-grey">${titre}</div>`;
                strFiltre += `<div class="align-self-center btn btn-primary rounded-pill py-1 px-2 em1-1 m-2 border-light border-white selected">${titre}</div>`;
            }
            filtreCompagnie.innerHTML = strFiltre;
            planeFiltre.forEach((elt)=>{
                planeFiltreSelected.add(elt);
            }) 

            for(const child of filtreCompagnie.children){
                child.addEventListener('click', (event)=>{
                    let target = event.target;
                    setSelectedFilter({
                        set: planeFiltreSelected,
                        target
                    });

                    refreshResultatPlane();
                });
            }

        }
    }




    function searchPlane({
		form_date = '07/03/2024',
    }){
        let from_town_id = towns?.source?.value;
	    let to_town_id = towns?.destination?.value;

        console.log("searchplane : from "+from_town_id+" to "+to_town_id);

        if(from_town_id != null && to_town_id != null){
            
            myFetch({
                url: `${window.location.origin}/m/${variables.search}`, 
                json: {
                    from_town_id,
                    to_town_id,
                    form_date,
                    from_web: true,
                },
                thenFct: (res)=>{
                    console.log(res);
    
                    listePlane = [];
                    planeFiltre = new Set();
                    
                    for(let i in res.data){
                        let data = res.data[i];
                        
                        let planeName = data[variables.Plane]['name'];
                        let planeType = data[variables.CompanyPlaneDestination]['name'];
                        
                        let planeMonnaieSymbole = data['Currency']['symbol'];
                        
                        
                        let planePrixMap = {
                            economic: {
                                oneway: {}, 
                                roundtrip: {}
                            },
                            firstclass: {
                                oneway: {}, 
                                roundtrip: {}
                            },
                            
                        }
                        
                        var prix = undefined;

                        function from_amounts_to_plane_prix(amounts, pricePlace){
                            amounts.split(";").forEach((value)=>{
                                let kv = value.split("=");
                                pricePlace[kv[0]] = kv[1];
                                if(isNaN(prix)) prix = parseFloat(kv[1]);
                            })
                        }

                        let amounts_economic_oneway = data[variables.CompanyPlaneDestination]['amounts_economic_oneway'];
                        let amounts_economic_roundtrip = data[variables.CompanyPlaneDestination]['amounts_economic_roundtrip'];
                        let amounts_firstclass_oneway = data[variables.CompanyPlaneDestination]['amounts_firstclass_oneway'];
                        let amounts_firstclass_roundtrip = data[variables.CompanyPlaneDestination]['amounts_firstclass_roundtrip'];
                        

                        from_amounts_to_plane_prix(amounts_economic_oneway, planePrixMap.economic.oneway);
                        from_amounts_to_plane_prix(amounts_economic_roundtrip, planePrixMap.economic.roundtrip);
                        from_amounts_to_plane_prix(amounts_firstclass_oneway, planePrixMap.firstclass.oneway);
                        from_amounts_to_plane_prix(amounts_firstclass_roundtrip, planePrixMap.firstclass.roundtrip);

                        
    
                        /*
                        let lstObjTags = data['Destination']['Car']['Tag'];
                        var tags = new Set();
                        for(let i in lstObjTags){
                            let tagName = lstObjTags[i]['name'];
                            tags.add(tagName);
                            confortFiltre.add(tagName);
                        }
                        */
                        planeFiltre.add(planeName);
    
                        listePlane.push({
                            name: planeName,
                            type: planeType,
                            prix,
                            prixMap: planePrixMap,
                            monnaie_symbol: planeMonnaieSymbole,
                            /*
                            schedule_start : data['Schedule']['start'],
                            schedule_arrets : data['Rate']['stop'],
                            rate_id : data['Rate']['id'],
                            schedule_id: data['Schedule']['id'],
                            */
                            company_id : data[variables.Plane]['id'],
                            from_town_id: data[variables.CompanyPlaneDestination]['from_town_id'],
                            to_town_id: data[variables.CompanyPlaneDestination]['to_town_id'],
                            currency_id: data['Currency']['id'],
                            currency_iso: data['Currency']['iso'],

                            destination_id: data[variables.CompanyPlaneDestination]['id'],
                            
                            
                        });

                        
                        
                        
                    }
    
                    console.log({listePlane, planeFiltre});
    
                    
                    
                    initFiltrePlaneCompagnie();
    
                    
    
              
                    refreshResultatPlane();
                    
                }, 
                spinner: true,
                
                
            });
        }

        
    }

    


    

    let btnResetFiltrePlaneCompagnie = document.querySelector('.reset-filtre-compagnie');
    if(btnResetFiltrePlaneCompagnie !=null){
        btnResetFiltrePlaneCompagnie.addEventListener('click', (event)=>{
            initFiltrePlaneCompagnie();
            refreshResultatPlane();
        })

    }

    let search = searchPlane;


})(jQuery);

