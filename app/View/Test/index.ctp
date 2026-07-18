<?php 
    $this->set('title_for_layout', Configure::read('Company.name').' - '.Op::translate(Configure::read('Company.slogan'), 'fr-'));

    $this->set('robots', 'all');

    $this->set('navHome', "active");

?>

<?php 
    $KTowns = isset($KTowns)?$KTowns:[];
    $STowns = isset($STowns)?$STowns:[];
?>




<!-- HOME START -->



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
                                                <select class="form-select mb-2 text-grey border-grey border-light select-country country-source" aria-label="Default select example">
                                                    <?php
                                                        foreach($KTowns as $key => $country){
                                                            echo '<option value="'.$key.'">'.$country["name"].'</option>';
                                                        }
                                                    ?>
                                                    
                                                </select>
                                                <select class="form-select mb-2 text-grey border-grey border-light select-town town-source" aria-label="Default select example">
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
                                                <select class="form-select mb-2 text-grey border-grey border-light select-country country-destination" aria-label="Default select example">
                                                    <?php
                                                        foreach($KTowns as $key => $country){
                                                            echo '<option value="'.$key.'">'.$country["name"].'</option>';
                                                        }
                                                    ?>
                                                    
                                                </select>
                                                <select class="form-select mb-2 text-grey border-grey border-light select-town town-destination" aria-label="Default select example">
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
                                        
                                        <div class="mx-0 mt-3 d-flex justify-content-center">
                                            <div class="btn btn-primary border-white rounded-pill  py-2 px-3 mx-0 btn-recherche recherche-bus">Rechercher</div>
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

    
        

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary reservationBusModalBtn d-none" data-bs-toggle="modal" data-bs-target="#reservationBusModal"> <!--"#select-busseats"> -->
  Launch static backdrop modal
</button>

<!-- Modal -->
<div class="modal fade" id="reservationBusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reservationBusModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-primary" id="reservationBusModalLabel">Réservation du Bus <span class="operator-name"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
          

          <div class="p-3 mb-3 rounded border-primary border-light bg-white">
            <div class="d-flex justify-content-center mb-2"><h4 class="text-primary ">Date</h4></div>
            <div class="d-flex justify-content-center"><input id="datepicker" class="border-grey border-light p-1 px-3" width="270" /></div>
          </div> 

          <div class="p-3 mb-3 rounded border-primary border-light bg-white">
            <div class="d-flex justify-content-center mb-2"><h4 class="text-primary ">Sièges</h4></div>
            <div class="bus-seats">
              
            </div>
          </div> 
                    
          <div class="p-3 mb-3 rounded border-primary border-light bg-white">
            <div class="d-flex justify-content-center mb-2"><h4 class="text-primary ">Mobile Money</h4></div>
            <div class="d-flex justify-content-center">
              <select id="mobile-money-prefix" class="form-select border-grey border-light me-1" aria-label="+000" style="width:6em;" >
                  <?php
                      foreach($KTowns as $key => $country){
                          echo '<option value="'.$country["mobile_code"].'">+'.$country["mobile_code"].'</option>';
                      }
                  ?>
                  
              </select>
              <input id="mobile-money-suffix" type="tel" class="border-grey border-light p-1 px-3" width="270" />
            </div>
          </div> 
          
                
        </div>

        <div>

        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-annuler" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary btn-acheter-billet-bus">Acheter</button>
      </div>
    </div>
  </div>
</div>







<!-- HOME END -->