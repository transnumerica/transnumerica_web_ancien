var setSelectedFilter = ({set, target})=>{}

var listeBus = [];

let reservationModalBtn = document.querySelector('.reservationModalBtn');
let reservationModal = document.querySelector("#reservationModal");
let checkDate = document.querySelector('#check_date');
let checkClient = document.querySelector('#check_client');

let eltDateMin = document.querySelector("#reservationModal #datepicker-min");
let eltDateMax = document.querySelector("#reservationModal #datepicker-max");
    
let inputSearchClient = document.querySelector("#reservationModal #input_client");
    

var busReservation = null;

function check_date_exec(target){
    let checked = target?.checked == true;

    
    if(checked){
        if(eltDateMin != null) eltDateMin.disabled = false;
        if(eltDateMax != null) eltDateMax.disabled = false;
    }else{
        if(eltDateMin != null) eltDateMin.disabled = true;
        if(eltDateMax != null) eltDateMax.disabled = true;
    }
}

function check_client_exec(target){
    let checked = target?.checked == true;

    if(checked){
        if(inputSearchClient != null) inputSearchClient.disabled = false;
    }else{
        if(inputSearchClient != null) inputSearchClient.disabled = true;
    }
}


function reservationBus(idxBus){
    
    let bus = listeBus[idxBus];
    busReservation = bus;

    ///A EFFACER
    ///console.log('objet : ', bus);

    if(bus !=null && reservationModalBtn !=null && reservationModal !=null){
        console.log(`in reservationBus(${idxBus})`);

        let operatorName = reservationModal.querySelector('.operator-name');
        if(operatorName !=null){
            operatorName.textContent = bus.name;
        }
        
        let economic = parseFloat(bus.prix);
        
        setSeatChart({price:{first: economic*3, second: economic*1.5, economic}, monnaie_symbol: bus.monnaie_symbol});

        check_date_exec(checkDate);
        check_client_exec(checkClient);

        reservationModalBtn.click();
    }
}



check_date_exec(checkDate);
check_client_exec(checkClient);

checkDate?.addEventListener('change', (event)=>{
    let target = event?.target;
    check_date_exec(target);
});

checkClient?.addEventListener('change', (event)=>{
    let target = event?.target;
    check_client_exec(target);
});




document.querySelector('.btn-acheter-billet-bus')?.addEventListener('click', (event)=>{
    if(user?.connected??false){
        var msgError = "";

        if(msgError.length==0){
            let data = {
                check_date : checkDate?.checked == true,
                check_client : checkClient?.checked == true,
                
                date_min : eltDateMin?.value??"",
                date_max : eltDateMax?.value??"",
                search_client : inputSearchClient?.value??"",
                marchand_user_id : user?.id,
                destination_id: busReservation.destination_id,
                categorie,
            };

            console.log('avant : ', data);

            myFetchSalesMarchand({data});
            


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

let fa_service = (()=>{
    switch(categorie){
        case 'bus': return 'fa-bus';
        case 'vol': return 'fa-plane';
        case 'hotel': return 'fa-hotel';
        case 'train': return 'fa-train';
        case 'taxi': return 'fa-taxi';
    }
})();


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
                var canDisplay = true;
                
                if(canDisplay){
                    strResultat+= `
<div class="col  d-block p-3  ">
    <div class="p-3 rounded border-white border-light  bg-black btn btn-primary btn-simple" onclick="reservationBus(${idxBus})" >
        <div class="d-flex justify-content-center mb-2"><h4 class="mb-2 text-white-grey"  style="overflow: hidden; text-overflow: ellipsis !important; white-space: nowrap !important;">${bus.name}</h4></div>
        <div class="d-flex justify-content-center mb-2"><h1 class="mb-2 text-white-grey  "><i class="fa ${fa_service} rounded-circle p-3 border-white border-light"></i></h1></div>
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
        /*
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
        */
    }
    
    function initFiltreBusCompagnie(){
        //repartition des noms de compagnie dans le DOM
        /*
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
        */
    }




    function searchBus({
		form_date = '07/03/2024',
    }){
        let from_town_id = towns?.source?.value;
	    let to_town_id = towns?.destination?.value;
        let marchand_user_id = user?.id??null;

        console.log("searchbus : from "+from_town_id+" to "+to_town_id);

        if(from_town_id != null && to_town_id != null){
            
            myFetch({
                url: ""+window.location.origin+"/m/search_destination_marchand", 
                json: {
                    from_town_id,
                    to_town_id,
                    marchand_user_id,
                    categorie
                },
                thenFct: (res)=>{
                    console.log(res);
    
                    listeBus = [];
                    //confortFiltre = new Set();

                    let fct_add_elt = (data)=>{};

                    switch(categorie){
                        case 'bus':
                            fct_add_elt = (data)=>{
                                listeBus.push({
                                    name: data['CompanyDestination']['name'],
                                    type: data['Car']['name'],
                                    prix: data['CompanyDestination']['amount'],
                                    monnaie_symbol: data['Currency']['symbol'],
                                    destination_id: data['CompanyDestination']['id'],
                                    
                                });
                            }
                            break;
                        case 'vol':
                            fct_add_elt = (data)=>{
                                listeBus.push({
                                    name: data['Plane']['name'],
                                    type: data['CompanyPlaneDestination']['name'],
                                    prix: "",//data['CompanyPlaneDestination']['amount'],
                                    monnaie_symbol: data['Currency']['symbol'],
                                    destination_id: data['CompanyPlaneDestination']['id'],
                                    
                                });
                            }
                            break;
                        case 'hotel':
                            fct_add_elt = (data)=>{
                                let hotelRooms = data['HotelRoom']??[];

                                hotelRooms.forEach(room => {
                                    listeBus.push({
                                        name: data['CompanyHotel']['name'],
                                        type: room['name'],
                                        prix: room['amount'],//data['CompanyPlaneDestination']['amount'],
                                        monnaie_symbol: data['Currency']['symbol'],//data['Currency']['symbol'],
                                        ///destination_id: data['HotelRoom']['id'],
                                        destination_id: room['id'],
                                        
                                    });
                                });

                                
                            }
                            break;
                        case 'train':
                            fct_add_elt = (data)=>{
                                listeBus.push({
                                    name: data['Former']['name'],
                                    type: data['CompanyFormerDestination']['name'],
                                    prix: "-",//data['CompanyPlaneDestination']['amount'],
                                    monnaie_symbol: "-",//data['Currency']['symbol'],
                                    destination_id: data['CompanyFormerDestination']['id'],
                                    
                                });
                            }
                            break;
                        case 'taxi':
                            fct_add_elt = (data)=>{
                                let taxiCity = data['Town']['TaxiCity'][0];
                                let currency_symbol = taxiCity['Currency']['symbol'];
                                
                                let prixMin = taxiCity['prix_min'];
                                let prixMax = taxiCity['prix_max'];

                                listeBus.push({
                                    name: data['User']['Info']['fullname'],
                                    type: data['CompanyTaxi']['model_vehicule'],
                                    prix: `${prixMin} - ${prixMax}`,
                                    monnaie_symbol: currency_symbol,
                                    destination_id: data['CompanyTaxi']['id'],
                                    
                                });
                            }
                            break;
                            
                    }
                   
                    for(let i in res.data){
                        let data = res.data[i];
                        
                        fct_add_elt(data);
                        
                        
                    }
    
                    
                    initFiltreBusConfort();
                    
                    initFiltreBusCompagnie();
    
                    
    
              
                    refreshResultatBus();
                    
                }, 
                spinner: true,
                
                
            });
        }

        
    }

    

/*
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

    let search = searchBus;


})(jQuery);

