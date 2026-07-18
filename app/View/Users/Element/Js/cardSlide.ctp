<?php

  $i = 0;
  foreach ($AuthUser['Banking'] as $BankingType => $Bankings) {

    foreach ($Bankings as $Banking) {
      if ($Banking[$BankingType]) {

        $BankingSlide[$Banking['Banking']['id']] = $i;

        $i ++;

      }
    }
  }



?>


<script type="text/javascript">

  var BankingSlide = <?php echo json_encode($BankingSlide) ?>;
  function updateBankSlide(bankId){

    owlTo = BankingSlide[bankId];

    //console.log(owlTo);

    if($(".owl-item").length){    
      $(".owl-item.active").removeClass("active");    
      $(".owl-item:nth-child("+(owlTo+1)+")").addClass("active ");

      $('.owl-carousel').trigger('to.owl.carousel', owlTo);
    }

  }

</script>