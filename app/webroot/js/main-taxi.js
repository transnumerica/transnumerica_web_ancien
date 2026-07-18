

var variables;

if(variables == null){
    variables = {
        service: "taxi",
        type: "taxi",
        fa: "fa-car",
        search: "searchtaxi",
        Plane: null,
        CompanyPlaneDestination: "CompanyTaxi",
    }
}


var setSelectedFilter = ({set, target})=>{}


var listeTaxi = [];
var taxiFiltre = new Set();

var taxiFiltreSelected = new Set();

let reservationModalBtn = document.querySelector('.reservationModalBtn');
let reservationModal = document.querySelector("#reservationModal");

var taxiReservation = null;

var factureList = [];
var reservationTotal = 0;

let eltHourCount = reservationModal?.querySelector('.container-hour-price .display');

function refreshPricesOnFacture(taxi){
    let prix = taxi?.prix??0;

    console.log('prix - ', prix);

    let priceElt = reservationModal.querySelector('.container-hour-price .hour-price');
    if(priceElt !=null) priceElt.textContent = `${prix}${taxi?.monnaie_symbol}`;
    
}



function refreshTotalOnFacture(){
    
    
    let prix = taxiReservation.prix??0;
   
    let strQuantite = eltHourCount?.value;
    let quantite = parseInt(strQuantite);
    if(isNaN(quantite)) quantite = 0;

    factureList = [{
        titre:"Heure",
        name:"all",
        prix:`${taxiReservation.prix}`,
        quantite:`${quantite}`,
        tours:"1"
    }];

    reservationTotal = quantite*prix;
    console.log("prix -  : ", prix);
    console.log("quantite -  : ", quantite);
    console.log("reservationTotal -  : ", reservationTotal);


    let totalElt = document.querySelector('.container-total .total');
    if(totalElt !=null){
        totalElt.textContent = `${reservationTotal}${taxiReservation?.monnaie_symbol??""}`;
    }


}


reservationModal?.querySelectorAll('.container-hour-price .display')?.forEach((item)=>{
    item?.addEventListener('click', (e)=>{
        refreshTotalOnFacture();
    });
});

function reservationTaxi(idxTaxi){
    let taxi = listeTaxi[idxTaxi];
    taxiReservation = taxi;

    if(taxi !=null && reservationModalBtn !=null && reservationModal !=null){

        let operatorName = reservationModal.querySelector('.operator-name');
        if(operatorName !=null){
            operatorName.textContent = taxi.name;
        }
        
        refreshPricesOnFacture(taxi);
        refreshTotalOnFacture();
        

        reservationModalBtn.click();
    }
}


document.querySelector(`.btn-acheter-billet-${variables.type}`)?.addEventListener('click', (event)=>{
    if(user?.connected??false){
        var msgError = "";
        let total = `${reservationTotal}`;
        if(total=="0") msgError +="<li>Vous devez sélectionner au minimum une heure pour le service</li>";
        
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

        let nbr_place = parseInt(eltHourCount?.value??0);

        let taxi = taxiReservation;
        if(msgError.length==0){
            let data = {
                service : variables.service,
                auth_id: user?.id,
                user_id: user?.id,
                total: total,
                rate_id: "",
                departure_date: departure_date,
                schedule_id: "",
                place: JSON.stringify({}), //placesTaxi
                nbr_place,
            
                company_name: taxi.name??"",
                category_company_id: taxi.company_id,
                company_destination_id: taxi.destination_id,
                from_country_mobile_code: "",
                from_town_id: taxi.from_town_id,
                to_country_mobile_code: "",
                to_town_id: "0",
                currency_id: taxi.currency_id,
                currency_iso: taxi.currency_iso??"",
                user_total: total,
                user_facture_list_data: JSON.stringify(factureList),
                user_facture_list_type_billet: JSON.stringify({}),
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
        searchTaxi({});
    });

   
    

    

    function refreshResultatTaxi(){
        let listeTaxiDom = document.querySelector('.liste-resultat');
        if(listeTaxiDom !=null){
            var strResultat = "";
            
            console.log('taxiFiltreSelected',taxiFiltreSelected);

            for(let idxTaxi in listeTaxi){
                let taxi = listeTaxi[idxTaxi];
                var canDisplay = false;
                

                var canDisplayName = false;
                if(taxiFiltreSelected.has(taxi.name)) canDisplayName = true;

                canDisplay = canDisplayName;

                if(canDisplay){
                    strResultat+= `
<div class="col  d-block p-3  ">
    <div class="p-3 rounded border-white border-light  bg-primary btn btn-primary btn-simple" onclick="reservationTaxi(${idxTaxi})" >
        <div class="d-flex justify-content-center mb-2"><h4 class="mb-2 text-white-grey"  style="overflow: hidden; text-overflow: ellipsis !important; white-space: nowrap !important;">${taxi.name}</h4></div>
        <div class="d-flex justify-content-center mb-2"><h1 class="mb-2 text-white-grey  "><i class="fa ${variables.fa} rounded-circle p-3 border-white border-light"></i></h1></div>
        <div class="d-flex justify-content-center"><h6 class="mb-1 text-white-grey"  style="overflow: hidden; text-overflow: ellipsis !important; white-space: nowrap !important;">${taxi.type}</h6></div>
        
        <div class="d-flex justify-content-center ${taxi.prix==null?'d-none ':''}"><div class="btn btn-white rounded-pill py-1 px-3 em1-1 m-2 border-grey">${taxi.prix} ${taxi.monnaie_symbol}</div></div>
    </div>
</div>
                    `;
                }

                
            }

            
            listeTaxiDom.innerHTML = strResultat;
        }
        
    }


    
    function initFiltreTaxiCompagnie(){
        //repartition des noms de compagnie dans le DOM
        let filtreCompagnie = document.querySelector('.filtre-compagnie');
        console.log('filtreCompagnie', filtreCompagnie);
        if(filtreCompagnie !=null){
            var strFiltre = "";
            for(let titre of taxiFiltre){
                //`<div class="align-self-center btn btn-white rounded-pill py-1 px-2 em1-1 m-2 border-light border-grey">${titre}</div>`;
                strFiltre += `<div class="align-self-center btn btn-primary rounded-pill py-1 px-2 em1-1 m-2 border-light border-white selected">${titre}</div>`;
            }
            filtreCompagnie.innerHTML = strFiltre;
            taxiFiltre.forEach((elt)=>{
                taxiFiltreSelected.add(elt);
            }) 

            for(const child of filtreCompagnie.children){
                child.addEventListener('click', (event)=>{
                    let target = event.target;
                    setSelectedFilter({
                        set: taxiFiltreSelected,
                        target
                    });

                    refreshResultatTaxi();
                });
            }

        }
    }




    function searchTaxi({
		form_date = '07/03/2024',
    }){
        let town_id = towns?.source?.value;

        console.log("searchtaxi : "+town_id);

        if(town_id != null){
            
            myFetch({
                url: `${window.location.origin}/m/${variables.search}`, 
                json: {
                    town_id,
                    form_date,
                    from_web: true,
                },
                thenFct: (res)=>{
                    //console.log(res);
    
                    listeTaxi = [];
                    taxiFiltre = new Set();
                    
                    for(let i in res.data){
                        let data = res.data[i];
                        
                        let taxiName = data[variables.CompanyPlaneDestination]['name'];
                        let taxiType = data[variables.CompanyPlaneDestination]['name'];
                        
                        let taxiMonnaieSymbole = data['Currency']['symbol'];
                        
                        
                        let prix = parseFloat(data[variables.CompanyPlaneDestination]['hour_amount']);
                        if(isNaN(prix)) prix =0;
                       
                        taxiFiltre.add(taxiName);
    
                        listeTaxi.push({
                            name: taxiName,
                            type: taxiType,
                            prix,
                            monnaie_symbol: taxiMonnaieSymbole,
                            /*
                            schedule_start : data['Schedule']['start'],
                            schedule_arrets : data['Rate']['stop'],
                            rate_id : data['Rate']['id'],
                            schedule_id: data['Schedule']['id'],
                            */
                            company_id : data[variables.CompanyPlaneDestination]['id'],
                            from_town_id: data[variables.CompanyPlaneDestination]['town_id'],
                            to_town_id: "0",
                            currency_id: data['Currency']['id'],
                            currency_iso: data['Currency']['iso'],

                            destination_id: data[variables.CompanyPlaneDestination]['id'],
                            
                            
                        });

                        
                        
                        
                    }
    
                    console.log({listeTaxi, taxiFiltre});
    
                    
                    
                    initFiltreTaxiCompagnie();
    
                    
                    refreshResultatTaxi();
                    
                }, 
                spinner: true,
                
                
            });
        }

        
    }

    


    

    let btnResetFiltreTaxiCompagnie = document.querySelector('.reset-filtre-compagnie');
    if(btnResetFiltreTaxiCompagnie !=null){
        btnResetFiltreTaxiCompagnie.addEventListener('click', (event)=>{
            initFiltreTaxiCompagnie();
            refreshResultatTaxi();
        })

    }

    let search = searchTaxi;


})(jQuery);

