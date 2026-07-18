function myFetch({url, method= "POST", json = {}, thenFct = (res)=>{}, 
    catchFct = (res)=>{}}){
        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: method,
            body: JSON.stringify(json),
        })
        .then(response => {
            response.json().then(thenFct);
        })//then(thenFct)
        .catch(catchFct)
}

var listeBus = [];
var confortFiltre = new Set();
var busFiltre = new Set();

var confortFiltreSelected = new Set();
var busFiltreSelected = new Set();

let reservationBusModalBtn = document.querySelector('.reservationBusModalBtn');
let reservationBusModal = document.querySelector("#reservationBusModal");

function reservationBus(idxBus){
    let bus = listeBus[idxBus];

    if(bus !=null && reservationBusModalBtn !=null && reservationBusModal !=null){

        ('#reservationBusModal .datepicker').datepicker();

        reservationBusModalBtn.click();
    }
}

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
        autoplay: true,
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
        document.querySelector('.nextYoutube').click();
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
        <div class="d-flex justify-content-center mb-2"><h4 class="mb-2 text-white-grey ">${bus.name}</h4></div>
        <div class="d-flex justify-content-center mb-2"><h1 class="mb-2 text-white-grey  "><i class="fa fa-bus rounded-circle p-3 border-white border-light"></i></h1></div>
        <div class="d-flex justify-content-center"><h6 class="mb-1 text-white-grey">${bus.type}</h6></div>
        
        <div class="d-flex justify-content-center"><div class="btn btn-white rounded-pill py-1 px-3 em1-1 m-2 border-grey">${bus.prix} ${bus.monnaie_symbol}</div></div>
    </div>
</div>
                    `;
                }

                
            }

            listeBusDom.innerHTML = strResultat;
        }
        
    }


    function setSelectedFilter({set, target}){
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
        from_town_id = 1,
	    to_town_id = '2',
		form_date = '07/03/2024',
    }){
        myFetch({
            url: `${window.location.origin}/m/searchbus`, 
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
                        tags
                        
                    });
                    
                    
                }

                console.log({listeBus, confortFiltre, busFiltre});

                initFiltreBusConfort();
                
                initFiltreBusCompagnie();

                

          
                refreshResultatBus();
                
            }, 
            
            
        });
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

})(jQuery);

