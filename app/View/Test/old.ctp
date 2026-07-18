<?php 
    $this->set('title_for_layout', Configure::read('Company.name').' - '.Op::translate(Configure::read('Company.slogan'), 'fr-'));

    $this->set('robots', 'all');

    $this->set('navHome', "active");

?>

    <style>
      .videoWrapper{
          position:relative;
          padding-bottom:56.25%;
          height:0
      }
      .videoWrapper iframe{position:absolute;top:0;left:0;width:100%;height:100%}
    </style>





    <div class="hero-wrap py-4 py-md-5">
      <div class="hero-mask opacity-4 bg-primary" style="background-color: rgb(58 58 58 / 70%) !important;"></div>
      <div class="hero-bg" style="background-image:url('<?php echo Router::url(Op::resizedURL($Countries[CakeSession::read('Localisation')]['Country']['cover'], array('width' => 720, 'height' => 400, 'quality' => 100, 'space' => false))) ?>');"></div>
      <div class="hero-content py-0 py-lg-3">
        <div class="container">
          <div class="row">
            <div class="col-lg-6">
              <div class="position-relative px-4 pt-3 pb-4">
                <div class="hero-mask opacity-8 bg-dark rounded"></div>
                <div class="hero-content">
                  <!-- Tabs -->
                  <ul class="nav nav-tabs nav-fill style-4 border-bottom" id="myTab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" id="bus-tab" data-toggle="tab" href="#bus" role="tab" aria-controls="bus" aria-selected="false">Bus</a> </li>
                    <li class="nav-item"> <a class="nav-link" id="flight-tab" data-toggle="tab" href="#flight" role="tab" aria-controls="flight" aria-selected="true">Vols</a> </li>
                    <li class="nav-item"> <a class="nav-link" id="hotels-tab" data-toggle="tab" href="#hotels" role="tab" aria-controls="hotels" aria-selected="true">Hôtels</a> </li>
                    <li class="nav-item"> <a class="nav-link" id="trains-tab" data-toggle="tab" href="#trains" role="tab" aria-controls="trains" aria-selected="false">Trains</a> </li>
                    <li class="nav-item"> <a class="nav-link" id="car-tab" data-toggle="tab" href="#car" role="tab" aria-controls="car" aria-selected="false">Taxi</a> </li>
                  </ul>
                  <div class="tab-content pt-4" id="myTabContent">

                    <!-- Search Bus -->
                    <div class="tab-pane fade show active" id="bus" role="tabpanel" aria-labelledby="bus-tab">

                        <?php
                        echo $this->Form->create(false,  array('type' => 'file', 'id' => 'bookingBus', 'novalidate' => false, 'class'=> 'search-input-line', 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100')))));
                        ?>

                        <div class="row">
                          <div class="col-12 form-group">
                            <?php
                                $ListTowns = Hash::combine($Towns, '{n}.Town.id', '{n}.Town.name');
                                echo $this->Form->input('Search.from_town_id', array('id' => 'busFrom', 'class' => 'form-control', 'required' => true, 'placeholder' => 'De', 'empty' => '(De)', 'options' => $STowns));
                            ?>
                            <!--input type="text" class="form-control" id="busFrom" required placeholder="De"-->
                            <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
                          <div class="col-12 form-group">
                            <?php
                                $ListTowns = Hash::combine($Towns, '{n}.Town.id', '{n}.Town.name');
                                echo $this->Form->input('Search.to_town_id', array('id' => 'busTo', 'class' => 'form-control', 'required' => true, 'placeholder' => 'À', 'empty' => '(À)', 'options' => $STowns));
                            ?>
                            <!--input type="text" class="form-control" id="busTo" required placeholder="À"-->
                            <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
                          <div class="col-12 form-group">
                            <!--input id="busDepart" type="text" class="form-control" required placeholder="Date de départ"-->
                            <?php
                                echo $this->Form->input('Search.form_date', array('type' => 'text', 'id' => 'busDepart', 'class' => 'form-control', 'required' => true, 'placeholder' => 'Date de départ'));
                            ?>
                            <span class="icon-inside"><i class="far fa-calendar-alt"></i></span> </div>
                          <div class="col-12 travellers-class form-group">
                            <!--input type="text" id="busTravellersClass"  class="travellers-class-input form-control" name="bus-travellers-class" placeholder="Places" required onkeypress="return false;"-->
                            <?php
                                echo $this->Form->input('Search.seats', array('type' => 'text', 'id' => 'busTravellersClass', 'class' => 'travellers-class-input form-control', 'required' => true, 'placeholder' => 'Places', 'onkeypress' => 'return false;'));
                            ?>
                            <span class="icon-inside"><i class="fas fa-caret-down"></i></span>
                            
                            <!-- Travellers & Class Dropdown -->
                            <div class="travellers-dropdown">
                              <div class="row align-items-center mb-3">
                                <div class="col-sm-7">
                                  <p class="mb-sm-0">Places</p>
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
                        </div>
                        <!--button class="btn btn-primary btn-block mt-2" type="submit">Recherche</button-->
                        <?php echo $this->Form->button('Recherche', array('type' => 'submit', 'id' => 'UsernameForm', 'class' => 'btn btn-primary btn-block mt-2', 'name' => 'form', 'value' => 'searchbus')); ?>


                        <?php
                            echo $this->Form->end(); 
                        ?>

                    </div>

                    <!-- Search Flight -->
                    <div class="tab-pane fade" id="flight" role="tabpanel" aria-labelledby="flight-tab">
                      <form id="bookingFlight" class="search-input-line" method="post">
                        <div class="text-light mb-2">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input id="oneway" name="flight-trip" class="custom-control-input" checked="" required type="radio">
                            <label class="custom-control-label" for="oneway">One Way</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input id="roundtrip" name="flight-trip" class="custom-control-input" required type="radio">
                            <label class="custom-control-label" for="roundtrip">Round Trip</label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-6 form-group">
                            <input type="text" class="form-control" id="flightFrom" required placeholder="From">
                            <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
                          <div class="col-lg-6 form-group">
                            <input type="text" class="form-control" id="flightTo" required placeholder="To">
                            <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-6 form-group">
                            <input id="flightDepart" type="text" class="form-control" required placeholder="Depart Date">
                            <span class="icon-inside"><i class="far fa-calendar-alt"></i></span> </div>
                          <div class="col-lg-6 form-group">
                            <input id="flightReturn" type="text" class="form-control" required placeholder="Return Date">
                            <span class="icon-inside"><i class="far fa-calendar-alt"></i></span> </div>
                        </div>
                        <div class="form-group">
                          <select class="custom-select" id="operator" required="">
                            <option value="">Select Your Operator</option>
                            <option>1st Operator</option>
                            <option>2nd Operator</option>
                            <option>3rd Operator</option>
                            <option>4th Operator</option>
                            <option>5th Operator</option>
                            <option>6th Operator</option>
                            <option>7th Operator</option>
                          </select>
                        </div>
                        <div class="travellers-class form-group">
                          <input type="text" id="flightTravellersClass" class="travellers-class-input form-control" name="flight-travellers-class" placeholder="Travellers, Class" readonly required onkeypress="return false;">
                          <a href=""></a> <span class="icon-inside"><i class="fas fa-caret-down"></i></span>
                          <!-- Travellers & Class Dropdown -->
                          <div class="travellers-dropdown">
                            <div class="row align-items-center">
                              <div class="col-sm-7">
                                <p class="mb-sm-0">Adults <small class="text-muted">(12+ yrs)</small></p>
                              </div>
                              <div class="col-sm-5">
                                <div class="qty input-group">
                                  <div class="input-group-prepend">
                                    <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#flightAdult-travellers" data-toggle="spinner">-</button>
                                  </div>
                                  <input type="text" data-ride="spinner" id="flightAdult-travellers" class="qty-spinner form-control" value="1" readonly>
                                  <div class="input-group-append">
                                    <button type="button" class="btn bg-light-4" data-value="increase" data-target="#flightAdult-travellers" data-toggle="spinner">+</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <hr class="my-2">
                            <div class="row align-items-center">
                              <div class="col-sm-7">
                                <p class="mb-sm-0">Children <small class="text-muted">(2-12 yrs)</small></p>
                              </div>
                              <div class="col-sm-5">
                                <div class="qty input-group">
                                  <div class="input-group-prepend">
                                    <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#flightChildren-travellers" data-toggle="spinner">-</button>
                                  </div>
                                  <input type="text" data-ride="spinner" id="flightChildren-travellers" class="qty-spinner form-control" value="0" readonly>
                                  <div class="input-group-append">
                                    <button type="button" class="btn bg-light-4" data-value="increase" data-target="#flightChildren-travellers" data-toggle="spinner">+</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <hr class="my-2">
                            <div class="row align-items-center">
                              <div class="col-sm-7">
                                <p class="mb-sm-0">Infants <small class="text-muted">(Below 2 yrs)</small></p>
                              </div>
                              <div class="col-sm-5">
                                <div class="qty input-group">
                                  <div class="input-group-prepend">
                                    <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#flightInfants-travellers" data-toggle="spinner">-</button>
                                  </div>
                                  <input type="text" data-ride="spinner" id="flightInfants-travellers" class="qty-spinner form-control" value="0" readonly>
                                  <div class="input-group-append">
                                    <button type="button" class="btn bg-light-4" data-value="increase" data-target="#flightInfants-travellers" data-toggle="spinner">+</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <hr class="mt-2">
                            <div class="mb-3">
                              <div class="custom-control custom-radio">
                                <input id="flightClassEconomic" name="flight-class" class="flight-class custom-control-input" value="0" checked="" required type="radio">
                                <label class="custom-control-label" for="flightClassEconomic">Economic</label>
                              </div>
                              <div class="custom-control custom-radio">
                                <input id="flightClassPremiumEconomic" name="flight-class" class="flight-class custom-control-input" value="1" required type="radio">
                                <label class="custom-control-label" for="flightClassPremiumEconomic">Premium Economic</label>
                              </div>
                              <div class="custom-control custom-radio">
                                <input id="flightClassBusiness" name="flight-class" class="flight-class custom-control-input" value="2" required type="radio">
                                <label class="custom-control-label" for="flightClassBusiness">Business</label>
                              </div>
                              <div class="custom-control custom-radio">
                                <input id="flightClassFirstClass" name="flight-class" class="flight-class custom-control-input" value="3" required type="radio">
                                <label class="custom-control-label" for="flightClassFirstClass">First Class</label>
                              </div>
                            </div>
                            <button class="btn btn-primary btn-block submit-done" type="button">Done</button>
                          </div>
                        </div>
                        <button class="btn btn-primary btn-block mt-4" type="submit">Search Flights</button>
                      </form>
                    </div>
                    <!-- Search Hotels -->
                    <div class="tab-pane fade" id="hotels" role="tabpanel" aria-labelledby="hotels-tab">
                      <form id="bookingHotels" class="search-input-line" method="post">
  <div class="row">
    <div class="col-lg-12 form-group">
      <input type="text" class="form-control" id="hotelsFrom" required placeholder="Enter Locality, City">
      <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
  </div>
  <div class="row">
    <div class="col-lg-6 form-group">
      <input id="hotelsCheckIn" type="text" class="form-control" required placeholder="Check In">
      <span class="icon-inside"><i class="far fa-calendar-alt"></i></span> </div>
    <div class="col-lg-6 form-group">
      <input id="hotelsCheckOut" type="text" class="form-control" required placeholder="Check Out">
      <span class="icon-inside"><i class="far fa-calendar-alt"></i></span> </div>
  </div>
  <div class="travellers-class form-group">
    <input type="text" id="hotelsTravellersClass" class="travellers-class-input form-control" name="hotels-travellers-class" placeholder="Rooms / People" required onKeyPress="return false;">
    <span class="icon-inside"><i class="fas fa-caret-down"></i></span>
    <div class="travellers-dropdown" style="display: none;">
      <div class="row align-items-center">
        <div class="col-sm-7">
          <p class="mb-sm-0">Rooms</p>
        </div>
        <div class="col-sm-5">
          <div class="qty input-group">
            <div class="input-group-prepend">
              <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#hotels-rooms" data-toggle="spinner">-</button>
            </div>
            <input type="text" data-ride="spinner" id="hotels-rooms" class="qty-spinner form-control" value="1" min="40" readonly>
            <div class="input-group-append">
              <button type="button" class="btn bg-light-4" data-value="increase" data-target="#hotels-rooms" data-toggle="spinner">+</button>
            </div>
          </div>
        </div>
      </div>
      <hr class="mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-sm-7">
          <p class="mb-sm-0">Adults <small class="text-muted">(12+ yrs)</small></p>
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
      <hr class="my-2">
      <div class="row align-items-center">
        <div class="col-sm-7">
          <p class="mb-sm-0">Children <small class="text-muted">(1-12 yrs)</small></p>
        </div>
        <div class="col-sm-5">
          <div class="qty input-group">
            <div class="input-group-prepend">
              <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#children-travellers" data-toggle="spinner">-</button>
            </div>
            <input type="text" data-ride="spinner" id="children-travellers" class="qty-spinner form-control" value="0" readonly>
            <div class="input-group-append">
              <button type="button" class="btn bg-light-4" data-value="increase" data-target="#children-travellers" data-toggle="spinner">+</button>
            </div>
          </div>
        </div>
      </div>
      <button class="btn btn-primary btn-block submit-done mt-3" type="button">Done</button>
    </div>
  </div>
  <button class="btn btn-primary btn-block mt-4" type="submit">Search Hotels</button>
</form>
                    </div>
                    <!-- Search Train -->
                    <div class="tab-pane fade" id="trains" role="tabpanel" aria-labelledby="trains-tab">
                      <form id="bookingTrain" class="search-input-line" method="post">
                        <div class="row">
                          <div class="col-12 form-group">
                            <input type="text" class="form-control" id="trainFrom" required placeholder="From">
                            <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
                          <div class="col-12 form-group">
                            <input type="text" class="form-control" id="trainTo" required placeholder="To">
                            <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
                          <div class="col-12 form-group">
                            <input id="trainDepart" type="text" class="form-control" required placeholder="Depart Date">
                            <span class="icon-inside"><i class="far fa-calendar-alt"></i></span> </div>
                          <div class="col-12 travellers-class form-group">
                            <input type="text" id="trainTravellersClass"  class="travellers-class-input form-control" name="train-travellers-class" placeholder="Travellers, Class" required onkeypress="return false;">
                            <span class="icon-inside"><i class="fas fa-caret-down"></i></span>
                            
                            <!-- Travellers & Class Dropdown -->
                            <div class="travellers-dropdown">
                              <div class="row align-items-center">
                                <div class="col-sm-7">
                                  <p class="mb-sm-0">Adults <small class="text-muted">(12+ yrs)</small></p>
                                </div>
                                <div class="col-sm-5">
                                  <div class="qty input-group">
                                    <div class="input-group-prepend">
                                      <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#trainAdult-travellers" data-toggle="spinner">-</button>
                                    </div>
                                    <input type="text" data-ride="spinner" id="trainAdult-travellers" class="qty-spinner form-control" value="1" readonly>
                                    <div class="input-group-append">
                                      <button type="button" class="btn bg-light-4" data-value="increase" data-target="#trainAdult-travellers" data-toggle="spinner">+</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <hr class="my-2">
                              <div class="row align-items-center">
                                <div class="col-sm-7">
                                  <p class="mb-sm-0">Children <small class="text-muted">(2-12 yrs)</small></p>
                                </div>
                                <div class="col-sm-5">
                                  <div class="qty input-group">
                                    <div class="input-group-prepend">
                                      <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#trainChildren-travellers" data-toggle="spinner">-</button>
                                    </div>
                                    <input type="text" data-ride="spinner" id="trainChildren-travellers" class="qty-spinner form-control" value="0" readonly>
                                    <div class="input-group-append">
                                      <button type="button" class="btn bg-light-4" data-value="increase" data-target="#trainChildren-travellers" data-toggle="spinner">+</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <hr class="my-2">
                              <div class="row align-items-center">
                                <div class="col-sm-7">
                                  <p class="mb-sm-0">Infants <small class="text-muted">(Below 2 yrs)</small></p>
                                </div>
                                <div class="col-sm-5">
                                  <div class="qty input-group">
                                    <div class="input-group-prepend">
                                      <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#trainInfants-travellers" data-toggle="spinner">-</button>
                                    </div>
                                    <input type="text" data-ride="spinner" id="trainInfants-travellers" class="qty-spinner form-control" value="0" readonly>
                                    <div class="input-group-append">
                                      <button type="button" class="btn bg-light-4" data-value="increase" data-target="#trainInfants-travellers" data-toggle="spinner">+</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group mt-3">
                                <select id="train-class" name="train-class" class="custom-select">
                                  <option value="0">All Class</option>
                                  <option value="1">First Class</option>
                                  <option value="2">Second Class</option>
                                  <option value="3">First Class Sleeper (SL)</option>
                                  <option value="4">Second Class Sleeper (SL)</option>
                                  <option value="5">Business</option>
                                </select>
                              </div>
                              <button class="btn btn-primary btn-block submit-done" type="button">Done</button>
                            </div>
                          </div>
                        </div>
                        <button class="btn btn-primary btn-block mt-2" type="submit">Search Trains</button>
                      </form>
                    </div>

                    <!-- Search Car -->
                    <div class="tab-pane fade" id="car" role="tabpanel" aria-labelledby="car-tab">
                      <form id="bookingCars" class="search-input-line" method="post">
                <div class="row">
                  <div class="col-lg form-group">
                    <input type="text" class="form-control ui-autocomplete-input" id="carsCity" required placeholder="Enter City" autocomplete="off">
                    <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
                </div>
                <div class="row">
                <div class="col-8 form-group">
                  <input id="carsPickup" type="text" class="form-control" required placeholder="Pick-up date">
                  <span class="icon-inside"><i class="far fa-calendar-alt"></i></span> 
                  </div>
                  <div class="col-4 form-group">
                  <select class="custom-select" id="carsPickuptime" required="">
                  <option value="">12:00 am</option>
                  <option>12:30 am</option>
                  <option>01:00 am</option>
                  <option>01:30 am</option>
                  <option>02:00 am</option>
                  <option>02:30 am</option>
                  <option>03:00 am</option>
                  <option>03:30 am</option>
                  <option>04:00 am</option>
                  <option>04:30 am</option>
                  <option>05:00 am</option>
                  <option>05:30 am</option>
                  <option>06:00 am</option>
                  <option>06:30 am</option>
                  <option>07:00 am</option>
                  <option>07:30 am</option>
                  <option>08:00 am</option>
                  <option>08:30 am</option>
                  <option>09:00 am</option>
                  <option>09:30 am</option>
                  <option>10:00 am</option>
                  <option>10:30 am</option>
                  <option>11:00 am</option>
                  <option>11:30 am</option>
                  <option>12:00 pm</option>
                  <option>12:30 pm</option>
                  <option>01:00 pm</option>
                  <option>01:30 pm</option>
                  <option>02:00 pm</option>
                  <option>02:30 pm</option>
                  <option>03:00 pm</option>
                  <option>03:30 pm</option>
                  <option>04:00 pm</option>
                  <option>04:30 pm</option>
                  <option>05:00 pm</option>
                  <option>05:30 pm</option>
                  <option>06:00 pm</option>
                  <option>06:30 pm</option>
                  <option>07:00 pm</option>
                  <option>07:30 pm</option>
                  <option>08:00 pm</option>
                  <option>08:30 pm</option>
                  <option>09:00 pm</option>
                  <option>09:30 pm</option>
                  <option>10:00 pm</option>
                  <option>10:30 pm</option>
                  <option>11:00 pm</option>
                  <option>11:30 pm</option>
                </select> 
                  </div>
                  </div>
                <div class="row">
                <div class="col-8 form-group">
                  <input id="carsDropoff" type="text" class="form-control" required placeholder="Drop-off date">
                  <span class="icon-inside"><i class="far fa-calendar-alt"></i></span> 
                  </div>
                  <div class="col-4 form-group">
                  <select class="custom-select" id="carsDropofftime" required="">
                  <option value="">12:00 am</option>
                  <option>12:30 am</option>
                  <option>01:00 am</option>
                  <option>01:30 am</option>
                  <option>02:00 am</option>
                  <option>02:30 am</option>
                  <option>03:00 am</option>
                  <option>03:30 am</option>
                  <option>04:00 am</option>
                  <option>04:30 am</option>
                  <option>05:00 am</option>
                  <option>05:30 am</option>
                  <option>06:00 am</option>
                  <option>06:30 am</option>
                  <option>07:00 am</option>
                  <option>07:30 am</option>
                  <option>08:00 am</option>
                  <option>08:30 am</option>
                  <option>09:00 am</option>
                  <option>09:30 am</option>
                  <option>10:00 am</option>
                  <option>10:30 am</option>
                  <option>11:00 am</option>
                  <option>11:30 am</option>
                  <option>12:00 pm</option>
                  <option>12:30 pm</option>
                  <option>01:00 pm</option>
                  <option>01:30 pm</option>
                  <option>02:00 pm</option>
                  <option>02:30 pm</option>
                  <option>03:00 pm</option>
                  <option>03:30 pm</option>
                  <option>04:00 pm</option>
                  <option>04:30 pm</option>
                  <option>05:00 pm</option>
                  <option>05:30 pm</option>
                  <option>06:00 pm</option>
                  <option>06:30 pm</option>
                  <option>07:00 pm</option>
                  <option>07:30 pm</option>
                  <option>08:00 pm</option>
                  <option>08:30 pm</option>
                  <option>09:00 pm</option>
                  <option>09:30 pm</option>
                  <option>10:00 pm</option>
                  <option>10:30 pm</option>
                  <option>11:00 pm</option>
                  <option>11:30 pm</option>
                </select> 
                  </div>
                  </div>
                <div class="custom-control custom-checkbox d-inline-block text-white-50 mb-4 mt-2">
              <input type="checkbox" id="terms" name="termsConditions" checked="" class="custom-control-input">
              <label class="custom-control-label d-block" for="terms">Driver aged 25 - 70 <span class="text-info" data-toggle="tooltip" data-original-title="Car rental suppliers may charge more if a driver is under 30 or over 60. Please check the car's terms &amp; conditions."><i class="far fa-question-circle"></i></span></label>
              </div>
                <button class="btn btn-primary btn-block" type="submit">Search Cars</button>
              </form>
                    </div>
                  </div><!-- Tabs End -->
                </div>
              </div>
            </div>
            <!--div class="col-lg-6 mt-5 mt-lg-0">
              <h2 class="text-9 font-weight-600 text-light">Pourquoi réserver avec nous ?</h2>
              <p class="lead mb-4 text-light">Reservation en ligne. Gagnez du temps et de l'argent !</p>
              <div class="row">
                <div class="col-12">
                  <div class="featured-box style-3 mb-4">
                    <div class="featured-box-icon border rounded-circle text-light"> <i class="fas fa-dollar-sign"></i></div>
                    <h3 class="text-light">Prix le moins cher</h3>
                    <p class="text-light opacity-8">Obtenez toujours le prix le moins cher avec le meilleur de l'industrie. Ainsi, vous obtenez la meilleure offre à chaque fois.</p>
                  </div>
                </div>
                <div class="col-12">
                  <div class="featured-box style-3 mb-4">
                    <div class="featured-box-icon border rounded-circle text-light"> <i class="fas fa-times"></i></div>
                    <h3 class="text-light">Annulation et remboursements faciles</h3>
                    <p class="text-light opacity-8">Obtenez un remboursement instantané et bénéficiez d'une exonération des frais de réservation ! Un processus d'annulation facile est disponible.</p>
                  </div>
                </div>
                <div class="col-12">
                  <div class="featured-box style-3">
                    <div class="featured-box-icon border rounded-circle text-light"> <i class="fas fa-percentage"></i></div>
                    <h3 class="text-light">Pas de frais de réservation</h3>
                    <p class="text-light opacity-8 mb-0">Pas de frais cachés, pas de frais de paiement et un service client gratuit. Ainsi, vous obtenez la meilleure offre à chaque fois!</p>
                  </div>
                </div>
              </div>
            </div-->
          </div>
        </div>
      </div>
    </div>
    <div class="section bg-white shadow-md">
      <div class="container"> 








        <div class="owl-carousel owl-theme banner" data-autoplay="true" data-autoplayhoverpause="true"  data-autoplaytimeout="180000" data-loop="true" data-nav="true" data-margin="30" data-items-xs="1" data-items-sm="2" data-items-md="2" data-items-lg="2" data-dots="false">


          <?php

          foreach ($Publicities as $key => $Publicity) {

            if (is_file(WWW_ROOT.$Publicity['Publicity']['cover'])){

            }elseif ($Publicity['Publicity']['youtube']){
              
            }else{
              continue;
            }


            $Thumb = $Publicity['Publicity']['cover'];



          ?>


            <div class="item rounded"> <!--a href="#"-->
              <div class="caption">
                <!--h2>20% OFF</h2-->
                <p><?php echo $Publicity['Publicity']['name'] ?></p>
              </div>
              <!--div class="banner-mask"></div-->
              <?php
              if ($Publicity['Publicity']['youtube']) {
              ?>

                <div class="videoWrapper">
                  <?php
                      echo $this->Video->embed($Publicity['Publicity']['youtube'], array('width' => 773, 'height' => 435));
                  ?>
                </div>

              <?php

              }else{
              ?>

                <img class="img-fluid" src="<?php echo Router::url(Op::resizedURL($Thumb, array('width' => 360, 'height' => 202, 'quality' => 80, 'space' => 'blur', 'blur' => '80'))) ?>" alt="banner" /> <!--/a-->

              <?php

              }

              ?>


            </div>


          <?php
          }

          ?>



        </div>










        
        <!-- Banner
        ============================================= -->
        <h2 class="text-9 font-weight-600 text-center mt-5">Commencez votre planification de voyage ici</h2>
        <p class="lead text-dark text-center mb-5">Rechercher des bus, des hôtels et des vols</p>

        <div class="owl-carousel owl-theme banner" data-autoplay="true" data-loop="true" data-nav="true" data-margin="30" data-items-xs="1" data-items-sm="2" data-items-md="2" data-items-lg="3">


          <?php

          foreach ($TTowns as $key => $TTown) {

            $Thumb = @$TTown['Media'][array_rand($TTown['Media'])];



          ?>


            <div class="item rounded"> <a href="#">
              <div class="caption">
                <!--h2>20% OFF</h2-->
                <p><?php echo $TTown['Town']['name'] ?></p>
              </div>
              <div class="banner-mask"></div>
              <img class="img-fluid" src="<?php echo Router::url(Op::resizedURL($Thumb['file'], array('width' => 360, 'height' => 200, 'quality' => 80, 'space' => false))) ?>" alt="banner" /> </a>
            </div>


          <?php
          }

          ?>



        </div>
        <!-- Banner end --> 
        
        <!-- Popular Routes
      ============================================= -->
        <!--h2 class="text-9 font-weight-600 text-center mt-5">Commencez votre planification de voyage ici</h2>
        <p class="lead text-dark text-center mb-5">Rechercher des bus, des hôtels et des vols</p>
        <div class="row">
          <div class="col-md-6 col-lg-4">
            <div class="accordion accordion-alternate popularRoutes mx-lg-2" id="popularRoutes">
              <div class="card">
                <div class="card-header" id="one">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"> New Delhi <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="one" data-parent="#popularRoutes">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - New Delhi <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - New Delhi <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - New Delhi <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - New Delhi <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - New Delhi <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - New Delhi <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Bhopal To Indore <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Bangalore to Chennai <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - New Delhi <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="two">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> Bengaluru <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="two" data-parent="#popularRoutes">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - Bengaluru <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - Bengaluru <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - Bengaluru <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - Bengaluru <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - Bengaluru <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Bengaluru <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Surat - Bengaluru <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Bengaluru <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - Bengaluru <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="three">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"> Chennai <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="three" data-parent="#popularRoutes">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - Chennai <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - Chennai <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - Chennai <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - Chennai <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - Chennai <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Chennai <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Surat - Chennai <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Chennai <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - Chennai <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="four">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour"> Mumbai <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseFour" class="collapse" aria-labelledby="four" data-parent="#popularRoutes">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - Mumbai <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - Mumbai <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - Mumbai <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - Mumbai <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - Mumbai <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Mumbai <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Surat - Mumbai <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Mumbai <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - Mumbai <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="five">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive"> Hyderabad <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseFive" class="collapse" aria-labelledby="five" data-parent="#popularRoutes">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - Hyderabad <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - Hyderabad <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - Hyderabad <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - Hyderabad <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - Hyderabad <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Hyderabad <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Surat - Hyderabad <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Hyderabad <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - Hyderabad <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="accordion accordion-alternate popularRoutes mx-lg-2" id="popularRoutes2">
              <div class="card">
                <div class="card-header" id="six">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix"> Chicago <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseSix" class="collapse" aria-labelledby="six" data-parent="#popularRoutes2">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - Chicago <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - Chicago <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - Chicago <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - Chicago <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - Chicago <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Chicago <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Bhopal To Chicago <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Bangalore to Chicago <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - Chicago <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="seven">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseTwo"> New York <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseSeven" class="collapse" aria-labelledby="seven" data-parent="#popularRoutes2">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - New York <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - New York <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - New York <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - New York <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - New York <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - New York <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Surat - New York <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - New York <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - New York <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="eight">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight"> London <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseEight" class="collapse" aria-labelledby="eight" data-parent="#popularRoutes2">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - London <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - London <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - London <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - London <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - London <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - London <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Surat - London <span class="ml-auto">$ 1,209+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="nine">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine"> Panaji <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseNine" class="collapse" aria-labelledby="nine" data-parent="#popularRoutes2">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - Panaji <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - Panaji <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - Panaji <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - Panaji <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - Panaji <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Panaji <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Surat - Panaji <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Panaji <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - Panaji <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="ten">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen"> Ahmedabad <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseTen" class="collapse" aria-labelledby="ten" data-parent="#popularRoutes2">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - Ahmedabad <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - Ahmedabad <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - Ahmedabad <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - Ahmedabad <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - Ahmedabad <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Ahmedabad <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Surat - Ahmedabad <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Ahmedabad <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - Ahmedabad <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="accordion accordion-alternate popularRoutes mx-lg-2" id="popularRoutes3">
              <div class="card">
                <div class="card-header" id="eleven">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven"> Bangkok <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseEleven" class="collapse" aria-labelledby="eleven" data-parent="#popularRoutes3">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - Bangkok <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - Bangkok <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - Bangkok <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - Bangkok <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - Bangkok <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Bangkok <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Surat - Bangkok <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Bangkok <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - Bangkok <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="twelve">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve"> Singapore <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseTwelve" class="collapse" aria-labelledby="twelve" data-parent="#popularRoutes3">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - Singapore <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - Singapore <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - Singapore <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - Singapore <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - Singapore <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Singapore <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Bhopal To Singapore <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Bangalore to Singapore <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - Singapore <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="thirteen">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen"> Los Angeles <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseThirteen" class="collapse" aria-labelledby="thirteen" data-parent="#popularRoutes3">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - Los Angeles <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - Los Angeles <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - Los Angeles <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - Los Angeles <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - Los Angeles <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Los Angeles <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Surat - Los Angeles <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Los Angeles <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - Los Angeles <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="fourteen">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen"> San Francisco <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseFourteen" class="collapse" aria-labelledby="fourteen" data-parent="#popularRoutes3">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - San Francisco <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - San Francisco <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - San Francisco <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - San Francisco <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - San Francisco <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - San Francisco <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Surat - San Francisco <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - San Francisco <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - San Francisco <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="fifteen">
                  <h5 class="collapsed mb-0" data-toggle="collapse" data-target="#collapseFifteen" aria-expanded="false" aria-controls="collapseFifteen">Hong Kong <span class="nav"><a href="#">HÔTELS</a><a href="#">VOLS</a><a href="#">TRAINS</a><a href="#">BUS</a></span> </h5>
                </div>
                <div id="collapseFifteen" class="collapse" aria-labelledby="fifteen" data-parent="#popularRoutes3">
                  <div class="card-body">
                    <ul class="routes-list">
                      <li><i class="fas fa-bed"></i></li>
                      <li><a href="#">The Orchid Hotel <span class="ml-auto">$ 210+</span></a></li>
                      <li><a href="#">Whistling Meadows Resort <span class="ml-auto">$ 675+</span></a></li>
                      <li><a href="#">Radisson Blu Hotel <span class="ml-auto">$ 280+</span></a></li>
                      <li><a href="#">The Lotus Hotel <span class="ml-auto">$ 412+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-plane"></i></li>
                      <li><a href="#">Jaipur - Hong Kong <span class="ml-auto">$ 1,015+</span></a></li>
                      <li><a href="#">Varanasi - Hong Kong <span class="ml-auto">$ 3,152+</span></a></li>
                      <li><a href="#">Amritsar - Hong Kong <span class="ml-auto">$ 4,137+</span></a></li>
                      <li><a href="#">Ahmedabad - Hong Kong <span class="ml-auto">$ 925+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-train"></i></li>
                      <li><a href="#">Surat - Hong Kong <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Hong Kong <span class="ml-auto">$ 1,999+</span></a></li>
                    </ul>
                    <ul class="routes-list">
                      <li><i class="fas fa-bus"></i></li>
                      <li><a href="#">Surat - Hong Kong <span class="ml-auto">$ 1,209+</span></a></li>
                      <li><a href="#">Kolkata - Hong Kong <span class="ml-auto">$ 1,999+</span></a></li>
                      <li><a href="#">Srinagar - Hong Kong <span class="ml-auto">$ 2,100+</span></a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 mt-4">
            <p class="text-center mb-0"><a href="#" class="btn btn-link">View All <i class="fas fa-arrow-right ml-1"></i></a></p>
          </div>
        </div-->
        <!-- Popular Routes end --> 
        
      </div>
    </div>
    
    <!-- Refer & Earn
    ============================================= -->
    <!--section class="section bg-secondary text-light shadow-md">
      <div class="container">
        <h2 class="text-9 text-light font-weight-600 text-center">Refer & Earn</h2>
        <p class="lead text-center mb-5">Refer your friends and earn up to $20.</p>
        <div class="row">
          <div class="col-md-4">
            <div class="featured-box style-3">
              <div class="featured-box-icon bg-primary text-light rounded-circle"> <i class="fas fa-bullhorn"></i> </div>
              <h3 class="text-light">You Refer Friends</h3>
              <p class="text-3 opacity-8">Share your referral link with friends. They get $10.</p>
            </div>
          </div>
          <div class="col-md-4 mt-4 mt-md-0">
            <div class="featured-box style-3">
              <div class="featured-box-icon bg-primary text-light rounded-circle"> <i class="fas fa-sign-in-alt"></i> </div>
              <h3 class="text-light">Your Friends Register</h3>
              <p class="text-3 opacity-8">Your friends Register with using your referral link.</p>
            </div>
          </div>
          <div class="col-md-4 mt-4 mt-md-0">
            <div class="featured-box style-3">
              <div class="featured-box-icon bg-primary text-light rounded-circle"> <i class="fas fa-dollar-sign"></i> </div>
              <h3 class="text-light">Earn You</h3>
              <p class="text-3 opacity-8">You get $20. You can use these credits to take recharge.</p>
            </div>
          </div>
        </div>
        <div class="text-center pt-4"> <a href="#" class="btn btn-outline-light">Get Started Earn</a> </div>
      </div>
    </section-->
    <!-- Refer & Earn end --> 
    
    <!-- Mobile App
    ============================================= -->
    <!--section class="section pb-0">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 text-center"> <img class="img-fluid" alt="" src="images/app-mobile-2.png"> </div>
          <div class="col-lg-6 text-center text-lg-left">
            <h2 class="text-9 font-weight-600 my-4">Download Our Quickai<br class="d-none d-lg-inline-block">
              Mobile App Now</h2>
            <p class="lead text-dark">Download our app for the fastest, most convenient way to Recharge & Bill Payment, Booking and more....</p>
            <div class="pt-3"> <a href="#" class="mr-4 btn btn-outline-primary shadow-none"><i class="fab fa-apple mr-1"></i> App Store</a> <a href="#" class="mr-4 btn btn-outline-primary shadow-none"><i class="fab fa-android mr-1"></i> Play Store</a></div>
          </div>
        </div>
      </div>
    </section-->
    <!-- Mobile App end --> 









<script>
$(function() {
 'use strict';
  // Autocomplete
  $('#hotelsFrom,#flightFrom,#flightTo,#trainFrom,#trainTo,#busFrom,#busTo,#carsCity').autocomplete({
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
  // Hotels Check In Date
  $('#hotelsCheckIn').daterangepicker({
    singleDatePicker: true,
    autoApply: true,
    minDate: moment(),
    autoUpdateInput: false,
    }, function(chosen_date) {
  $('#hotelsCheckIn').val(chosen_date.format('YYYY-MM-DD'));
  });
  
  // Hotels Check Out Date
  $('#hotelsCheckOut').daterangepicker({
    singleDatePicker: true,
    autoApply: true,
    minDate: moment(),
    autoUpdateInput: false,
    }, function(chosen_date) {
  $('#hotelsCheckOut').val(chosen_date.format('YYYY-MM-DD'));
  });
  
  // Flight Depart Date
  $('#flightDepart').daterangepicker({
    singleDatePicker: true,
    autoApply: true,
    minDate: moment(),
    autoUpdateInput: false,
    }, function(chosen_date) {
  $('#flightDepart').val(chosen_date.format('YYYY-MM-DD'));
  });
  
  // Flight Return Date
  $('#flightReturn').daterangepicker({
    singleDatePicker: true,
    autoApply: true,
    minDate: moment(),
    autoUpdateInput: false,
    }, function(chosen_date) {
  $('#flightReturn').val(chosen_date.format('YYYY-MM-DD'));
  });
  
  // Train Depart Date
  $('#trainDepart').daterangepicker({
    singleDatePicker: true,
    autoApply: true,
    minDate: moment(),
    autoUpdateInput: false,
    }, function(chosen_date) {
  $('#trainDepart').val(chosen_date.format('YYYY-MM-DD'));
  });
  
  // Bus Depart Date
  $('#busDepart').daterangepicker({
    singleDatePicker: true,
    autoApply: true,
    minDate: moment(),
    autoUpdateInput: true,
    }, function(chosen_date) {
  $('#busDepart').val(chosen_date.format('MM/DD/YYYY'));
  });
  
  // Cars Pick up Date
  $('#carsPickup').daterangepicker({
    singleDatePicker: true,
    autoApply: true,
    minDate: moment(),
    autoUpdateInput: false,
    }, function(chosen_date) {
  $('#carsPickup').val(chosen_date.format('YYYY-MM-DD'));
  
  });
  
  // Cars Drop-off Date
  $('#carsDropoff').daterangepicker({
    singleDatePicker: true,
    autoApply: true,
    minDate: moment(),
    autoUpdateInput: false,
    }, function(chosen_date) {
  $('#carsDropoff').val(chosen_date.format('YYYY-MM-DD'));
  });
  
});
</script> 