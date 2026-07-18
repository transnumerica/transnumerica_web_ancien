        <!-- Middle Panel
        ============================================= -->
<div class="col-lg-9">
      
  <div class="money-form">


    <?php

    //debug($transactionRef);

    $transactionData = ${$transactionRef};
    //debug($transactionData);


    if ($transactionRef == 'Deposit') {
      $TOperation = 'Dépot éffectué';
      $TOpera = 'Dépot';
      $this->set('MenuDeposit', 'active');
    }elseif ($transactionRef == 'Transfer') {
      $TOperation = 'Transfert éffectué';
      $TOpera = 'Transfert';
      $this->set('MenuSend', 'active');
  }


    if($this->action == 'saving_create'){
      $TOperation = 'Epargne '.$transactionData['ToBanking']['Saving']['Project']['name'].' '.$transactionData['ToBanking']['Saving']['name'].' crée';
    }


    $created = $transactionData['Transaction']['created'];
    if ($transactionData[$transactionRef]['done_date']) {
      $created = $transactionData[$transactionRef]['done_date'];
    }



    ?>


      <h4 class="title">
          <?php echo $TOperation ?>
      </h4>
       


    <div class="col-lg-12 p-3 pt-sm-4">

        <p class="text-center">
          <span class="text-4"><?php echo $TOpera ?> éffectué de <span class="font-weight-700"><?php echo $transactionData[$transactionRef]['to_amount'].' '.$transactionData['ToCurrency']['iso'] ?></span>  avec succès au compte <?php echo $transactionData['ToBanking']['Type']['name'] ?> <span class="font-weight-700"><?php echo $transactionData['ToBanking']['iban2'] ?></span>, le <?php echo ucfirst(CakeTime::format($created, '%A %d/%m/%Y').' '.CakeTime::format($created, '%H:%M:%S')) ?> sur le n° de Transaction <?php echo $transactionData['Transaction']['serial'] ?></span> 
        <?php


           $uid = 4938204589;

          if (strlen($uid) == 10) {
            $uid = substr($uid, 0, 3).'-'.substr($uid, 3, 3).'-'.substr($uid, 6, 4);
          }

          ?>

         <p class="text-center">
         <span class="text-4">Solde disponible : <span class="font-weight-700"><?php echo $transactionData['ToBanking']['balance'].' '.$transactionData['ToCurrency']['iso'] ?></span></span><br/>


         <p>


    </div>



  </div>

</div>



<?php
  echo $this->element('./../'.$this->viewPath.'/Element/Js/cardSlide');
?>

<script type="text/javascript">


  $(document).ready(function(){

    updateBankSlide('<?php echo $transactionData['ToBanking']['id'] ?>');
  })

</script>