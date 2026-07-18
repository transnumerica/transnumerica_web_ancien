<?php

    $this->set('MenuSaving', 'active');

?>
        <!-- Middle Panel
        ============================================= -->
    <div class="col-lg-9">
    
<div class="money-form">

    <h4 class="title">
        Nouveau compte Courant
    </h4>
    
    


    
      <?php 
        echo $this->Form->create(false,  array('type' => 'file', 'id' => 'deposit_form', 'novalidate' => false, 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100')))));

        echo $this->Form->hidden('Transaction.id');

        echo $this->Form->hidden('Transfer.id');
      ?>





          <div class="row">

            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Type de plan</label>

                  <?php

                    foreach ($SavingProjects as $Project) {

                        $ListProjectsG[] = $Project['Project']['name'];

                        $ListProjects[$Project['Project']['id']]['name'] = $Project['Project']['name'];
                        $ListProjects[$Project['Project']['id']]['value'] = $Project['Project']['id'];
                        //$ListProjects[$Project['Project']['id']]['data-icon'] = $Project['Project']['symbol'];
                        //$ListProjects[$Project['Project']['id']]['data-subtext'] = $Project['Project']['speed'];

                    }

                    echo $this->Form->input('Transfer.from_country', array('id' => 'SenderCountry', 'label' => false, 'div' => false, 'data-style' => 'custom-select', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListProjects, 'empty' => 'Séléctionner un plan'));
                  ?>

              </div>

            </div>



            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Nom du plan</label>

                  <?php
                    echo $this->Form->input('Transfer.from_amount', array('type' => 'text', 'id' => 'SendAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'default' => '', 'required' => '',  'placeholder' => CakeText::toList($ListProjectsG, 'ou').'...'));
                  ?>

              </div>

            </div>
      



            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Fréquence</label>

                  <?php

                    $ListFrequences[] = 'Chaque Année';
                    $ListFrequences[] = 'Chaque Semestre';
                    $ListFrequences[] = 'Chaque Trimestre';
                    $ListFrequences[] = 'Chaque Mois';
                    $ListFrequences[] = 'Chaque Semaine';
                    $ListFrequences[] = 'Chaque Journalier';
                    $ListFrequences[] = 'Personnalisé';

                    echo $this->Form->input('Transfer.from_country', array('id' => 'SenderCountry', 'label' => false, 'div' => false, 'data-style' => 'custom-select', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListFrequences, 'empty' => 'Séléctionner un plan'));
                  ?>

              </div>

            </div>





            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Montant</label>

                  <?php
                    echo $this->Form->input('Transfer.from_amount', array('type' => 'text', 'id' => 'SendAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'default' => '', 'required' => '',  'placeholder' => CakeText::toList($ListProjectsG, 'ou').'...'));
                  ?>

              </div>

            </div>
      



            <div class="col-lg-12">

              <h6>Periode d'investissement</h6>
              <hr>

            </div>



            <div class="col-lg-6">

              <div class="form-group">
                <label for="youSend">Début</label>

                  <?php
                    echo $this->Form->date('Transfer.from_amount', array('id' => 'SendAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'default' => date('Y-m-d'), 'required' => ''));
                  ?>

              </div>

            </div>
      

            <div class="col-lg-6">

              <div class="form-group">
                <label for="youSend">Fin</label>

                  <?php
                    echo $this->Form->date('Transfer.from_amount', array('id' => 'SendAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'default' => date('Y-m-d', strtotime('+4 month')), 'required' => ''));
                  ?>

              </div>

            </div>



            <!--div class="col-lg-12">
                <div class="form-group">
                    <label for="payment-method"  class="bmd-label-floating" >Select your Payment method</label>
                    <select class="form-control paymentmethod1" name="paymentmethod" data-style="btn btn-link" id="paymentmethod1">
                        <option selected disabled>Select Method</option>
                                                    <option value="Stripe">Stripe</option>
                                                    <option value="Paypal">Paypal</option>
                                                    <option value="Paytm">Paytm</option>
                                                    <option value="Mollie Payment">Mollie Payment</option>
                                                    <option value="Paystack">Paystack</option>
                                            </select>

                                    </div>
            </div-->

            <!--div class="col-lg-12 d-none string-show">
                <div class="col-lg-12">
                    <h5>
                        Select Credit Card:
                    </h5>
                </div>
                <div class="col-lg-12">
                                    </div>

                <div class="col-lg-12">
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" id="customRadio0" name="card_id" class="custom-control-input card-check" value="0">
                        <label class="custom-control-label" for="customRadio0"> Add New Card</label>
                    </div>
                </div>

                <div class="col-lg-12 d-none card-show">
                    <div class="border p-3 mt-3">
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="card_number">Card Number</label>
                                    <input type="text" class="form-control card-elements" name="cardNumber" id="validateCard" placeholder="Card Number" autocomplete="off">
                                    <span id="errCard" class="text-danger"></span>
                                                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cardCVC">Cvc</label>
                                    <input type="text" class="form-control card-elements" id="validateCVC"  placeholder="Cvc" name="cardCVC" >
                                    <span id="errCVC text-danger"></span>
                                                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Month</label>
                                    <input type="text" class="form-control card-elements" id="" placeholder="Month" name="month">
                                                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Year</label>
                                    <input type="text" class="form-control card-elements" id="" placeholder="Year" name="year">
                                                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="checkbox" id="cardsave" name="isCheckCardSave" value="1">
                                    <label for="cardsave"> Save for future deposit?</label><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div-->

        <input type="hidden" name="email" value="a@a.fr">
        <input type="hidden" name="ref_id" id="ref_id" value="">
        <input type="hidden" name="sub" id="sub" value="0">
        
        <input type="hidden" name="paystackInfo" id="paystackInfo" value="pk_test_162a56d42131cbb01932ed0d2c48f9cb99d8e8e2">
 

            <div class="col-lg-12 " id="methodinputbox">
            </div>


          <!-- Add New Card Details Modal
          ================================== -->
        <style type="text/css">

        /*
        #add-new-card-details{
            display: block!important;
            opacity: 1;
        }

        */

        </style>


          <div id="add-new-card-details" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-400">Résumé</h5>
                        <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                    </div>
                    <div class="modal-body p-4">

                        <p class="text-3 font-weight-500">En construction</p>


                        <?php echo $this->Form->button('<span>Payer</span>', array('type' => 'submit', 'id' => 'UsernameForm', 'class' => 'btn btn-primary', 'name' => 'form', 'value' => 'stage3')); ?>


                    </div>
                </div>
            </div>
          </div>
          <!-- Credit or Debit Cards End --> 



            <div class="col-lg-12">

                <button type="button" id="final-btn" class="btn btn-primary" data-target="#add-new-card-details" data-toggle="modal">Continuer</button>

            </div>

          </div>


      </div>
    <?php echo $this->Form->end(); ?>
</div>



        <!-- Middle Panel End --> 
