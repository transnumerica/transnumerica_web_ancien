<?php

    $this->set('MenuDeposit', 'active');

    $this->set('LightBank', true);

?>
        <!-- Middle Panel
        ============================================= -->
    <div class="col-lg-9">
    
<div class="money-form">

    <h4 class="title">
        Dépot
    </h4>
    
    


    
      <?php 
        echo $this->Form->create(false,  array('type' => 'file', 'id' => 'deposit_form', 'novalidate' => false, 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100')))));

      ?>
      
            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Vers</label>
                <!--label for="youSend">Vous envoyez combien?</label-->
                <div class="input-group">

                  <div class="input-group-prepend"> 


                <div class="input-group country" style=" display: inherit; ">
                  <div class="input-group-append" style=" display: inherit; "> <span class="input-group-text p-0">

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

                        echo $this->Form->input('Deposit.to_banking', array('id' => 'ToBanking', 'label' => false, 'div' => false, 'data-style' => 'custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListBankings, 'empty' => false));

                    ?>


                    <!--select id="youFroamCurrency" data-style="custom-select bg-transparent border-0" data-container="body" data-live-search="true" class="selectpicker form-control bg-transparent" required="">
                      <optgroup label="Popular Currency">
                      <option data-icon="currency-flag currency-flag-usd mr-1" data-subtext="United States dollar" selected="selected" value="">USD</option>
                      <option data-icon="currency-flag currency-flag-aud mr-1" data-subtext="Australian dollar" value="">AUD</option>
                      <option data-icon="currency-flag currency-flag-inr mr-1" data-subtext="Indian rupee" value="">INR</option>
                      </optgroup>
                      <option value="" data-divider="true">divider</option>
                      <optgroup label="Other Currency">
                      <option data-icon="currency-flag currency-flag-aed mr-1" data-subtext="United Arab Emirates dirham" value="">AED</option>
                      <option data-icon="currency-flag currency-flag-ars mr-1" data-subtext="Argentine peso" value="">ARS</option>
                      <option data-icon="currency-flag currency-flag-zar mr-1" data-subtext="South African rand" value="">ZAR</option>
                      </optgroup>
                    </select-->

                    </span> </div>
                </div>

                  </div>

                  <?php
                    echo $this->Form->input('Deposit.to_amount', array('type' => 'text', 'id' => 'ToAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'ToAmount form-control', 'default' => '1.00', 'required' => '', 'error' => false,  'maxlength' => "20"));
                  ?>

                  <div class="input-group-append"> <span class="input-group-text p-0">


                    <?php

                        echo $this->Form->input('Deposit.to_currency', array('id' => 'ToCurrency', 'label' => false, 'div' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => array(), 'empty' => false));

                    ?>

                    </span>
                  </div>
                
                </div>

                <!--div>
                  <span style="clear:both" class="text-muted text-center">Envoyé à jusqu'à <span class="font-weight-500">5000 USD</span></span>
                </div-->


                  <?php

                    echo $this->Form->error('Deposit.to_amount', array('id' => 'SendAmount', 'data-bv-field' => 'youSendError', 'label' => false, 'div' => false, 'class' => 'form-control', 'required' => ''));

                  ?>

              </div>

            </div>




            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Sélectionnez une méthode d'approvisionnement</label>
                <!--label for="youSend">Vous envoyez combien?</label-->
                <div class="input-group">

                  <div class="input-group-prepend"> 


                <div class="input-group country" style=" display: inherit; ">
                  <div class="input-group-append" style=" display: inherit; "> <span class="input-group-text p-0">

                    <?php

                        echo $this->Form->input('Deposit.from_operator', array('id' => 'FromOperator', 'label' => false, 'div' => false, 'data-style' => 'custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListFromOperators, 'empty' => false));

                    ?>


                    <!--select id="youFromCurnrency" data-style="custom-select bg-transparent border-0" data-container="body" data-live-search="true" class="selectpicker form-control bg-transparent" required="">
                      <optgroup label="Popular Currency">
                      <option data-icon="currency-flag currency-flag-usd mr-1" data-subtext="United States dollar" selected="selected" value="">USD</option>
                      <option data-icon="currency-flag currency-flag-aud mr-1" data-subtext="Australian dollar" value="">AUD</option>
                      <option data-icon="currency-flag currency-flag-inr mr-1" data-subtext="Indian rupee" value="">INR</option>
                      </optgroup>
                      <option value="" data-divider="true">divider</option>
                      <optgroup label="Other Currency">
                      <option data-icon="currency-flag currency-flag-aed mr-1" data-subtext="United Arab Emirates dirham" value="">AED</option>
                      <option data-icon="currency-flag currency-flag-ars mr-1" data-subtext="Argentine peso" value="">ARS</option>
                      <option data-icon="currency-flag currency-flag-zar mr-1" data-subtext="South African rand" value="">ZAR</option>
                      </optgroup>
                    </select-->

                    </span> </div>
                </div>

                  </div>

                  <?php
                    echo $this->Form->input('Deposit.from_amount', array('type' => 'text', 'id' => 'FromAmount', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'FromAmount form-control', 'default' => '1.00', 'required' => '', 'error' => false,  'maxlength' => "20"));
                  ?>

                  <div class="input-group-append"> <span class="input-group-text p-0">


                    <?php

                        echo $this->Form->input('Deposit.from_currency', array('id' => 'FromCurrency', 'label' => false, 'div' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => array(), 'empty' => 'Chargement'));

                    ?>

                    </span>
                  </div>
                
                </div>

                <!--div>
                  <span style="clear:both" class="text-muted text-center">Envoyé à jusqu'à <span class="font-weight-500">5000 USD</span></span>
                </div-->


                  <?php
                    echo $this->Form->error('Deposit.from_amount', array('id' => 'SendAmount', 'data-bv-field' => 'youSendError', 'label' => false, 'div' => false, 'class' => 'form-control', 'required' => ''));
                  ?>

              </div>

            </div>




            <div class="col-lg-12">

                <p class="text-2 text-muted text-center livechange">Le taux de change actuel est <span class="font-weight-500"></span></p>

                <div class="form-group">
                    <div class="custom-control custom-checkbox mr-sm-2 d-inline-block">
 
                        <?php

                        //echo $this->Form->input('Deposit.from_currency', array('id' => 'customControlAutosizing', 'type' => 'checkbox', 'class' => 'selectpicker form-control bg-transparent custom-control-input paycost', 'label' => false, 'div' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'required' => '', 'default' => 1));

                        ?>
                        <!--label class="custom-control-label" for="customControlAutosizing">Je paie les frais (5.00 $ USD)</label-->
                    </div>
                    <div id="totalbalance" class="float-right">
                        <span class="text-2">Frais: <strong class="fee float-right pl-2">100.00 $ USD</strong></span><br/>
                        <span class="text-3">Total à payer: <strong class="TG float-right pl-2">100.00 $ USD</strong></span>
                    </div>
                </div>
            </div>


            <div class="col-lg-12">

              <?php echo $this->Form->button('<span>Continuer</span>', array('type' => 'submit', 'id' => 'UsernameForm', 'class' => 'btn btn-primary', 'name' => 'form', 'value' => 'newdeposit')); ?>

            </div>
        </div>
    <?php

      echo $this->Form->hidden('Transaction.ref', array('value' => 'Deposit'));
      echo $this->Form->hidden('Deposit.info_id', array('value' => $AuthUser['Info']['id']));
      echo $this->Form->hidden('Deposit.changecurrency', array('id' => 'changecurrency'));
      echo $this->Form->hidden('Deposit.fee', array('id' => 'fee'));
      echo $this->Form->hidden('Deposit.TG', array('id' => 'TG'));


       echo $this->Form->end();
    ?>
</div>

        <!-- Middle Panel End --> 




<?php
  echo $this->element('./../'.$this->viewPath.'/Element/Js/transaction');
?>