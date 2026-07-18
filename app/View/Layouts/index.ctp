<?php 
    $this->set('title_for_layout', Configure::read('Company.name').' - '.Op::translate(Configure::read('Company.slogan'), 'fr-'));

    $this->set('robots', 'all');

    $this->set('navHome', "active");

?>

<?php 
    $KTowns = isset($KTowns)?$KTowns:[];
    $STowns = isset($STowns)?$STowns:[];
    $keySchedule = isset($keySchedule)?$keySchedule:'';
    $Schedule = isset($Schedule)?$Schedule:[];

    $Tags = isset($Tags)?$Tags:[];
    $FromTown = isset($FromTown)?$FromTown:[];
    $ToTown = isset($ToTown)?$ToTown:[];
    $mT = isset($mT)?$mT:'';
    $endTravel = isset($endTravel)?$endTravel:'';

    $authUser = isset($authUser)?$authUser:[];


?>


       <h1>TESTES</h1>

        <!-- Search Bus -->
        <div class="tab-pane fade show active" id="bus" role="tabpanel" aria-labelledby="bus-tab">

            <div class="row">
            <div class="col-12 form-group">
                <?php
                    //$ListTowns = Hash::combine($Towns, '{n}.Town.id', '{n}.Town.name');
                    echo 'KTowns : ';
                    print_r($KTowns);
                    echo $this->Form->input('Search.from_town_id', array('id' => 'busFrom', 'class' => 'form-control', 'required' => true, 'placeholder' => 'De', 'empty' => '(De)', 'options' => $STowns));
                ?>
                <!--input type="text" class="form-control" id="busFrom" required placeholder="De"-->
                <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
            <div class="col-12 form-group">
                <?php
                    //$ListTowns = Hash::combine($Towns, '{n}.Town.id', '{n}.Town.name');
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

       <h1>FIN TESTE</h1>












        <!-- Carousel Start -->
        <div class="header-carousel mt-5 image-shadow mb-5 filter-space">
            <div id="carouselId" class="carousel slide py-4" data-bs-ride="carousel" data-bs-interval="false">
                
                <div class="container mt-0">
                    <div class="row ">
                        <div class="col-lg-6 fadeIn wow py-4 " data-wow-delay="0.6s" >
                            <div class="rounded p-3 py-4 bg-primary-transparent border-white">
                                <form>
                                    <div class="row">
                                        <div class="row mx-0">
                                            <div class="col-6">
                                                <div class="d-flex justify-content-center"><h4 class="text-white-grey mb-2">Départ</h4></div>
                                                <select class="form-select mb-2 text-grey border-grey border-light select-country" aria-label="Default select example">
                                                    <?php
                                                        foreach($KTowns as $key => $country){
                                                            echo '<option value="'.$key.'">'.$country["name"].'</option>';
                                                        }
                                                    ?>
                                                    
                                                </select>
                                                <select class="form-select mb-2 text-grey border-grey border-light select-town" aria-label="Default select example">
                                                    <?php
                                                        foreach($KTowns as $country_id => $country){
                                                            foreach($country['towns'] as $town_id => $town_name){
                                                                echo '<option country_id="'.$country_id.'" value="'.$town_id.'">'.$town_name.'</option>';
                                                            } 
                                                        }
                                                    ?>
                                                   
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-center"><h4 class="text-white-grey mb-2">Destination</h4></div>
                                                <select class="form-select mb-2 text-grey border-grey border-light select-country" aria-label="Default select example">
                                                    <?php
                                                        foreach($KTowns as $key => $country){
                                                            echo '<option value="'.$key.'">'.$country["name"].'</option>';
                                                        }
                                                    ?>
                                                    
                                                </select>
                                                <select class="form-select mb-2 text-grey border-grey border-light select-town" aria-label="Default select example">
                                                    <?php
                                                        foreach($KTowns as $country_id => $country){
                                                            foreach($country['towns'] as $town_id => $town_name){
                                                                echo '<option country_id="'.$country_id.'" value="'.$town_id.'">'.$town_name.'</option>';
                                                            } 
                                                        }
                                                    ?>
                                                   
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mx-0 d-flex justify-content-center">
                                            <!--<button class="btn btn-light py-2 px-2 mx-0">Rechercher</button>-->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-6 fadeIn wow py-4 " data-wow-delay="0.6s" >
                            <div class="rounded p-3 py-4 bg-white-transparent border-grey">
                                <form>
                                    <div class="row">
                                        <div class="row mx-0">
                                            <div class="">
                                                <div class="d-flex justify-content-center"><h4 class="text-grey mb-2">Filtre</h4></div>
                                                <div class="rounded">
                                                    <div class="rounded p-2 py-3 my-3 bg-white border-white  bg-primary">
                                                        <div class="d-flex justify-content-center mb-2 reset-filtre-confort"><h4 class="d-flex flex-nowrap align-items-center text-white m-0 p-0 px-1 btn "><div class="mt-1 text-white-grey me-2  link-simple-white">Confort</div><i class="fa fa-undo me-2 link-simple-white"></i></h4></div>
                                                        
                                                        <div class="d-flex flex-wrap justify-content-center filtre-confort">
                                                           
                                                        </div>
                                                    </div>
                                                    <div class="rounded p-2 py-3 my-3 bg-white border-white  bg-primary">
                                                        <div class="d-flex justify-content-center mb-2 reset-filtre-compagnie"><h4 class="d-flex flex-nowrap align-items-center text-white m-0 p-0 px-1 btn "><div class="mt-1 text-white-grey me-2  link-simple-white">Compagnie</div><i class="fa fa-undo me-2  link-simple-white"></i></h4></div>
                                                        
                                                        <div class="d-flex flex-wrap justify-content-center filtre-compagnie">
                                                            
                                                            
                                                        </div>
                                                    </div>
                                                
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="mx-0 d-flex justify-content-center">
                                            <!--<button class="btn btn-light py-2 px-2 mx-0">Rechercher</button>-->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    </div>
                </div>

                
            </div>
        </div>
        <!-- Carousel End -->


        <div class="container-fluid py-2 my-4 image-shadow bg-black-transparent" >
            <div class="container py-2">
                <div class="text-center mx-auto pb-2 wow fadeInUp" data-wow-delay="0.5s" style="max-width: 800px;">
                    <h2 class="display-6 text-capitalize my-3 text-white">Liste des Bus</h2>
                    
                </div>
            </div>
            <div class="row row-cols-5 liste-resultat">
                
            </div>
            
            
        </div>

        <!-- Features Start -->
        <div class="container-fluid py-5 mt-4  image-shadow" style="background-color: #f2f2f2dd;">
            <div class="container py-2">
                <div class="text-center mx-auto pb-4 wow fadeInUp" data-wow-delay="0.5s" style="max-width: 800px;">
                    <h1 class="display-5 text-capitalize mb-3 text-primary" >En savoir plus </h1>
                    
                </div>
                
                <div class="wow fadeIn" data-wow-delay="0.7s">
                    <div id="carouselYoutubeControls" class="carousel slide position-relative " data-bs-interval = "false">
                        <div class="carousel-inner position-relative">
                          <div class="carousel-item active p-2 ">
                            <div class="d-flex youtube ratio ratio-16x9 mx-auto">
                                <iframe  class="rounded border-white m-0  p-0" style="" allowfullscreen
                                    src="https://www.youtube.com/embed/Qz-H20h2i2U">
                                </iframe> 
                            </div>
                            
                          </div>
                          <div class="carousel-item p-2 ">
                            <div class="d-flex youtube ratio ratio-16x9 mx-auto">
                                <iframe  class="rounded border-white m-0 p-0" style="" allowfullscreen
                                    src="https://www.youtube.com/embed/MHEj9_LMlCg">
                                </iframe> 
                            </div>
                            
                          </div>
                          <div class="carousel-item p-2 ">
                            <div class="d-flex youtube ratio ratio-16x9 mx-auto">
                                <iframe  class="rounded border-white m-0 p-0" style="" allowfullscreen
                                    src="https://www.youtube.com/embed/9stQ9uheEXo">
                                </iframe> 
                            </div>
                            
                          </div>
                          
                          
                        </div>
                        <div class="d-flex align-items-center position-absolute top-0 start-0 h-100">
                            <div class="d-flex rounded-circle bg-primary btn btn-primary p-0 transparent" data-bs-target="#carouselYoutubeControls" data-bs-slide="prev"><span class="carousel-control-prev-icon m-2" aria-hidden="true"></span></div>
                          
                        </div>
                        <div class="d-flex align-items-center position-absolute top-0 end-0 h-100">
                            <div class="d-flex rounded-circle bg-primary btn btn-primary p-0 transparent nextYoutube" data-bs-target="#carouselYoutubeControls" data-bs-slide="next"><span class="carousel-control-next-icon m-2" aria-hidden="true"></span></div>
                          
                        </div>
                      </div>
                </div>
            </div>
        </div>
        <!-- Features End -->

        

        <!-- About Start -->
        <div class="container-fluid overflow-hidden about py-5">
            <div class=" py-2 mx-2">
                <div class="wow fadeInLeft pb-4 d-flex justify-content-center" data-wow-delay="0.2s">
                    <h1 class="display-5 text-capitalize text-shadow">Planifiez Votre <span class="text-primary">Voyage</span></h1>
                </div>
                <div>
                    <div class="wow fadeIn" data-wow-delay="0.7s">
                        <div class="owl-carousel owl-theme">
                          <div class="item active">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16/9;"
                                    src="../img/town/1/4f5cf6f6df6a426b9c06eaa6018ab9d1.jpg" />
                            </div>
                          </div>
                          <div class="item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16/9;"
                                    src="../img/town/2/7854348339f9607f8f9839802d59a362.jpg" />
                            </div>
                          </div>
                          <div class="item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16/9;"
                                    src="../img/town/3/b068f82ea229edc3be483493d1901b28.jpg" />
                            </div>
                          </div> 
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/4/b161a4fd20c96483563f789428509c35.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/5/a8f3845a2cb5ac61b19680b3d1c222ae.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/6/0e325e32ce6ce1cee117c76580b6cd30.gif" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/7/d758a12c69913d35b38d74c342c09877.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/9/399bf04e1b6845a1616793e22140d585.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/10/69b06935593153645aa4cc679b5c21aa.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/11/527b92d639da1cc525e5c9e37b487f8b.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/12/a933666b3f69d605c1854cd677ee7230.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/13/bdc664f532cd630c6f9e8fb2fe3ff163.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/14/7e7a9dce9b8be3db14496ebe828f4234.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/15/e6bceb3f294c9acce4be23eb5dfd1788.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/16/a318db1d2397dac6f40b9a1cf4b2da3a.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/17/794ba7578e8ecfbf0780cc928857ce7a.jpg" />
                            </div>
                          </div>
                          
                        </div>
                        
                      </div>
                </div>




                
            </div>
        </div>
        <!-- About End -->


    
        

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary reservationBusModalBtn d-none" data-bs-toggle="modal" data-bs-target="#reservationBusModal"> <!--"#select-busseats"> -->
  Launch static backdrop modal
</button>

<!-- Modal -->
<div class="modal fade" id="reservationBusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reservationBusModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-primary" id="reservationBusModalLabel">Réservation du Bus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
          
          <div>
            <h4>Date</h4>
            <input id="datepicker" width="270" />
          </div> 
          <div>
            <h4>Sièges</h4>
            <div class="bus-seats">
              <div class="wrapper">
                <div class="container">
                <h1>jQuery Seat Charts Plugin Demo</h1>
                  <div id="seat-map">
                    <div class="front-indicator">Front</div>
                  </div>
                  <div class="booking-details">
                    <h2>Détails</h2>
                    <h3>Sièges sélectionnés (<span id="counter">0</span>):</h3>
                    <ul id="selected-seats">
                    </ul>
                    Total: <b>$<span id="total">0</span></b>
                    <button class="checkout-button">Checkout &raquo;</button>
                    <div id="legend"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>                                   
          
                
        </div>

        <div>

        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary">Acheter</button>
      </div>
    </div>
  </div>
</div>



<div id="select-busseats" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Détails de la réservation d'Autobus</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
        <div class="bus-details">
          <div class="row align-items-sm-center flex-row mb-4">
            <div class="col col-sm-3"> <span class="text-4 text-dark operator-name"></span> <span class="text-muted d-block" style=" font-weight: 500; color: #0ad50a!important; "><?php echo CakeText::toList($Tags) ?></span> </div>
            <div class="col col-sm-3 text-center time-info"> <span class="text-5 text-dark schedule_start"></span> <small class="text-muted d-block"><?php echo $FromTown['Town']['name'] ?></small> </div>
            <div class="col col-sm-3 text-center d-none d-sm-block time-info"> <span class="text-3 duration"><?php echo $mT ?></span> <small class="text-muted d-block"><span class="schedule_arrets"></span> arrêts</small> </div>
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



<!-- HOME END -->