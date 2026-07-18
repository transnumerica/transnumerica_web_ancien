<?php 
    $Towns = isset($Towns)?$Towns:[];
    $STowns = isset($STowns)?$STowns:[];
    $keySchedule = isset($keySchedule)?$keySchedule:'';
    $Schedules = isset($Schedules)?$Schedules:[];

    $Tags = isset($Tags)?$Tags:[];
    $Companies = isset($Companies)?$Companies:[];
    
    $FromTown = isset($FromTown)?$FromTown:[];
    $ToTown = isset($ToTown)?$ToTown:[];
    $mT = isset($mT)?$mT:'';
    $endTravel = isset($endTravel)?$endTravel:'';

    $authUser = isset($authUser)?$authUser:[];


?>

<?php 
    $this->set('title_for_layout', Configure::read('Company.name').' - '.Op::translate(Configure::read('Company.slogan'), 'fr-'));

    $this->set('navHome', "active");

    $this->set('lieu', true);

    $this->set('pageTitle', count($Schedules).' Trajets détectés');


?>
      <section class="container">
        <div class="row">
          <div class="col mb-2">
            <?php
            echo $this->Form->create(false,  array('type' => 'file', 'id' => 'bookingBus', 'novalidate' => false, 'class'=> '', 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100')))));
            ?>
              <div class="form-row">
                <div class="col-md-6 col-lg form-group">
                  <?php
                      $ListTowns = Hash::combine($Towns, '{n}.Town.id', '{n}.Town.name');
                      echo $this->Form->input('Search.from_town_id', array('id' => 'busFrom', 'class' => 'form-control', 'required' => true, 'placeholder' => 'De', 'empty' => '(De)', 'options' => $STowns));
                  ?>
                  <!--input type="text" class="form-control" id="busFrom" required placeholder="From"-->

                  <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
                <div class="col-md-6 col-lg form-group">
                  <?php
                      $ListTowns = Hash::combine($Towns, '{n}.Town.id', '{n}.Town.name');
                      echo $this->Form->input('Search.to_town_id', array('id' => 'busTo', 'class' => 'form-control', 'required' => true, 'placeholder' => 'À', 'empty' => '(À)', 'options' => $STowns));
                  ?>
                  <!--input type="text" class="form-control" id="busTo" required placeholder="To"-->
                  <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
                <div class="col-md-4 col-lg form-group">
                  <?php
                    //$this->request->data['Search']['form_date'] = '01-01-2024';
                    echo $this->Form->input('Search.form_date', array('type' => 'text', 'id' => 'busDepart', 'class' => 'form-control', 'required' => true, 'placeholder' => 'Date de départ'));
                  ?>
                  <!--input id="busDepart" type="text" class="form-control" required placeholder="Depart Date"-->
                  <span class="icon-inside"><i class="far fa-calendar-alt"></i></span> </div>
                <div class="col-md-4 col-lg travellers-class form-group">

                  <?php
                      echo $this->Form->input('Search.seats', array('type' => 'text', 'id' => 'busTravellersClass', 'class' => 'travellers-class-input form-control', 'required' => true, 'placeholder' => 'Places', 'onkeypress' => 'return false;'));
                  ?>
                  <!--input type="text" id="busTravellersClass"  class="travellers-class-input form-control" name="bus-travellers-class" placeholder="Sièges" readonly required onkeypress="return false;"-->
                  <span class="icon-inside"><i class="fas fa-caret-down"></i></span>
                  <div class="travellers-dropdown">
                    <div class="row align-items-center mb-3">
                      <div class="col-sm-7">
                        <p class="mb-sm-0">Sièges</p>
                      </div>
                      <div class="col-sm-5">
                        <div class="qty input-group">
                          <div class="input-group-prepend">
                            <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#adult-travellers" data-toggle="spinner">-</button>
                          </div>
                          <input type="text" data-ride="spinner" id="adult-travellers" class="qty-spinner form-control" value="1" readonly>
                          <div class="input-group-append">
                            <button type="button" class="btn bg-light-4" data-value="increase" data-target="#adult-travellers" data-toggle="spinner">+</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <button class="btn btn-primary btn-block submit-done" type="button">Terminé</button>
                  </div>
                </div>
                <div class="col-md-4 col-lg form-group">
                  <?php echo $this->Form->button('Recherche', array('type' => 'submit', 'id' => 'UsernameForm', 'class' => 'btn btn-primary btn-block', 'name' => 'form', 'value' => 'searchbus')); ?>
                  <!--button class="btn btn-primary btn-block" type="submit">Recherche</button-->
                </div>
              </div>
            <?php
                echo $this->Form->end(); 
            ?>
          </div>
        </div>
        <div class="row">
        
        <!-- Side Panel
        ============================================= -->
          <aside class="col-md-3">
            <div class="bg-white shadow-md rounded p-3">
              <h3 class="text-5">Filtre</h3>
			  <hr class="mx-n3">
              <div class="accordion accordion-alternate style-2 mt-n3" id="toggleAlternate">
                <div class="card">
                  <div class="card-header" id="departure">
                    <h5 class="mb-0"> <a href="#" class="collapse" data-toggle="collapse" data-target="#toggleDeparture" aria-expanded="true" aria-controls="togglePrice">Heure de départ</a> </h5>
                  </div>
                  <div id="toggleDeparture" class="collapse show" aria-labelledby="departure">
                    <div class="card-body">
                      <p><span class="slider-time-departure">00:00</span> - <span class="slider-time-departure">23:59</span></p>
                      <div id="slider-range-departure"></div>
                    </div>
                  </div>
                </div>
                <!--div class="card">
                  <div class="card-header" id="price">
                    <h5 class="mb-0"> <a href="#" class="collapse" data-toggle="collapse" data-target="#togglePrice" aria-expanded="true" aria-controls="togglePrice">Prix</a> </h5>
                  </div>
                  <div id="togglePrice" class="collapse show" aria-labelledby="price">
                    <div class="card-body">
                      <p>
                        <input id="amount" type="text" readonly class="form-control border-0 bg-transparent p-0">
                      </p>
                      <div id="slider-range"></div>
                    </div>
                  </div>
                </div-->
                <div class="card">
                  <div class="card-header" id="busType">
                    <h5 class="mb-0"> <a href="#" class="collapse" data-toggle="collapse" data-target="#togglebusType" aria-expanded="true" aria-controls="togglebusType">Type d'Autobus</a> </h5>
                  </div>
                  <div id="togglebusType" class="collapse show" aria-labelledby="busType">
                    <div class="card-body">
                      

                      <?php

                      foreach ($Tags as $key => $Tag) {

                      ?>
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" id="sleeper" name="busType" class="custom-control-input">
                        <label class="custom-control-label" for="sleeper"><?php echo $Tag['Tag']['name'] ?></label>
                      </div>
                      <?php

                      }

                      ?>

                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header" id="busOperators">
                    <h5 class="mb-0"> <a href="#" class="collapse" data-toggle="collapse" data-target="#togglebusOperators" aria-expanded="true" aria-controls="togglebusOperators">Compagnie d'Autobus</a> </h5>
                  </div>
                  <div id="togglebusOperators" class="collapse show" aria-labelledby="busOperators">
                    <div class="card-body">
                      

                      <?php

                      foreach ($Companies as $keyCompany => $Company) {

                      ?>

                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" id="compagny<?php echo $keyCompany ?>" name="busOperators" class="custom-control-input">
                        <label class="custom-control-label" for="compagny<?php echo $keyCompany ?>"><?php echo $Company['Company']['name'] ?></label>
                      </div>

                      <?php

                      }

                      ?>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </aside><!-- Side Panel end -->
          
          <div class="col-md-9 mt-4 mt-md-0">
            <div class="bg-white shadow-md rounded py-4">
              <div class="mx-3 mb-3 text-center">
                <?php
                $FromTown = @$Towns[$this->request->data['Search']['from_town_id']];
                $ToTown = @$Towns[$this->request->data['Search']['to_town_id']];
                ?>
                <h2 class="text-6 mb-4"><?php echo @$FromTown['Town']['name'] ?> <small class="mx-2">à</small><?php echo @$ToTown['Town']['name'] ?></h2>
              </div>
              <div class="text-1 bg-light-3 border border-right-0 border-left-0 py-2 px-3">
                <div class="row">
                  <div class="col col-sm-3"><span class="d-none d-sm-block">Opérateurs</span></div>
                  <div class="col col-sm-2 text-center">Départ</div>
                  <div class="col-sm-2 text-center d-none d-sm-block">Durée</div>
                  <div class="col col-sm-2 text-center">Arrivée</div>
                  <div class="col col-sm-3 text-center d-none d-sm-block">Prix</div>
                </div>
              </div>
              <div class="bus-list">





                <?php


                foreach ($Schedules as $keySchedule => $Schedule) {
                
                $minutesToTime = Op::minutesToTime($Schedule['Rate']['min_arrival']);
                $mT = sprintf("%02d", $minutesToTime['h']).'h '.sprintf("%02d", $minutesToTime['m']);
                if ($minutesToTime['d']) {
                 $mT = $minutesToTime['d'].'j '.$mT;
                }

                //debug($Schedule);

                $ddate = date_create($Schedule['Schedule']['start']);
                date_add($ddate, date_interval_create_from_date_string('+'.$Schedule['Rate']['min_start'].' min'));
                $startTravel = date_format($ddate, 'H:i');   


                $ddate = date_create($Schedule['Schedule']['start']);
                date_add($ddate, date_interval_create_from_date_string('+'.$Schedule['Rate']['min_start'].' min'));
                date_add($ddate, date_interval_create_from_date_string('+'.$Schedule['Rate']['min_arrival'].' min'));
                $endTravel = date_format($ddate, 'H:i');

                $Tags = Hash::extract($Schedule['Destination']['Car']['Tag'], '{n}.name');

                //debug($Schedule);

                ?>

                <div class="bus-item">
                  <div class="row align-items-sm-center flex-row py-4 px-3">
                    <div class="col col-sm-3"> <span class="text-3 text-dark operator-name"><?php echo $Schedule['Destination']['Car']['Company']['name'] ?></span> <span class="text-1 text-muted d-block" style=" font-weight: 500; color: #0ad50a!important; "><?php echo CakeText::toList($Tags) ?></span> </div>
                    <div class="col col-sm-2 text-center time-info"> <span class="text-4 text-dark"><?php echo $startTravel ?></span> <small class="text-muted d-block"><?php echo $Schedule['From']['name'] ?></small> </div>
                    <div class="col col-sm-2 text-center d-none d-sm-block time-info"> <span class="text-3 duration"><?php echo $mT ?></span> <small class="text-muted d-block"><?php echo $Schedule['Rate']['stop'] ?> arrêts</small> </div>
                    <div class="col col-sm-2 text-center time-info"> <span class="text-4 text-dark"><?php echo $endTravel ?></span> <small class="text-muted d-block"><?php echo $Schedule['To']['name'] ?></small> </div>
                    <div class="col-12 col-sm-3 align-self-center text-right text-sm-center mt-2 mt-sm-0">
                      <div class="d-inline-block d-sm-block text-dark text-5 price mb-sm-1"><?php echo number_format($Schedule['Rate']['amount'], $Schedule['Currency']['decimal'], $Schedule['Currency']['decimalspace'], $Schedule['Currency']['thousandspace']).' '.$Schedule['Currency']['iso'] ?></div>
                      <a href="#" class="btn btn-sm btn-outline-primary shadow-none" data-toggle="modal" data-target="#select-busseats<?php echo $keySchedule ?>"><i class="fas fa-ellipsis-h d-none d-sm-block d-lg-none" data-toggle="tooltip" title="Reserver des sièges"></i> <span class="d-block d-sm-none d-lg-block">Reserver des sièges</span></a> </div>
                  </div>
                </div>

                <?php

                }

                ?>

              </div>
              
              <!-- Pagination
              ============================================= -->
              <ul class="pagination justify-content-center mt-4 mb-0">
                <li class="page-item disabled"> <a class="page-link" href="#" tabindex="-1"><i class="fas fa-angle-left"></i></a> </li>
                <li class="page-item active"> <a class="page-link" href="#">1 <span class="sr-only">(current)</span></a> </li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"> <a class="page-link" href="#"><i class="fas fa-angle-right"></i></a> </li>
              </ul><!-- Pagination end -->
            </div>
          </div>
        </div>
      </section>
  <!-- Content end -->
  






<!-- Bus Details (Reserver des sièges) Modal Dialog
============================================= -->

<?php

foreach ($Schedules as $keySchedule => $Schedule) {

  $Tags = Hash::extract($Schedule['Destination']['Car']['Tag'], '{n}.name');


?>

<div id="select-busseats<?php echo $keySchedule ?>" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Détails de la réservation d'Autobus</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
        <div class="bus-details">
          <div class="row align-items-sm-center flex-row mb-4">
            <div class="col col-sm-3"> <span class="text-4 text-dark operator-name"><?php echo $Schedule['Destination']['Company']['name'] ?></span> <span class="text-muted d-block" style=" font-weight: 500; color: #0ad50a!important; "><?php echo CakeText::toList($Tags) ?></span> </div>
            <div class="col col-sm-3 text-center time-info"> <span class="text-5 text-dark"><?php echo CakeTime::format($Schedule['Schedule']['start'], '%H:%M') ?></span> <small class="text-muted d-block"><?php echo $FromTown['Town']['name'] ?></small> </div>
            <div class="col col-sm-3 text-center d-none d-sm-block time-info"> <span class="text-3 duration"><?php echo $mT ?></span> <small class="text-muted d-block"><?php echo $Schedule['Rate']['stop'] ?> arrêts</small> </div>
            <div class="col col-sm-3 text-center time-info"> <span class="text-5 text-dark"><?php echo $endTravel ?></span> <small class="text-muted d-block"><?php echo $ToTown['Town']['name'] ?></small> </div>
          </div>
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item"> <a class="nav-link active" id="first-tab" data-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="true">Places libres</a> </li>
            <li class="nav-item"> <a class="nav-link" id="second-tab" data-toggle="tab" href="#second" role="tab" aria-controls="second" aria-selected="false">Frais d'annulation</a> </li>
          </ul>
          <div class="tab-content my-3" id="myTabContent">
            <div class="tab-pane fade show active" id="first" role="tabpanel" aria-labelledby="first-tab">
              <div class="row">
                <div class="col-12 col-lg-6 text-center">
                  <p class="text-muted text-1">Cliquez sur Siège pour sélectionner/ désélectionner</p>
                  <div id="seat-map<?php echo $keySchedule ?>">
                    <div class="front-indicator">L'avant</div>
                  </div>
                  <div id="legend<?php echo $keySchedule ?>"></div>
                </div>
                <div class="col-12 col-lg-6 mt-3 mt-lg-0">
                  <div class="booking-details">
                    <h2 class="text-5">Les détails de réservation</h2>
                    <p>Sièges sélectionnés (<span id="counter<?php echo $keySchedule ?>">0</span>):</p>
                    <ul id="selected-seats<?php echo $keySchedule ?>">
                    </ul>
                    <div class="d-flex bg-light-4 px-3 py-2 mb-3">Prix total: <span class="text-dark text-5 font-weight-600 ml-2"><span id="total<?php echo $keySchedule ?>">0</span> <?php echo $Schedule['Currency']['symbol'] ?></span>
                    </div>






                      <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item"> <a class="nav-link active" id="first-tab" data-toggle="tab" href="#bmmoney" role="tab" aria-controls="bmmoney" aria-selected="true">Mobile Money</a> </li>
                        <li class="nav-item"> <a class="nav-link" id="second-tab" data-toggle="tab" href="#bpaypal" role="tab" aria-controls="bpaypal" aria-selected="false">PayPal</a> </li>
                      </ul>
                      <div class="tab-content my-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="bmmoney" role="tabpanel" aria-labelledby="first-tab">


                          <?php
                          echo $this->Form->create(false,  array('url' => array('controller' => 'buy', 'action' => 'mmoney', $keySchedule),'type' => 'file', 'id' => 'buyformpesa'.$keySchedule, 'novalidate' => false, 'class'=> '', 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100')))));

                          ?>


                              <?php


                                  echo $this->Form->hidden('Sale.type', array('value' => 'mmoney'));

                                  echo $this->Form->hidden('Sale.user_id', array('value' => @$authUser['id']));
                                  echo $this->Form->hidden('Sale.total', array('id' => 'SaleAmount'.$keySchedule));

                                  echo $this->Form->hidden('Sale.info.place', array('id' => 'SalePlace'.$keySchedule));
                                  echo $this->Form->hidden('Sale.rate_id', array('value' => $Schedule['Rate']['id']));



                              ?>

                              <?php
                                  echo $this->Form->input('Sale.info.phone', array('type' => 'text', 'id' => 'buyphone'.$keySchedule, 'class' => 'form-control', 'required' => true, 'placeholder' => 'Mobile Money'));
                              ?>


                              <?php


                                  echo $this->Form->hidden('Sale.departure_date', array('value' => CakeTime::format($this->request->data['Search']['form_date'], '%Y-%m-%d')));
                                  echo $this->Form->hidden('Sale.schedule_id', array('value' => $Schedule['Schedule']['id']));



                              ?>



                              <!--ul>
                                <li><a href="<?php echo Router::url('/lang/en') ?>"><?php echo Op::translate('Anglais', 'fr-'); ?></a></li>                              
                              </ul-->

                              <div id="pesanot" style="padding: 5px;margin-top: 10px;"></div>

                          <?php echo $this->Form->button('Acheter', array('type' => 'submit', 'id' => 'buysubmitpesa'.$keySchedule, 'class' => 'btn btn-primary btn-block mt-2', 'name' => 'form', 'value' => 'buymobilemoney', 'disabled' => 'disabled')); ?>

                          <?php
                              echo $this->Form->end(); 
                          ?>

                        </div>
                        <div class="tab-pane fade" id="bpaypal" role="tabpanel" aria-labelledby="second-tab">

                        </div>
                      </div>



































                    <!--button class="btn btn-primary btn-block">Continue</button-->
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="second" role="tabpanel" aria-labelledby="second-tab">
              <table class="table table-hover table-bordered bg-light">
                <thead>
                  <tr>
                    <td>Heures avant le départ</td>
                    <td class="text-center">Pourcentage de remboursement</td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>12h Avant.</td>
                    <td class="text-center">0%</td>
                  </tr>
                  <tr>
                    <td>24h avant.</td>
                    <td class="text-center">30%</td>
                  </tr>
                  <tr>
                    <td>48h avant.</td>
                    <td class="text-center">60%</td>
                  </tr>
                  <tr>
                    <td>60h avant.</td>
                    <td class="text-center">75%</td>
                  </tr>
                  <tr>
                    <td>Au délà de 60h. </td>
                    <td class="text-center">80%</td>
                  </tr>
                </tbody>
              </table>
              <p class="font-weight-bold">Termes & Conditions</p>
              <ul>
                <li>La pénalité est soumise à 24 heures avant le départ. Aucune modification n'est autorisée après cela.</li>
                <li>Les frais sont par siège.</li>
                <li>L'annulation partielle n'est pas autorisée sur les billets réservés dans le cadre de tarifs réduits spéciaux.</li>
                <li>En cas de non-présentation ou de billet non annulé dans les délais stipulés, seules les taxes légales sont remboursables sous réserve des frais de service.</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php

}

?>


<!-- Bus Details Modal Dialog end -->
                  
<!-- Script -->
<script>
$(function() {

 'use strict';
  // Autocomplete
  $('#busFrom,#busTo').autocomplete({
	  minLength: 3,
	  delay: 100,
	  source: function (request, response) {
		$.getJSON(
		 'http://gd.geobytes.com/AutoCompleteCity?callback=?&q='+request.term,
		  function (data) {
			 response(data);
		}
	);
	},
  });
 // Depart Date
  $('#busDepart').daterangepicker({
	singleDatePicker: true,
	autoApply: true,
	minDate: moment(),
	autoUpdateInput: true,
	}, function(chosen_date) {
  $('#busDepart').val(chosen_date.format('MM/DD/YYYY'));
  });

// Departure Time Slider Range (jQuery UI)
	$("#slider-range-departure").slider({
  range: true,
  min: 0,
  max: 1439,
  values: [0, 1439],
  slide: function(e, ui) {
    $('.slider-time-departure').each(function(i) {
      var hours = ("00" + Math.floor(ui.values[i] / 60)).slice(-2);
      var mins = ("00" + (ui.values[i] - (hours * 60))).slice(-2);
      $(this).html(hours + ':' + mins);
    });
  }
});

// Slider Range (jQuery UI)
    $( "#slider-range" ).slider({
      range: true,
      min: 125,
      max: 1250,
      values: [ 125, 1250 ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
      }
    });
    $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
      " - $" + $( "#slider-range" ).slider( "values", 1 ) );
 });
	
// Seat Charts
  //var baglist;
  //baglist = new array();
  var baglist = [];

	$(document).ready(function() {

    $("#buyformpesa").submit(function (event) {
      var formData = {
        buyphone: $("#buyphone").val(),
        buyseats: JSON.parse(JSON.stringify(Object.assign({}, baglist))),
      };

      $('#pesanot').html('');

      if (JSON.stringify(Object.assign({}, baglist))  != "{}") {

        $.ajax({
          type: "POST",
          url: "<?php echo Router::url(array('controller' => 'Search', 'action' => 'pesa')) ?>",
          data: formData,
          dataType: "json",
          encode: true,
        }).done(function (data) {
          if(data.success){
            $('#pesanot').html('<div class="bg-green text-white">Push envoyée, verifier votre téléphone</div>');
          }else{
            $('#pesanot').html('<div class="bg-red text-white">Push non envoyée, réessayez plus tard</div>');
          }
        });

      }else{
        $('#pesanot').html('<div class="bg-orange text-white">Veuillez choisir un siège</div>');
      }



      event.preventDefault();
    });











    <?php

      foreach ($Schedules as $keySchedule => $Schedule) {

    ?>


        var firstSeatLabel = 1;

        var $cart<?php echo $keySchedule ?> = $('#selected-seats<?php echo $keySchedule ?>');

			  $counter<?php echo $keySchedule ?> = $('#counter'),
			  $total<?php echo $keySchedule ?> = $('#total<?php echo $keySchedule ?>'),
			  sc<?php echo $keySchedule ?> = $('#seat-map<?php echo $keySchedule ?>').seatCharts({
			  map: [
				  'ff_ff',
				  'ff_ff',
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
					  price   : <?php echo $Schedule['Rate']['amount'] ?>,
					  classes : 'first-class', //your custom CSS class
					  category: 'Première classe'
				  },
				  e: {
					  price   : <?php echo $Schedule['Rate']['amount'] ?>,
					  classes : 'economy-class', //your custom CSS class
					  category: 'Classe économique'
				  }					
			  
			  },
			  naming : {
				  top : false,
				  getLabel : function (character, row, column) {
					  return firstSeatLabel++;
				  },
			  },
			  legend : {
				  node : $('#legend<?php echo $keySchedule ?>'),
				  items : [
					  [ 'f', 'available',   'Première classe' ],
					  [ 'e', 'available',   'Classe économique'],
					  [ 'f', 'unavailable', 'Déjà réservé']
				  ]					
			  },
			  click: function () {

				  if (this.status() == 'available') {
					  //let's create a new <li> which we'll add to the cart items
					  $('<li>'+this.data().category+' Siège # '+this.settings.label+': <b>'+this.data().price+' <?php echo $Schedule['Currency']['symbol'] ?></b> <a href="#" class="cancel-cart-item text-danger text-4"><i class="far fa-times-circle"></i></a></li>')
						  .attr('id', 'cart-item-'+this.settings.id)
						  .data('seatId', this.settings.id)
						  .appendTo($cart<?php echo $keySchedule ?>);
					  



					  /*
					   * Lets update the counter and total
					   *
					   * .find function will not find the current seat, because it will change its stauts only after return
					   * 'selected'. This is why we have to add 1 to the length and the current seat price to the total.
					   */
					  $counter<?php echo $keySchedule ?>.text(sc<?php echo $keySchedule ?>.find('selected').length+1);
					  $total<?php echo $keySchedule ?>.text(recalculateTotal(sc<?php echo $keySchedule ?>)+this.data().price);

            baglist[this.settings.id] = this.data().price;


            $('#SaleAmount<?php echo $keySchedule ?>').val(recalculateTotal(sc<?php echo $keySchedule ?>)+this.data().price);
            $('#SalePlace<?php echo $keySchedule ?>').val(JSON.stringify(Object.assign({}, baglist)));
            $('#buysubmitpesa<?php echo $keySchedule ?>').removeAttr('disabled');



            //console.log(baglist);

					  return 'selected';
				  } else if (this.status() == 'selected') {
					  //update the counter
					  $counter<?php echo $keySchedule ?>.text(sc<?php echo $keySchedule ?>.find('selected').length-1);
					  //and total
					  $total<?php echo $keySchedule ?>.text(recalculateTotal(sc<?php echo $keySchedule ?>)-this.data().price);
				  
					  //remove the item from our cart
					  $('#cart-item-'+this.settings.id).remove();

            baglist[this.settings.id] = undefined;
				  

            $('#SaleAmount<?php echo $keySchedule ?>').val(recalculateTotal(sc<?php echo $keySchedule ?>)-this.data().price);
            $('#SalePlace<?php echo $keySchedule ?>').val(JSON.stringify(Object.assign({}, baglist)));

            if (!(recalculateTotal(sc<?php echo $keySchedule ?>)-this.data().price)) {
              $('#buysubmitpesa<?php echo $keySchedule ?>').attr('disabled', 'disabled');
            }



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
		  $('#selected-seats<?php echo $keySchedule ?>').on('click', '.cancel-cart-item', function () {
			  //let's just trigger Click event on the appropriate seat, so we don't have to repeat the logic here
			  sc<?php echo $keySchedule ?>.get($(this).parents('li:first').data('seatId')).click();
		  });

		  //let's pretend some seats have already been booked
		  sc<?php echo $keySchedule ?>.get(['1_2', '4_1', '7_1', '7_2']).status('unavailable');


      <?php

        }

      ?>


  
  });








  function recalculateTotal(sc) {
	  var total = 0;
  
	  //basically find every selected seat and sum its price
	  sc.find('selected').each(function () {
		  total += this.data().price;
	  });
	  
	  return total;
  



  }



</script>
