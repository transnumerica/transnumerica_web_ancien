<?php

    $this->set('MenuSaving', 'active');
    $this->set('LightBank', true);

    echo $this->Html->script('/js/cleave.min.js', array('once' => true));
    echo $this->Html->script('/js/cleave-phone.i18n.js', array('once' => true));

?>


        <!-- Middle Panel
        ============================================= -->
    <div class="col-lg-9">
    
<div class="money-form">

    <h4 class="title">
        Nouveau compte épargne
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
                <label for="youSend">Type de plan</label>

                  <?php

                    foreach ($SavingProjects as $Project) {

                        $ListProjectsG[] = $Project['Project']['name'];

                        $ListProjects[$Project['Project']['id']]['name'] = $Project['Project']['name'];
                        $ListProjects[$Project['Project']['id']]['value'] = $Project['Project']['id'];
                        //$ListProjects[$Project['Project']['id']]['data-icon'] = $Project['Project']['symbol'];
                        //$ListProjects[$Project['Project']['id']]['data-subtext'] = $Project['Project']['speed'];

                    }

                    echo $this->Form->input('Saving.project_id', array('id' => 'SenderCountry', 'label' => false, 'div' => false, 'data-style' => 'custom-select', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListProjects, 'empty' => 'Séléctionner un plan'));
                  ?>

              </div>

            </div>



            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Nom du plan</label>

                  <?php
                    echo $this->Form->input('Saving.name', array('type' => 'text', 'id' => 'SendAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'default' => '', 'required' => '',  'placeholder' => 'Naissance de Patrick, voyage à dubai...'));
                  ?>

              </div>

            </div>
      


            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Photo</label>

                  <?php
                    echo $this->Form->input('Saving.cover_thumb', array('type' => 'file', 'id' => 'SendAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'default' => '', 'required' => ''));
                  ?>

              </div>

            </div>




            <div class="col-lg-12">

                <h6>Objectif</h6>
                <hr>

            </div>



            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Montant à atteindre</label>
                <div class="input-group">

                  <div class="input-group-prepend"> 


                <div class="input-group country" style=" display: inherit; ">
                    <div class="input-group-append" style=" display: inherit; "> <span class="input-group-text p-0"></span>
                    </div>
                </div>

                  </div>

                  <?php
                    echo $this->Form->input('Saving.target_amount', array('type' => 'text', 'id' => 'TargetAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'Amount form-control', 'default' => '2000.00', 'required' => '', 'error' => false,  'maxlength' => "20"));
                  ?>

                  <div class="input-group-append"> <span class="input-group-text p-0">


                    <?php

                        echo $this->Form->input('Banking.currency_id', array('id' => 'ToCurrency', 'label' => false, 'div' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListCurrencies));

                    ?>

                    </span>
                  </div>
                
                </div>

                  <?php

                    echo $this->Form->error('Saving.target_amount', array('data-bv-field' => 'youSendError', 'label' => false, 'div' => false, 'class' => 'form-control', 'required' => ''));

                  ?>


              </div>

            </div>



            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Date echéante</label>

                  <?php
                    echo $this->Form->date('Saving.target_date', array('id' => 'TargetDate', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'default' => date('Y-m-d', strtotime('+2 month')), 'required' => ''));
                  ?>

              </div>

            </div>



            <div class="col-lg-12">

                <p class="text-2 text-muted text-center livetargetdate"><span class="font-weight-500"></span></p>

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
                    echo $this->Form->input('TransactionModel.to_amount', array('type' => 'text', 'id' => 'firstAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'ToAmount form-control', 'default' => '1.00', 'required' => '', 'error' => false,  'maxlength' => "20"));
                  ?>

                  <div class="input-group-append"> <span class="input-group-text p-0">


                    <?php

                        echo $this->Form->input('TransactionModel.to_currency', array('label' => false, 'div' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'SameCurrency selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListCurrencies, 'disabled' => true));

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


                <p class="text-2 text-muted text-center livefirst">Minimum autorisé <span class="livefirstminamount"></span> <span class="livecurrency"></span><span class="font-weight-500"></span></p>

            </div>







































            <div class="col-lg-12">

              <h6>Dépot automatique</h6>
              <hr>

            </div>


            <div class="col-lg-12">
                <div class="form-group">
                    <div class="custom-control custom-checkbox mr-sm-2 d-inline-block">
 
                        <?php

                        echo $this->Form->input('Saving.autodeposit', array('id' => 'autoDeposit', 'type' => 'checkbox', 'class' => 'selectpicker form-control bg-transparent custom-control-input paycost', 'label' => false, 'div' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'required' => '', 'default' => 1));

                        ?>
                        <label class="custom-control-label" for="autoDeposit">Dépot automatique</label>
                    </div>

                </div>
            </div>



            <div class="autoDepositDiv" style="display: contents; width: 100%;">


            <div class="col-lg-12">

              <?php

                $ListFrequences[1] = 'Jour';
                $ListFrequences[7] = 'Semaine';
                $ListFrequences[30] = 'Mois';
                $ListFrequences[365] = 'Année';

              ?>
              <div class="form-group">
                <label for="youSend">Periodicité</label>
                <div class="input-group">

                  <div class="input-group-prepend"> 


                <div class="input-group country" style=" display: inherit; ">
                    <div class="input-group-append" style=" display: inherit; "> <span class="input-group-text p-0"></span>
                    </div>
                </div>

                  </div>

                  <?php
                    echo $this->Form->input('Saving.autodeposit_average', array('type' => 'text', 'id' => 'autoDepositAverage', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'default' => '1', 'required' => '', 'error' => false,  'maxlength' => "20"));
                  ?>

                  <div class="input-group-append"> <span class="input-group-text p-0">


                    <?php

                        echo $this->Form->input('Saving.autodeposit_schedule', array('id' => 'ScheduleInterval', 'label' => false, 'div' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListFrequences, 'empty' => false));

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
                <label for="youSend">Montant</label>
                <div class="input-group">

                  <div class="input-group-prepend"> 


                <div class="input-group country" style=" display: inherit; ">
                    <div class="input-group-append" style=" display: inherit; "> <span class="input-group-text p-0"></span>
                    </div>
                </div>

                  </div>

                  <?php
                    echo $this->Form->input('Saving.autodeposit_amount', array('type' => 'text', 'id' => 'autoAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'default' => '1.00', 'required' => '', 'error' => false,  'maxlength' => "20"));
                  ?>

                  <div class="input-group-append"> <span class="input-group-text p-0">


                    <?php

                        echo $this->Form->input('Saving.autodeposit_currency', array('label' => false, 'div' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'SameCurrency selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListCurrencies, 'disabled' => true));

                    ?>

                    </span>
                  </div>
                
                </div>

                <!--div>
                  <span style="clear:both" class="text-muted text-center">Envoyé à jusqu'à <span class="font-weight-500">5000 USD</span></span>
                </div-->


                  <?php

                    echo $this->Form->error('Saving.autodeposit_amount', array('id' => 'SendAmount', 'data-bv-field' => 'youSendError', 'label' => false, 'div' => false, 'class' => 'form-control', 'required' => ''));

                  ?>

              </div>

            </div>








            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">A partir du compte</label>

                  <?php

                    foreach ($AuthUser['Banking'] as $BankingType => $Bankings) {

                        foreach ($Bankings as $Banking) {
                            if ($Banking[$BankingType]) {

                                $ListBankings[$Banking['Banking']['id']]['name'] = $Banking[$BankingType]['fullname'].'_'.$Banking['Banking']['iban2'].'_'.$Banking['Type']['name'].'_'.$Banking['Currency']['symbol'];
                                $ListBankings[$Banking['Banking']['id']]['value'] = $Banking['Banking']['id'];
                                //$ListBankings[$Banking['Banking']['id']]['data-icon'] = $Banking['Banking']['symbol'];
                                //$ListBankings[$Banking['Banking']['id']]['data-subtext'] = $Banking[$BankingType]['ref'];

                            }
                        }
                    }

                    echo $this->Form->input('Saving.autodeposit_banking', array('id' => 'SenderCountry', 'label' => false, 'div' => false, 'data-style' => 'custom-select', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListBankings, 'empty' => 'Séléctionner un compte'));
                  ?>

              </div>

            </div>




            <div class="col-lg-12">

                    <!--span class="liveautoallleft"></span> dépot restant (1 premier depot + <span class="liveautoleft"></span> dépot automatique)<br/-->

                <p class="text-2">

                    <span class="liveautoleft"></span> dépot(s) automatique(s) planifié(s)<br/>

                    Pour pouvoir atteindre le Montant souhaité au temps imparti<br/>Vous devez prélever environ <span class="liveautoamount"></span> $ USD par dépot<br/><span class="font-weight-500">Le prélèvement automatique n'est applicable le jour de l'ouverture du compte épargne</span>
                </p>

            </div> 



            </div>















            <div class="col-lg-12">

                <p class="text-2 text-muted text-center">
                    Montant Total: <span class="targetVal"></span> <span class="livecurrency"></span><br>
                    Premier Depot: <span class="firstDepositVal"></span> <span class="livecurrency"></span><br>
                    Montant Restant: <span class="leftVal"></span> <span class="livecurrency"></span><br>

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


      echo $this->Form->hidden('Banking.ref', array('value' => 'Saving'));
      echo $this->Form->hidden('Banking.info_id', array('value' => $AuthUser['Info']['id']));


      echo $this->Form->hidden('Banking.id');
      echo $this->Form->hidden('Saving.id');




    echo $this->Form->end(); 

    ?>
</div>








<?php
  echo $this->element('./../'.$this->viewPath.'/Element/Js/transaction', array('ListCurrencies' => $ListCurrencies));
  extract($this->viewVars);

  //debug($ListCurrencies);
  //exit;

?>



<script type="text/javascript">

    function timeAgo(input) {
      inputNow = '<?php echo date('Y-m-d') ?>';
      const dateNow = (inputNow instanceof Date) ? inputNow : new Date(inputNow);

      const date = (input instanceof Date) ? input : new Date(input);
      const formatter = new Intl.RelativeTimeFormat('<?php echo Op::language()[0] ?>');
      const ranges = {
        //years: 3600 * 24 * 365,
        //months: 3600 * 24 * 30,
        //weeks: 3600 * 24 * 7,
        days: 3600 * 24,
        hours: 3600,
        minutes: 60,
        seconds: 1
      };

      //const secondsElapsed = (date.getTime() - Date.now()) / 1000;
      const secondsElapsed = (date.getTime() - dateNow.getTime()+1) / 1000;
      for (let key in ranges) {
        if (ranges[key] < Math.abs(secondsElapsed)) {
          const delta = secondsElapsed / ranges[key];
          firstDay = Math.round(delta);
          return formatter.format(Math.round(delta), key);
        }
      }
    }


    function timeAgo2(input) {
      inputNow = '<?php echo date('Y-m-d') ?>';
      const dateNow = (inputNow instanceof Date) ? inputNow : new Date(inputNow);

      const date = (input instanceof Date) ? input : new Date(input);
      const formatter = new Intl.RelativeTimeFormat('<?php echo Op::language()[0] ?>');
      const ranges = {
        months: 3600 * 24 * 365.25 / 12,
      };

      //const secondsElapsed = (date.getTime() - Date.now()) / 1000;
      const secondsElapsed = (date.getTime() - dateNow.getTime()) / 1000;
      for (let key in ranges) {
        if (ranges[key] < Math.abs(secondsElapsed)) {
          const delta = secondsElapsed / ranges[key];
          return formatter.format(Math.round((delta*10))/10, key);
        }
      }
    }


    var firstDay;


    function livetargetdate(date, TargetAmountVal) {

        date = $("#TargetDate").val();

        if (date > '<?php echo date('Y-m-d') ?>') {

            livetargetdateAgo = timeAgo(date);
            livetargetdateAgo2 = timeAgo2(date);

            livetargetdateAgoName = livetargetdateAgo;
            if (livetargetdateAgo2) {
                livetargetdateAgo2 = livetargetdateAgo2.replace('dans ', '');
                livetargetdateAgo2 = livetargetdateAgo2.replace('in ', '');
                livetargetdateAgoName = livetargetdateAgoName + ' (environ '+ livetargetdateAgo2 + ')'
            }

            $('.livetargetdate').html(livetargetdateAgoName);

        }else{

            livetargetdateAgo = 0;

            $('.livetargetdate').html("Veuillez selectionner une date positif à la date d'aujourdhui");

        }

        firstMin();
        liveautoDepositAverage();

    }


    $("#TargetDate").live('change', function (e) {
        //livetargetdate(this.value);
        livetargetdate();
    });

    $("#TargetAmount").live('change', function (e) {
        //livetargetdate(this.value);
        livetargetdate();
    });


    livetargetdate();




    function liveAutoDeposit (val) {
        if(val){
            $(".autoDepositDiv").css('display', 'contents');
            $(".autoDepositDiv").css('width', '100%');
        }else{
            $(".autoDepositDiv").css('display', 'none');
        }
    }


    $("#autoDeposit").live('change', function (e) {
        liveAutoDeposit(this.checked);
    });

    liveAutoDeposit($("#autoDeposit")[0].checked);



    var firstAmountBest;

    function bestFirstAmount() {
 
       autoDepositAverageVal = filterFloat($("#autoDepositAverage").val());
      ScheduleIntervalVal = filterFloat($("#ScheduleInterval").val());

      depositLeft = Math.round((firstDay/autoDepositAverageVal/ScheduleIntervalVal));

      TargetAmountVal = filterFloat($("#TargetAmount").val());


      ToAmountValDecimal = 2;
      if (ListCurrencies[ToCurrencyID]) {
        ToAmountValDecimal = ListCurrencies[ToCurrencyID]['decimal'];
      }

      a = parseFloat(((TargetAmountVal/(depositLeft+1))+0.004999).toFixed(ToAmountValDecimal));
      b = parseFloat((a*depositLeft).toFixed(ToAmountValDecimal));
      firstAmountBest = parseFloat((TargetAmountVal - b).toFixed(ToAmountValDecimal));
      if (firstAmountBest < 0) {
        firstAmountBest = 0;
      }

      firstAmountBestText = formatMoney(firstAmountBest, ToAmountValDecimal, ' ');

      $(".ToAmount").val(firstAmountBestText);
    }


    function firstMin(){


        TargetAmountVal = filterFloat($("#TargetAmount").val());

        ToAmountValDecimal = 2;
        if (ListCurrencies[ToCurrencyID]) {
          ToAmountValDecimal = ListCurrencies[ToCurrencyID]['decimal'];
        }

        firstAmountMin = parseFloat(((TargetAmountVal*60/100/firstDay)+0.004999).toFixed(ToAmountValDecimal));

        firstAmountMinText = formatMoney(firstAmountMin, ToAmountValDecimal, ' ');

        $(".livefirstminamount").html(firstAmountMinText);

        if (firstAmountBest < firstAmountMin) {
          bestFirstAmount();
        }

    }





    function liveautoDepositAverage(call){

        autoDepositAverageVal = filterFloat($("#autoDepositAverage").val());
        ScheduleIntervalVal = filterFloat($("#ScheduleInterval").val());

        ToAmountValDecimal = 2;
        if (ListCurrencies[ToCurrencyID]) {
          ToAmountValDecimal = ListCurrencies[ToCurrencyID]['decimal'];
        }

        depositLeft = Math.round((firstDay/autoDepositAverageVal/ScheduleIntervalVal)-0.004999);
        $(".liveautoallleft").html(parseFloat(((depositLeft+1)).toFixed(ToAmountValDecimal)));
        $(".liveautoleft").html(parseFloat(((depositLeft)).toFixed(ToAmountValDecimal)));

        TargetAmountVal = filterFloat($("#TargetAmount").val());



        //console.log(TargetAmountVal);

        $(".liveautoamount").html(parseFloat((((TargetAmountVal-firstAmountBest)/depositLeft)+0.004999).toFixed(ToAmountValDecimal)));


        ftl = parseFloat((((TargetAmountVal-firstAmountBest)/depositLeft)+0.004999).toFixed(ToAmountValDecimal));

        if (ftl < 0) {
          ftl = 0;
        }

        firstAmountMoyText = formatMoney(ftl, ToAmountValDecimal, ' ');


       $("#autoAmount").val(firstAmountMoyText);

        $(".targetVal").html(formatMoney(TargetAmountVal, ToAmountValDecimal, ' '));
        $(".firstDepositVal").html(formatMoney(firstAmountBest, ToAmountValDecimal, ' '));
        $(".leftVal").html(formatMoney(TargetAmountVal-firstAmountBest, ToAmountValDecimal, ' '));

        if (!call) {
            bestFirstAmount();
            updateAmountTo(true);
            //livefirstAmountChange();
        }


    }

    $("#autoDepositAverage").live('change keyup', function (e) {
        liveautoDepositAverage();
    });

    $("#ScheduleInterval").live('change', function (e) {
        liveautoDepositAverage();
    });


    liveautoDepositAverage();




    function livefirstAmountChange(){
        $(".livefirstamount").html($("#firstAmount").val());
        liveautoDepositAverage(true);
    }
    
    $("#firstAmount").live('change keyup', function (e) {

      firstAmountBest = filterFloat(this.value);
      livefirstAmountChange();

    });

    
    livefirstAmountChange();



  var ListCurrencies;
  ListCurrencies = <?php echo json_encode($ListCurrencies) ?>;


  function updateAllCurrencies(){

    ToCurrencyVal = $("#ToCurrency").val()

    $('.SameCurrency').val(ToCurrencyVal);
    $('.SameCurrency').selectpicker('refresh');

    setTimeout(function(){
      $("#FromCurrency").trigger('change');
      $("#TargetAmount").trigger('change');
    }, 0);


    $('.livecurrency').html(ListCurrencies[ToCurrencyVal].name);

  }



  $("#ToCurrency").live('change', function (e) {
      updateAllCurrencies();
  });

  $(document).ready(function(){
    updateAllCurrencies();
  });



</script>

        <!-- Middle Panel End --> 
