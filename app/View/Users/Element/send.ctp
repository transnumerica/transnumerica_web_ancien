<?php

    $this->set('MenuSend', 'active');

    $this->set('LightBank', true);


?>
        <!-- Middle Panel
        ============================================= -->
    <div class="col-lg-9">
    
<div class="money-form">

    <h4 class="title">
        Transfert
    </h4>
    
    


    
      <?php 
        echo $this->Form->create(false,  array('type' => 'file', 'id' => 'deposit_form', 'novalidate' => false, 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100')))));

      ?>
      

            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">De</label>
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

                    echo $this->Form->input('TransactionModel.from_banking', array('id' => 'FromBanking', 'label' => false, 'div' => false, 'data-style' => 'custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'notTrans selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListBankings, 'empty' => false));

                    ?>

                    </span> </div>
                </div>

                  </div>

                  <?php
                    echo $this->Form->input('TransactionModel.from_amount', array('type' => 'text', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'FromAmount form-control', 'default' => '1.00', 'required' => '', 'error' => false,  'maxlength' => "20"));
                  ?>

                  <div class="input-group-append"> <span class="input-group-text p-0">


                    <?php

                        echo $this->Form->input('TransactionModel.from_currency', array('id' => 'FromCurrency', 'label' => false, 'div' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => ' selectpicker form-control bg-transparent' , 'required' => '', 'options' => array(), 'empty' => false));

                    ?>

                    </span>
                  </div>
                
                </div>

                <!--div>
                  <span style="clear:both" class="text-muted text-center">Envoyé à jusqu'à <span class="font-weight-500">5000 USD</span></span>
                </div-->


                  <?php

                    echo $this->Form->error('TransactionModel.from_amount', array('id' => 'SendAmount', 'data-bv-field' => 'youSendError', 'label' => false, 'div' => false, 'class' => 'form-control', 'required' => ''));

                  ?>

              </div>

            </div>


            <div class="col-lg-12">

              <div class="form-group">
                <label for="youSend">Vers</label>
                <!--label for="youSend">Vous envoyez combien?</label-->


                <div class="col-lg-12">

                    <div class="form-group">
                        <?php

                            $options['Transfer'] = array('name' => 'Entre Comptes', 'value' => 'Transfer');
                            $options['Send'] = array('name' => 'Prestataire de paiement', 'value' => 'Send');

                            echo $this->Form->input('Transaction.ref', array('id' => 'Ref', 'type' => 'radio', 'class' => 'TransactionRef form-control bg-transparent custom-control-input paycost', 'label' => array('class' => 'custom-control-label'), 'div' => false,  'legend' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'required' => '', 'default' => 1, 'options' => $options, 'before' => '<div class="custom-control custom-radio mr-sm-2 d-inline-block">', 'separator' => '</div><div class="custom-control custom-radio mr-sm-2 d-inline-block">', 'after' => '</div>', 'default' => 'Transfer'));

                        ?>

                    </div>
                </div>




                <div class="input-group">

                  <div class="input-group-prepend"> 


                <div class="input-group country" style=" display: inherit; ">
                  <div class="input-group-append" style=" display: inherit; "> <span class="input-group-text p-0">

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

                        echo $this->Form->input('TransactionModel.to_banking', array('id' => 'ToBanking', 'label' => false, 'div' => false, 'data-style' => 'custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListReceivers, 'empty' => false, 'disabled' => true));



                        echo $this->Form->input('TransactionModel.to_operator', array('id' => 'ToOperator', 'label' => false, 'div' => false, 'data-style' => 'custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListToOperators, 'empty' => false, 'disabled' => true));



                    ?>



                    </span> </div>
                </div>

                  </div>

                  <?php
                    echo $this->Form->input('TransactionModel.to_amount', array('type' => 'text', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'ToAmount form-control', 'default' => '1.00', 'required' => '', 'error' => false,  'maxlength' => "20"));
                  ?>

                  <div class="input-group-append"> <span class="input-group-text p-0">


                    <?php

                        echo $this->Form->input('TransactionModel.to_currency', array('id' => 'ToCurrency', 'label' => false, 'div' => false, 'data-style' => 'sendercurrency custom-select bg-transparent border-0', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => array(), 'empty' => false));

                    ?>

                    </span>
                  </div>
                
                </div>

                <!--div>
                  <span style="clear:both" class="text-muted text-center">Envoyé à jusqu'à <span class="font-weight-500">5000 USD</span></span>
                </div-->


                  <?php

                    echo $this->Form->error('TransactionModel.from_amount', array('id' => 'SendAmount', 'data-bv-field' => 'youSendError', 'label' => false, 'div' => false, 'class' => 'form-control', 'required' => ''));

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

      echo $this->Form->hidden('TransactionModel.info_id', array('value' => $AuthUser['Info']['id']));
      echo $this->Form->hidden('TransactionModel.changecurrency', array('id' => 'changecurrency'));
      echo $this->Form->hidden('TransactionModel.fee', array('id' => 'fee'));
      echo $this->Form->hidden('TransactionModel.TG', array('id' => 'TG'));

    echo $this->Form->end(); 

    ?>

</div>



        <!-- Middle Panel End --> 



















<?php
  echo $this->element('./../'.$this->viewPath.'/Element/Js/transaction');
?>





