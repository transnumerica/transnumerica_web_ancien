



    /*
console.log($cart);
console.log(`counter : ${$counter}`);
console.log(`total : ${$total}`);
console.log(`seatMap : ${$seatMap}`);
console.log(`kvSeats : ${kvSeats}`);
    */
var placesBus = {};
var placesPrixBus = {};
var mapFactureBus = {};
var timeWrapper = "";

function setSeatChart({price: {first,second,economic}, monnaie_symbol}){
    var firstSeatLabel = 1;
    timeWrapper = `time-${Date.now()}`;
    placesBus = {};
    mapFactureBus = {};
    placesPrixBus = {};

    $('.bus-seats').html(`
    <div class="wrapper ${timeWrapper}">
        <div class="container text-center pt-3">
        
            <div id="seat-map" class="">
                <div class="front-indicator">L'avant</div>
            </div>
            <div class="booking-details">
                <h5 class="mt-2">Réservation (<span id="counter">0</span>)</h5>
                <ul id="selected-seats" class="text-start" >
                </ul>
                Total : <div class="d-flex justify-content-center" ><h4 class="text-primary rounded-pill border-primary border-light py-2 px-4"><span id="total">0</span> ${monnaie_symbol}</h4></div>
                
                <div id="legend" class="container text-start" ></div>
            </div>
        </div>
    </div>
    `);

    var $cart = $(`.bus-seats .wrapper.${timeWrapper} #selected-seats`),
        $counter = $(`.bus-seats .wrapper.${timeWrapper} #counter`),
        $total = $(`.bus-seats .wrapper.${timeWrapper} #total`),
        $seatMap = $(`.bus-seats .wrapper.${timeWrapper} #seat-map`),
        kvSeats = $seatMap.seatCharts({
/*
        map: [
            'ff_ff',
            'ss_ss',
            'ss_ee',
            'ee_ee',
            'ee___',
            'ee_ee',
            'ee_ee',
            'ee_ee',
            'eeeee',
        ],
        */
        map: [
            'ee_ee',
            'ee_ee',
            'ee_ee',
            'ee_ee',
            'ee___',
            'ee_ee',
            'ee_ee',
            'ee_ee',
            'eeeee',
        ],
        seats: {
            f: {
                price   : first,
                classes : 'first-class', //your custom CSS class
                category: 'Première Classe',
                name: 'prime'
            },
            s: {
                price : second,
                classes : 'second-class',
                category: 'Classe Business',
                name: 'business'
            },
            e: {
                price   : economic,
                classes : 'economy-class', //your custom CSS class
                category: 'Classe Economique',
                name: 'economic'
            }					
        
        },
        naming : {
            top : false,
            getLabel : function (character, row, column) {
                return firstSeatLabel++;
            },
        },
        legend : {
            node : $(`.bus-seats .wrapper.${timeWrapper} #legend`),
            items : [
                [ 'f', 'available',   'Première Classe : '+first+' '+monnaie_symbol ],
                [ 's', 'available',   'Classe Business : '+second+' '+monnaie_symbol ],
                [ 'e', 'available',   'Classe Economique : '+economic+' '+monnaie_symbol ],
                [ 'f', 'unavailable', 'Déjà Réservé']
            ]					
        },
        click: function () {
                let titre = this.data().category;
                let prix = this.data().price;
                let numPlace = this.settings.label;

                let name = this.data().name;

            if (this.status() == 'available') {
                
                //placesBus[this.settings.label] = this.data().price;
                placesPrixBus[numPlace] = prix;

                let places = placesBus[titre]??(new Set());
                
                places.add(numPlace);
                console.log(`places.add(${numPlace}) - places = `, places);
                placesBus[titre] = places;
                //console.log(`placesBus[${titre}] - placesBus = `, placesBus);
                mapFactureBus[titre] = {
                    titre,
                    name,//name : titre,
                    places : JSON.stringify(Array.from(places)),
                    prix: `${prix}`,
                    quantite : `${places.size}`,
                    tours: "1"
                  };

                console.log(`placesPrixBus[${titre}] - placesPrixBus = `, placesPrixBus);
                console.log(`mapFactureBus[${titre}] - mapFactureBus = `, mapFactureBus);

                //let's create a new <li> which we'll add to the cart items
                $('<li>'+titre+'(#'+numPlace+') : <b>'+prix+' '+monnaie_symbol+'</b> <a href="#" class="cancel-cart-item">[X]</a></li>')
                    .attr('id', 'cart-item-'+this.settings.id)
                    .data('seatId', this.settings.id)
                    .appendTo($cart);
                
                /*
                    * Lets update the counter and total
                    *
                    * .find function will not find the current seat, because it will change its stauts only after return
                    * 'selected'. This is why we have to add 1 to the length and the current seat price to the total.
                    */
                $counter.text(kvSeats.find('selected').length+1);
                $total.text(recalculateTotal(kvSeats)+this.data().price);
                
                return 'selected';
            } else if (this.status() == 'selected') {
                
                delete placesPrixBus[numPlace];

                let places = placesBus[titre];
                if(places !=null){
                    places?.delete(numPlace);
                    placesBus[titre] = places;

                    if(places.size>0){
                        if(mapFactureBus[titre] !=null) mapFactureBus[titre]['places'] = JSON.stringify(Array.from(places));
                    }else{
                        delete mapFactureBus[titre];
                    }
                }
                
                console.log(`placesPrixBus[${titre}] - placesPrixBus = `, placesPrixBus);
                
                //update the counter
                $counter.text(kvSeats.find('selected').length-1);
                //and total
                $total.text(recalculateTotal(kvSeats)-this.data().price);
            
                //remove the item from our cart
                $(`.bus-seats .wrapper.${timeWrapper} #cart-item-`+this.settings.id).remove();
            
                //seat has been vacated
                return 'available';
            } else if (this.status() == 'unavailable') {
                //seat has been already booked
                return 'unavailable';
            } else {
                return this.style();
            }
        }
    });


    //this will handle "[cancel]" link clicks
    $(`.bus-seats .wrapper.${timeWrapper} #selected-seats`).on('click', '.cancel-cart-item', function () {
        //let's just trigger Click event on the appropriate seat, so we don't have to repeat the logic here
        kvSeats.get($(this).parents('li:first').data('seatId')).click();
    });

    //let's pretend some seats have already been booked
    //kvSeats.get(['1_2', '4_1', '7_1', '7_2']).status('unavailable');

}




$(document).ready(function() {
   
    

});

function recalculateTotal(kvSeats) {
    var total = 0;

    //basically find every selected seat and sum its price
    kvSeats.find('selected').each(function () {
        total += this.data().price;
    });
    
    return total;
}
		
		