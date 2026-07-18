<!-- HOME START -->

<?php 
    $rubrique = isset($rub)?$rub:'bus';
    $KTowns = isset($KTowns)?$KTowns:[];


?>
<script>let categorie = `<?php echo $rubrique; ?>`;</script>
<?php 
    $justSourceTown = false;
    if(in_array($rubrique, ['hotel', 'taxi']) )$justSourceTown = true;
?>

<!-- Carousel Start -->
<div class="header-carousel mt-0 mb-0 ">
            <div id="carouselId" class="carousel slide py-4" data-bs-ride="carousel" data-bs-interval="false">
                
                <div class="container mt-0">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-6 fadeIn wow py-4 " data-wow-delay="0.6s" >
                            <div class="rounded p-3 py-4 bg-black border-white">
                                <form>
                                    <div class="row">
                                        <div class="row mx-0">
                                            <div class="<?php echo $justSourceTown?"col":"col-6"; ?>">
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
                                            <div class="<?php echo $justSourceTown?"d-none":"col-6"; ?>">
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
                                            <div class="btn btn-primary bg-black border-white rounded-pill  py-2 px-3 mx-0 btn-recherche recherche-bus">Rechercher</div>
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
                    <h2 class="display-6 text-capitalize my-3 text-white">Liste - <?php echo ucfirst($rubrique); ?></h2>
                    
                </div>
            </div>
            <div class="row row-cols-sm-2 row-cols-md-4 row-cols-lg-5 liste-resultat">
                
            </div>
            
            
        </div>

        <div class="container-fluid py-2 my-4 image-shadow bg-black-transparent" >
            <div class="container py-2">
                <div class="text-center mx-auto pb-2 wow fadeInUp" data-wow-delay="0.5s" >
                    <h2 class="display-6 text-capitalize my-3 text-white">Résultat</h2>
                    <div class="rounded bg-white border-grey overflow-scroll" style="max-height:500px;" >
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Commande</th>
                                    <th>Status</th>
                                    <th>Prénom</th>
                                    <th>Nom</th>
                                    <th>Montant</th>
                                    <th>Dev</th>
                                    <th>Mobile M</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Date Départ</th>
                                    <th>Date Achat</th>
                                </tr>
                            </thead>
                            <tbody id="resultat_tab_body" >
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
            <div class="row row-cols-sm-2 row-cols-md-4 row-cols-lg-5 liste-resultat-details">
                
            </div>
            
            
        </div>

    
        

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary reservationModalBtn d-none" data-bs-toggle="modal" data-bs-target="#reservationModal"> <!--"#select-busseats"> -->
  Launch static backdrop modal
</button>

<!-- Modal -->
<div class="modal fade" id="reservationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-primary" id="reservationModalLabel">Filtre <span class="operator-name"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
          

          <div class="p-3 mb-3 rounded border-primary border-light bg-white">
            <div class="d-flex justify-content-center mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="check_date" >
                    <label class="form-check-label" for="check_date">
                        <h4 class="text-primary ">Date</h4>
                    </label>
                </div>
                
            </div>
            <div class="d-flex justify-content-center datepicker-cont">
                <div>
                    <div class="d-flex align-items-center my-2" ><span>Min</span> <input id="datepicker-min" class="border-grey border-light p-1 ms-3 px-3 datepicker-bootstrap" width="270" /></div> 
                    <div class="d-flex align-items-center my-2" ><span>Max</span> <input id="datepicker-max" class="border-grey border-light p-1 ms-3 px-3 datepicker-bootstrap" width="270" /></div> 
                </div>
            </div>
          </div> 

                    
          <div class="pt-3 mb-3 rounded border-primary border-light bg-white">
            <div class="d-flex justify-content-center mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="check_client" >
                    <label class="form-check-label" for="check_client">
                        <h4 class="text-primary ">Client</h4>
                    </label>
                </div>
                
            </div>
            <div class="d-flex justify-content-center flex-wrap px-3">
                
                <input id="input_client" type="text" class="form-control border-grey border-light mx-1 mb-3" aria-label="+000" />
                    
                
            </div>
          </div> 
          
                
        </div>

        <div>

        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-annuler" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary btn-acheter-billet-bus">Exécuter</button>
      </div>
    </div>
  </div>
</div>







<!-- HOME END -->

<script src="/js/marchand.js?<?php echo time(); ?>"></script>
<script src="/js/marchand-bus.js?<?php echo time(); ?>"></script>
