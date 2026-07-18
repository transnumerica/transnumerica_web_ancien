<?php

    $this->set('MenuBwakisa', 'active');
    $this->set('LightBank', true);

    echo $this->Html->script('/js/cleave.min.js', array('once' => true));
    echo $this->Html->script('/js/cleave-phone.i18n.js', array('once' => true));

?>


        <!-- Middle Panel
        ============================================= -->
    <div class="col-lg-9">
    
<div class="money-form">

    <h4 class="title">
        Nouveau compte Bwakisa Carte
    </h4>
    
    


    
      <?php 

        foreach ($AuthUser['Country']['Currency'] as $Currency) {

          $ListCurrencies[$Currency['id']] = array('name' => $Currency['iso'], 'value' => $Currency['id'], 'data-subtext' => $Currency['name']);

        }

        echo $this->Form->create(false,  array('type' => 'file', 'id' => 'deposit_form', 'novalidate' => false, 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100')))));

      ?>



            <div class="col-lg-12">

                <h6>Information</h6>
                <hr>

            </div>


          <div class="row">

            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Nom du plan</label>

                  <?php
                    echo $this->Form->input('Bwakisa.name', array('type' => 'text', 'id' => 'SendAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'default' => '', 'required' => '',  'placeholder' => 'Naissance de Patrick, voyage à dubai...'));
                  ?>

              </div>

            </div>
      


            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Photo</label>

                  <?php
                    echo $this->Form->input('Bwakisa.cover_thumb', array('type' => 'file', 'id' => 'SendAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'default' => '', 'required' => ''));
                  ?>

              </div>

            </div>




            <div class="col-lg-12">

              <h6>Premier dépot</h6>
              <hr>

            </div>




            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Montant</label>
                <div class="input-group">

                  <div class="input-group-prepend"> 


                <div class="input-group country" style=" display: inherit; ">
                    <div class="input-group-append" style=" display: inherit; "> <span class="input-group-text p-0"></span>
                    </div>
                </div>

                  </div>

                  <?php
                    echo $this->Form->input('TransactionModel.to_amount', array('type' => 'text', 'id' => 'ToAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'ToAmount form-control', 'default' => '1.00', 'required' => '', 'error' => false,  'maxlength' => "20"));
                  ?>

                  <div class="input-group-append"> <span class="input-group-text p-0">


                    <?php

                        echo $this->Form->input('Banking.currency_id', array('id' => 'ToCurrency', 'label' => false, 'div' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'SameCurrency selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListCurrencies, 'default' => '1.00'));

                    ?>

                    </span>
                  </div>
                
                </div>

                <!--div>
                  <span style="clear:both" class="text-muted text-center">Envoyé à jusqu'à <span class="font-weight-500">5000 USD</span></span>
                </div-->


              </div>

            </div>












            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Via</label>

                <div class="col-lg-12">

                    <div class="form-group">
                        <?php

                            $options['Deposit'] = array('name' => 'Prestataire de paiement', 'value' => 'Deposit');
                            $options['Transfer'] = array('name' => 'Entre Comptes', 'value' => 'Transfer');

                            echo $this->Form->input('Transaction.ref', array('id' => 'Ref', 'type' => 'radio', 'class' => 'TransactionRef form-control bg-transparent custom-control-input paycost', 'label' => array('class' => 'custom-control-label'), 'div' => false,  'legend' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'required' => '', 'default' => 1, 'options' => $options, 'before' => '<div class="custom-control custom-radio mr-sm-2 d-inline-block">', 'separator' => '</div><div class="custom-control custom-radio mr-sm-2 d-inline-block">', 'after' => '</div>', 'default' => 'Deposit'));

                        ?>

                    </div>
                </div>


                <div class="input-group">

                  <div class="input-group-prepend"> 


                <div class="input-group country" style=" display: inherit; ">
                    <div class="input-group-append" style=" display: inherit; "> <span class="input-group-text p-0"></span>
                    </div>
                </div>

                  </div>

                    <?php

                          $ListReceivers = array();

                          foreach ($AuthUser['Banking'] as $BankingType => $Bankings) {

                              foreach ($Bankings as $Banking) {
                                  if ($Banking[$BankingType]) {

                                      $ListReceivers[$Banking['Banking']['id']]['name'] = $Banking[$BankingType]['fullname'].'_'.$Banking['Banking']['iban2'].'_'.$Banking['Type']['name'].'_'.$Banking['Currency']['symbol'];

                                      $ListReceivers[$Banking['Banking']['id']]['value'] = $Banking['Banking']['id'];

                                  }
                              }
                          }


                        echo $this->Form->input('TransactionModel.from_banking', array('id' => 'FromBanking', 'label' => false, 'div' => false, 'data-style' => 'custom-select', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListReceivers, 'empty' => false, 'disabled' => true));


                        echo $this->Form->input('TransactionModel.from_operator', array('id' => 'FromOperator', 'label' => false, 'div' => false, 'data-style' => 'custom-select', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListFromOperators, 'empty' => false, 'disabled' => true));


                    ?>

                  <div class="input-group-append"> <span class="input-group-text p-0">


                    <?php

                        echo $this->Form->input('TransactionModel.from_currency', array('id' => 'FromCurrency', 'div' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'SameCurrency selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListCurrencies, 'disabled' => false));

                    ?>

                    </span>
                  </div>
                
                </div>

                <!--div>
                  <span style="clear:both" class="text-muted text-center">Envoyé à jusqu'à <span class="font-weight-500">5000 USD</span></span>
                </div-->


              </div>

            </div>




            <div class="col-lg-12">

                <p class="text-2 text-muted text-center livechange" style="display: none;">Le taux de change actuel est <span class="font-weight-500"></span></p>



            </div>





            <div class="col-lg-12">

                    <!--span class="liveautoallleft"></span> dépot restant (1 premier depot + <span class="liveautoleft"></span> dépot automatique)<br/-->

                <p class="text-2">
                    <span class="liveautoleft"></span><br/>
                </p>

            </div> 






























































            <div class="col-lg-12">
                <div class="form-group">
                    <div class="custom-control custom-checkbox mr-sm-2 d-inline-block">
 
                    </div>

                    <div id="totalbalance" class="float-right">
                        <span class="text-2">Frais: <strong class="fee float-right pl-2"></strong></span><br>
                        <span class="text-3">Total à payer: <strong class="TG float-right pl-2"></strong></span>

                        <!--strong><span class="livefirstamount"></span> <span class="livecurrency"></span></strong-->

                    </div>

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

            <div class="col-lg-12">

                <?php echo $this->Form->button('<span>Confirmer</span>', array('type' => 'submit', 'id' => 'UsernameForm', 'class' => 'btn btn-primary', 'name' => 'form', 'value' => 'newdeposit')); ?>


            </div>

          </div>


      </div>
    <?php 

      echo $this->Form->hidden('TransactionModel.from_amount', array('class' => 'FromAmount')); 


      echo $this->Form->hidden('TransactionModel.info_id', array('value' => $AuthUser['Info']['id']));
      echo $this->Form->hidden('TransactionModel.changecurrency', array('id' => 'changecurrency'));
      echo $this->Form->hidden('TransactionModel.fee', array('id' => 'fee'));
      echo $this->Form->hidden('TransactionModel.TG', array('id' => 'TG'));


      echo $this->Form->hidden('Banking.ref', array('value' => 'Bwakisa'));
      echo $this->Form->hidden('Banking.info_id', array('value' => $AuthUser['Info']['id']));


      echo $this->Form->hidden('Banking.id');
      echo $this->Form->hidden('Bwakisa.id');




    echo $this->Form->end(); 

    ?>
</div>








<?php
  echo $this->element('./../'.$this->viewPath.'/Element/Js/transaction', array('ListCurrencies' => $ListCurrencies));
  extract($this->viewVars);

  //debug($ListCurrencies);
  //exit;

?>

