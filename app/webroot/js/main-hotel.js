

var variables;

if(variables == null){
    variables = {
        service: "hotel",
        type: "hotel",
        fa: "fa-hotel",
        search: "searchhotel",
        Plane: "CompanyHotel",
        CompanyPlaneDestination: "HotelRoom",
    }
}


var setSelectedFilter = ({set, target})=>{}


var listeHotel = [];
var hotelFiltre = new Set();

var hotelFiltreSelected = new Set();

let reservationModalBtn = document.querySelector('.reservationModalBtn');
let reservationModal = document.querySelector("#reservationModal");

let reservationHotelCarousel = document.querySelector("#images-hotel");
let radiosTypeChambre = document.querySelector("#radios-type-chambre");
let roomCountElt = reservationModal?.querySelector('.container-room-count .display');
let nightCountElt = reservationModal?.querySelector('.container-night-count .display');

let eltPlaceNbrTotal = reservationModal.querySelector('.place-nbr-total');
let eltDatePicker = reservationModal.querySelector('#datepicker');

var hotelReservation = null;

var mapTypeBillet = {};
var factureList = [];
var reservationTotal = 0;

function refreshPricesOnFacture(hotel){
    mapTypeBillet = {};

    var classe_billet;
    var prix;
    reservationModal?.querySelectorAll(".container-classe input.radio-room")?.forEach((elt)=>{
        if(elt?.checked == true){
            idxRoom = elt.value;//getAttribute('idxRoom');
            let room = hotel?.listeChambreHotel[idxRoom];
            if(room !=null){
                prix = room?.prix??0;
                classe_billet = `${prix}${hotel.monnaie_symbol??""}`;
                name_classe_billet = room?.name??"";
                mapTypeBillet.niveau_classe = {titre: classe_billet, name:name_classe_billet};
                
            }
            
            
        }
    });

    console.log('classe_billet', classe_billet);
    console.log('hotel', hotel);
    console.log('prix', prix);

    /*
    let adultElt = reservationModal.querySelector('.container-adult-price');
    if(!isNaN(prixAdult)){
        adultElt?.classList.remove('d-none');
        let priceElt = adultElt?.querySelector('.adult-price');
        if(priceElt !=null) priceElt.textContent = `${prixAdult}${hotel?.monnaie_symbol}`;
    }else adultElt?.classList.add('d-none');

    let kidElt = reservationModal.querySelector('.container-kid-price');
    if(!isNaN(prixEnfant)){
        kidElt?.classList.remove('d-none');
        let priceElt = kidElt?.querySelector('.kid-price');
        if(priceElt !=null) priceElt.textContent = `${prixEnfant}${hotel?.monnaie_symbol}`;
    }else kidElt?.classList.add('d-none');

    let babyElt = reservationModal.querySelector('.container-baby-price');
    if(!isNaN(prixBebe)){
        babyElt?.classList.remove('d-none');
        let priceElt = babyElt?.querySelector('.baby-price');
        if(priceElt !=null) priceElt.textContent = `${prixBebe}${hotel?.monnaie_symbol}`;
    }else babyElt?.classList.add('d-none');
    */
}


function refreshTotalOnFacture(){
    factureList = [];

    
    console.log("ici refreshTotalOnFacture");

    let roomCount = parseInt(roomCountElt?.value??0);
    let nightCount = parseInt(nightCountElt?.value??0);

    //console.log({roomCountElt, nightCountElt, roomCount, nightCount});

    let idxRoom = hotelReservation?.idxRoom;
    let room = hotelReservation?.listeChambreHotel[idxRoom];
    
    let prix = parseFloat(room?.prix);

    reservationTotal = prix*roomCount*nightCount;

    factureList.push({
        titre:"Chambre",
        name:"all",
        prix:`${prix}`,
        quantite:`${roomCount}`,
        tours:`${nightCount}`,
    });

    

    let totalElt = document.querySelector('.container-total .total');
    if(totalElt !=null){
        totalElt.textContent = `${reservationTotal}${hotelReservation?.monnaie_symbol??""}`;
    }


}

function redresserNbrPlaceChoisi(){
    var nbrActu = parseInt(roomCountElt?.value??0);
        
    let nbrPlaceRestant = parseInt(eltPlaceNbrTotal?.textContent);
    
    if(nbrActu<0){roomCountElt.value = 0}
    else if(nbrActu>nbrPlaceRestant){roomCountElt.value = nbrPlaceRestant}
    
}

function refreshNbrPlaceRestantOnHtml(nbr){
    eltPlaceNbrTotal.textContent = nbr;
    redresserNbrPlaceChoisi();
}

function refreshNbrPlaceVendues(){
    let date =  eltDatePicker.value;
    let hotel = hotelReservation;

    let idxRoom = hotelReservation?.idxRoom??0;
    let room = hotel?.listeChambreHotel[idxRoom];
    
    if(date.length>0){
        if(room.nbr_place_vendues[date] !=null){
            let nbrPlaceVendues = room.nbr_place_vendues[date];
            refreshNbrPlaceRestantOnHtml(room.nbr_place - nbrPlaceVendues);
        }else{
            myFetch({
                url: `${window.location.origin}/m/hotel_nbr_place_vendues`, 
                json: {
                    departure_date: date, 
                    company_destination_id: room.destination_id,
                },
                thenFct: (res)=>{
                    console.log({res_date:res});
                    if(res?.success==true){
                        room.nbr_place_vendues[date] = parseInt(res?.data?.nbr_place_vendues);
    
                    }else{
                        room.nbr_place_vendues[date] = 0;
                    }
    
                    let nbrPlaceVendues = room.nbr_place_vendues[date];
                    refreshNbrPlaceRestantOnHtml(room.nbr_place - nbrPlaceVendues); 
                    
                    
                }, 
                spinner: true,
                
                
            });
        }
    }else{
        refreshNbrPlaceRestantOnHtml(room.nbr_place);
    }

    
    
}

var eltDatePickerFocusOut = false;
eltDatePicker?.addEventListener('focusout', (e)=>{
    eltDatePickerFocusOut = true;
});

eltDatePicker?.addEventListener('focusin', (e)=>{
    console.log('eltDatePicker-focusin')
    if(eltDatePickerFocusOut) refreshNbrPlaceVendues(e.target);
    eltDatePickerFocusOut = false;
});

reservationModal?.querySelectorAll('.container-allee-retour-radio input')?.forEach((item)=>{
    item.addEventListener('click', (e)=>{
        console.log(e.target.checked, e.target);
        if(e.target.id == "alle-simple-radio"){
            reservationModal?.querySelector('.container-datepicker-retour')?.classList?.add('d-none');
        }else if(e.target.id == "alle-retour-radio"){
            reservationModal?.querySelector('.container-datepicker-retour')?.classList?.remove('d-none');
        }

        /*
        refreshPricesOnFacture(hotelReservation);
        refreshTotalOnFacture();
        */
    });
});

reservationModal?.querySelectorAll('.container-classe input')?.forEach((item)=>{
    item.addEventListener('click', (e)=>{
        refreshPricesOnFacture(hotelReservation);
        refreshTotalOnFacture();
    });
});

reservationModal?.querySelectorAll('.container-room-count .display, .container-night-count .display')?.forEach((item)=>{
    item?.addEventListener('click', (e)=>{
        redresserNbrPlaceChoisi();
        refreshTotalOnFacture();
        
    });
});

function reservationHotel(idxHotel){
    let hotel = listeHotel[idxHotel];
    hotel.idxRoom = 0;
    hotelReservation = hotel;

    let room = hotel?.listeChambreHotel[hotel.idxRoom];

    if(hotel !=null && reservationModalBtn !=null && reservationModal !=null){

        let operatorName = reservationModal.querySelector('.operator-name');
        if(operatorName !=null){
            operatorName.textContent = hotel.name;
        }


        refreshNbrPlaceVendues();
        
        
        
        refreshReservationRoom();
        refreshRadioTypeChambre();
        
        refreshPricesOnFacture(hotel);

        initReservationCount();

        //refreshTotalOnFacture();
        
        reservationModalBtn.click();
    }
}

function initReservationCount(){
    if(roomCountElt)roomCountElt.value = 1;
    if(nightCountElt)nightCountElt.value = 1;
    refreshTotalOnFacture();

};

function divHotelImage({src}){
    
    return `<div class="item">
    <div class="w-100 d-flex justify-content-center">
        <img class="rounded border-white m-2" style=" aspect-ratio: 16/9;"
            src="${src}" />
    </div>
  </div>`;
  
/*
  return `<div class="item">
  <div class="w-100 d-flex justify-content-center">
      <img class="rounded border-white m-2" style=" aspect-ratio: 16/9;"
          src="../img/town/2/7854348339f9607f8f9839802d59a362.jpg" />
  </div>
</div>`;
*/
}

function refreshRadioTypeChambre(){
    
    let idxRoom = hotelReservation?.idxRoom??0;
    let listeRoom = hotelReservation?.listeChambreHotel;
    

    var strRes = "";
    listeRoom.forEach((room, idx)=>{
        //let room = listeRoom[idx];

        strRes += `<input type="radio" class="btn-check mx-2 radio-room" name="options-classe" id="radio-${idx}" value="${idx}" autocomplete="off" ${idx==idxRoom?"checked":""}>
        <label class="btn btn-outline-secondary mx-2" for="radio-${idx}"><span class="fa fa-money"></span><span class="ms-1">${room?.prix}${hotelReservation?.monnaie_symbol}</span></label>`;
    });

    radiosTypeChambre.innerHTML = strRes;

    radiosTypeChambre.querySelectorAll("input.radio-room").forEach((radioBtn, key)=>{
        radioBtn.addEventListener('change', (e)=>{
            let idxActu = e.target.value;
            if(hotelReservation !=null)hotelReservation.idxRoom = parseInt(idxActu);
            refreshReservationRoom();
            refreshNbrPlaceVendues(eltDatePicker);
            
            console.log({idxRoom: hotelReservation.idxRoom});
        });
    });

}

/*
function selectRadioTypeChambre({idx}){
    let lastIdx = hotelReservation?.idxRoom;
    if(hotelReservation !=null) hotelReservation.idxRoom = idx;
    
    
    let lastRadio = radiosTypeChambre.querySelector(`radio-${lastIdx}`);
    if(lastRadio !=null) lastRadio.checked = false;

    let actuRadio = radiosTypeChambre.querySelector(`radio-${idx}`);
    if(actuRadio !=null) actuRadio.checked = true;

    refreshReservationRoom();

}
*/

function refreshReservationRoom(){
    
    let itemSize = $(".hotel-carousel .item").length;
    for(var i=0; i<itemSize; i++){
        $(".hotel-carousel").trigger('remove.owl.carousel', [0]);
    }
    
    let idxRoom = hotelReservation?.idxRoom;
    let room = hotelReservation?.listeChambreHotel[idxRoom];
    
    //let nomChambre = room?.name;
    //let prix = room?.prix;

    let urlsMedia = room?.urlsMedia??[];

    if(urlsMedia.length>0) reservationHotelCarousel.classList.remove("d-none");
    else reservationHotelCarousel.classList.add("d-none");
    
    urlsMedia?.forEach((value, idx)=>{
        $(".hotel-carousel").owlCarousel('add', divHotelImage({src: value})).owlCarousel('update');
        //strListImages += divHotelImage({src: value});
    });

    refreshTotalOnFacture();
    
}


document.querySelector(`.btn-acheter-billet-${variables.type}`)?.addEventListener('click', (event)=>{
    if(user?.connected??false){
        var msgError = "";
        let total = `${reservationTotal}`;
        if(total=="0") msgError +="<li>Vous devez sélectionner au moins une nuit dans une chambre</li>";
        
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

        let hotel = hotelReservation;

        let idxRoom = hotel?.idxRoom;
        let room = hotel?.listeChambreHotel[idxRoom];

        let nbr_place = roomCountElt?.value;

        if(msgError.length==0){
            let data = {
                service : variables.service,
                auth_id: user?.id,
                user_id: user?.id,
                total: total,
                rate_id: "",
                departure_date: departure_date,
                schedule_id: "",
                place: JSON.stringify({}), //placesHotel
                nbr_place,
            
                company_name: hotel.name??"",
                category_company_id: hotel.company_id,
                company_destination_id: room?.destination_id,
                from_country_mobile_code: "",
                from_town_id: hotel.town_id,
                town_id: hotel.town_id,
                to_country_mobile_code: "",
                to_town_id: hotel.town_id,
                currency_id: hotel.currency_id,
                currency_iso: hotel.currency_iso??"",
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
        searchHotel({});
    });

   
    

    

    function refreshResultatHotel(){
        let listeHotelDom = document.querySelector('.liste-resultat');
        if(listeHotelDom !=null){
            var strResultat = "";
            
            console.log('hotelFiltreSelected',hotelFiltreSelected);

            for(let idxHotel in listeHotel){
                let hotel = listeHotel[idxHotel];
                var canDisplay = false;
                

                var canDisplayName = false;
                if(hotelFiltreSelected.has(hotel.name)) canDisplayName = true;

                canDisplay = canDisplayName;

                if(canDisplay){
                    strResultat+= `
<div class="col  d-block p-3  ">
    <div class="p-3 rounded border-white border-light  bg-primary btn btn-primary btn-simple" onclick="reservationHotel(${idxHotel})" >
        
    <div class="d-flex justify-content-center mb-2"><img src=${hotel.couverture} class="rounded mx-auto d-block" style="max-width: 100%; height: auto; " /></div>
    <div class="d-flex justify-content-center mb-2 pt-2"><h4 class="mb-2 text-white-grey"  style="overflow: hidden; text-overflow: ellipsis !important; white-space: nowrap !important;">${hotel.name}</h4></div>
        
        <div class="d-flex justify-content-center"><h6 class="mb-1 text-white-grey"  style="overflow: hidden; text-overflow: ellipsis !important; white-space: nowrap !important;">${hotel.piscine?"Avec Piscine":""}</h6></div>
        
        <div class="d-flex justify-content-center ${hotel.prix==null?'d-none ':''}"><div class="btn btn-white rounded-pill py-1 px-3 em1-1 m-2 border-grey">${hotel.prix} ${hotel.monnaie_symbol}</div></div>
    </div>
</div>
                    `;
                    //<div class="d-flex justify-content-center mb-2"><h1 class="mb-2 text-white-grey  "><i class="fa ${variables.fa} rounded-circle p-3 border-white border-light"></i></h1></div>
                }

                
            }

            
            listeHotelDom.innerHTML = strResultat;
        }
        
    }


    
    function initFiltreHotelCompagnie(){
        //repartition des noms de compagnie dans le DOM
        let filtreCompagnie = document.querySelector('.filtre-compagnie');
        console.log('filtreCompagnie', filtreCompagnie);
        if(filtreCompagnie !=null){
            var strFiltre = "";
            for(let titre of hotelFiltre){
                //`<div class="align-self-center btn btn-white rounded-pill py-1 px-2 em1-1 m-2 border-light border-grey">${titre}</div>`;
                strFiltre += `<div class="align-self-center btn btn-primary rounded-pill py-1 px-2 em1-1 m-2 border-light border-white selected">${titre}</div>`;
            }
            filtreCompagnie.innerHTML = strFiltre;
            hotelFiltre.forEach((elt)=>{
                hotelFiltreSelected.add(elt);
            }) 

            for(const child of filtreCompagnie.children){
                child.addEventListener('click', (event)=>{
                    let target = event.target;
                    setSelectedFilter({
                        set: hotelFiltreSelected,
                        target
                    });

                    refreshResultatHotel();
                });
            }

        }
    }




    function searchHotel({
		form_date = '07/03/2024',
    }){
        let from_town_id = towns?.source?.value;
	    let to_town_id = towns?.destination?.value;

        console.log("searchhotel : from "+from_town_id+" to "+to_town_id);

        if(from_town_id != null && to_town_id != null){
            
            myFetch({
                url: `${window.location.origin}/m/${variables.search}`, 
                json: {
                    town_id: from_town_id,
                    form_date,
                    from_web: true,
                },
                thenFct: (res)=>{
                    console.log(res);
    
                    listeHotel = [];
                    hotelFiltre = new Set();
                    
                    for(let i in res.data){
                        let data = res.data[i];
                        
                        let hotelName = data[variables.Plane]['name'];
                        
                        let hotelMonnaieSymbole = data['Currency']['symbol'];
                        let piscine = data[variables.Plane]?.piscine??false;

                        let listeRoom = data[variables.CompanyPlaneDestination]??[];
                        let listeChambreHotel = [];
                        var couverture = null;

                        listeRoom.forEach((room)=>{
                            


                            
                            //String monnaie = room?["Currency"]?["symbol"]??"";
                            let nomChambre = room?.name??"";
                            let strPrix = room?.amount??"0";
                            let prix = parseFloat(strPrix);
                            
                            let listeMedia = room?.Media??[];
                            
                            let urlsMedia = listeMedia.map((value, index)=>`${window.location.origin}${listeMedia[index]?.file??""}`);
                            
                            if(couverture ==null) couverture =  urlsMedia[0];
                    
                            let chambreHotel = {
                                name: nomChambre,
                                urlsMedia,
                                prix,
                                destination_id: room?.id,
                                nbr_place: room?.nbr_place,
                                nbr_place_vendues: {},
                            };
                            listeChambreHotel.push(chambreHotel);
                          });
                        
                        /*
                        let hotelPrixMap = {
                            economic: {
                                oneway: {}, 
                                roundtrip: {}
                            },
                            firstclass: {
                                oneway: {}, 
                                roundtrip: {}
                            },
                            
                        }
                        
                        function from_amounts_to_hotel_prix(amounts, pricePlace){
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
                        

                        from_amounts_to_hotel_prix(amounts_economic_oneway, hotelPrixMap.economic.oneway);
                        from_amounts_to_hotel_prix(amounts_economic_roundtrip, hotelPrixMap.economic.roundtrip);
                        from_amounts_to_hotel_prix(amounts_firstclass_oneway, hotelPrixMap.firstclass.oneway);
                        from_amounts_to_hotel_prix(amounts_firstclass_roundtrip, hotelPrixMap.firstclass.roundtrip);
*/
                        
    
                        /*
                        let lstObjTags = data['Destination']['Car']['Tag'];
                        var tags = new Set();
                        for(let i in lstObjTags){
                            let tagName = lstObjTags[i]['name'];
                            tags.add(tagName);
                            confortFiltre.add(tagName);
                        }
                        */
                        hotelFiltre.add(hotelName);
    
                        listeHotel.push({
                            name: hotelName,
                            piscine,
                            //type: hotelType,
                            prix: listeChambreHotel[0]?.prix??0,
                            couverture: couverture??"",
                            //prixMap: hotelPrixMap,
                            monnaie_symbol: hotelMonnaieSymbole,
                            /*
                            schedule_start : data['Schedule']['start'],
                            schedule_arrets : data['Rate']['stop'],
                            rate_id : data['Rate']['id'],
                            schedule_id: data['Schedule']['id'],
                            */
                            company_id : data[variables.Plane]['id'],
                            town_id: data[variables.Plane]['town_id'],
                            currency_id: data['Currency']['id'],
                            currency_iso: data['Currency']['iso'],
                            listeChambreHotel,
                            
                            
                        });

                        
                        
                        
                    }
    
                    console.log({listeHotel, hotelFiltre});
    
                    
                    
                    initFiltreHotelCompagnie();
    
                    
    
              
                    refreshResultatHotel();
                    
                }, 
                spinner: true,
                
                
            });
        }

        
    }

    


    

    let btnResetFiltreHotelCompagnie = document.querySelector('.reset-filtre-compagnie');
    if(btnResetFiltreHotelCompagnie !=null){
        btnResetFiltreHotelCompagnie.addEventListener('click', (event)=>{
            initFiltreHotelCompagnie();
            refreshResultatHotel();
        })

    }

    let search = searchHotel;


})(jQuery);

