<!-- HOME START -->

<?php 
    $KTowns = isset($KTowns)?$KTowns:[];
?>


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
                                            <div class="btn btn-primary border-white rounded-pill  py-2 px-3 mx-0 btn-recherche recherche-former">Rechercher</div>
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
                    <h2 class="display-6 text-capitalize my-3 text-white">Liste des Trains</h2>
                    
                </div>
            </div>
            <div class="row row-cols-sm-2 row-cols-md-4 row-cols-lg-5 liste-resultat">
                
            </div>
            
            
        </div>

    
        

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary reservationModalBtn d-none" data-bs-toggle="modal" data-bs-target="#reservationModal"> 
  Launch static backdrop modal
</button>

<!-- Modal -->
<div class="modal fade" id="reservationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
  <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-primary" id="reservationModalLabel">Réservation du Train <span class="operator-name"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container">
            
            <div class="p-3 mb-3 rounded border-primary border-light bg-white">
              <div class="d-flex justify-content-center mb-2"><h4 class="text-primary ">Type de billets</h4></div>
                                                        
              <div class="d-flex justify-content-center">
                <div class="btn-group mr-2 container-allee-retour-radio" role="group" aria-label="First group">
                    
                    <input type="radio" class="btn-check" name="options-type-billets" id="alle-simple-radio" autocomplete="off" checked>
                    <label class="btn btn-outline-secondary" for="alle-simple-radio">Allée Simple</label>

                    <input type="radio" class="btn-check" name="options-type-billets" id="alle-retour-radio" autocomplete="off">
                    <label class="btn btn-outline-secondary" for="alle-retour-radio">Allée Retour</label>
                   
                </div>
              </div>
            </div> 
            
            <div class="p-3 mb-3 rounded border-primary border-light bg-white">
              <div class="d-flex justify-content-center mb-2"><h4 class="text-primary ">Date Allée</h4></div>
              <div class="d-flex justify-content-center"><input id="datepicker" class="datepicker-alle border-grey border-light p-1 px-3 datepicker-bootstrap" width="270" /></div>
            </div> 
  
            <div class="container-datepicker-retour d-none p-3 mb-3 rounded border-primary border-light bg-white">
              <div class="d-flex justify-content-center mb-2"><h4 class="text-primary ">Date Retour</h4></div>
              <div class="d-flex justify-content-center"><input id="datepicker" class="datepicker-retour border-grey border-light p-1 px-3 datepicker-bootstrap" width="270" /></div>
            </div> 
  
            <div class="container-classe p-3 mb-3 rounded border-primary border-light bg-white">
              <div class="d-flex justify-content-center mb-2"><h4 class="text-primary ">Classe</h4></div>
              <div class="d-flex justify-content-center">
                <input type="radio" class="btn-check mx-2" name="options-classe" id="economic-radio" autocomplete="off" checked>
                <label class="btn btn-outline-secondary mx-2" for="economic-radio">Economic</label>

                <input type="radio" class="btn-check mx-2" name="options-classe" id="firstclass-radio" autocomplete="off">
                <label class="btn btn-outline-secondary mx-2" for="firstclass-radio">First Class</label>

              </div>
            </div> 


            <div class="p-3 mb-3 container-adult-price rounded border-primary border-light bg-white">
              <div class="d-flex justify-content-center mb-2"><h4 class="text-primary ">Adultes (+12 ans) : </h4><h4 class="text-primary adult-price"></h4></div>
              
              <div class="d-flex justify-content-center">
                <div class="btn-group mr-2 knumber-picker " role="group" aria-label="First group">
                  <button type="button" class="btn btn-secondary moins">-</button>
                  <input type="button" class="btn btn-secondary display" value="1" />
                  <button type="button" class="btn btn-secondary plus">+</button>
                </div>
              </div>
            </div> 

            <div class="p-3 mb-3 container-kid-price rounded border-primary border-light bg-white">
              <div class="d-flex justify-content-center mb-2"><h4 class="text-primary ">Enfants (2 - 12 ans) : </h4><h4 class="text-primary kid-price"></h4></div>
              
              <div class="d-flex justify-content-center">
                <div class="btn-group mr-2 knumber-picker " role="group" aria-label="First group">
                  <button type="button" class="btn btn-secondary moins">-</button>
                  <input type="button" class="btn btn-secondary display" value="0" />
                  <button type="button" class="btn btn-secondary plus">+</button>
                </div>
              </div>
            </div> 

            <div class="p-3 mb-3 container-baby-price rounded border-primary border-light bg-white">
              <div class="d-flex justify-content-center mb-2"><h4 class="text-primary ">Bébés (-2 ans) : </h4><h4 class="text-primary baby-price"></h4></div>
              
              <div class="d-flex justify-content-center">
                <div class="btn-group mr-2 knumber-picker " role="group" aria-label="First group">
                  <button type="button" class="btn btn-secondary moins">-</button>
                  <input type="button" class="btn btn-secondary display" value="0" />
                  <button type="button" class="btn btn-secondary plus">+</button>
                </div>
              </div>
            </div> 


            <div class="container-total p-3 mb-3 rounded border-primary border-light bg-white">
              <div class="d-flex justify-content-center mb-2"><h4 class="text-primary ">Réservation</h4></div>
              <div class="d-flex justify-content-center">
                <div class="d-flex"><h4>Total : </h4><h4 class="total"></h4></div>
              </div>
            </div> 

            
  
            
                      
            <div class="p-3 mb-3 rounded border-primary border-light bg-white">
              <div class="d-flex justify-content-center mb-2"><h4 class="text-primary ">Mobile Money</h4></div>
              <div class="d-flex justify-content-center flex-wrap">
                <select id="mobile-money-prefix" class="form-select border-grey border-light me-1 mb-3" aria-label="+000" style="width:6em;" >
                    <?php
                        foreach($KTowns as $key => $country){
                            echo '<option value="'.$country["mobile_code"].'">+'.$country["mobile_code"].'</option>';
                        }
                    ?>
                    
                </select>
                <input id="mobile-money-suffix" type="tel" class="border-grey border-light p-1 px-3 mb-3" width="270" />
              </div>
            </div> 
            
                  
          </div>
  
  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-annuler" data-bs-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-primary btn-acheter-billet-former">Acheter</button>
        </div>
      
      </div>
  </div>
</div>







<!-- HOME END -->

<script>
    variables = {
        service: "train",
        type: "former",
        fa: "fa-train",
        search: "searchformer",
        Plane: "Former",
        CompanyPlaneDestination: "CompanyFormerDestination",
    }
</script>
<script src="/js/main-vol.js?<?php echo time(); ?>"></script>