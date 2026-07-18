<?php

  echo $this->Html->script('/js/cleave.min.js', array('once' => true));
  echo $this->Html->script('/js/cleave-phone.i18n.js', array('once' => true));

  $i = 0;
  foreach ($AuthUser['Banking'] as $BankingType => $Bankings) {

    foreach ($Bankings as $Banking) {
      if ($Banking[$BankingType]) {

        $ListBankingCurrencies[$Banking['Banking']['id']] = array('id' => $Banking['Currency']['id'], 'iso' => $Banking['Currency']['iso'], 'name' => $Banking['Currency']['name']);

        $ListFormCurrencies[$Banking['Banking']['id']] = $Banking['Currency']['id'];


        $ListCurrencies[$Banking['Currency']['id']] = array('name' => $Banking['Currency']['iso'], 'value' => $Banking['Currency']['id'], 'data-subtext' => $Banking['Currency']['name'], 'decimal' => ($Banking['Currency']['decimal'] ? 2 : 0));


        $i ++;

      }
    }
  }


  $this->set('ListCurrencies', $ListCurrencies);


?>





<?php
  echo $this->element('./../'.$this->viewPath.'/Element/Js/cardSlide');
?>



<script type="text/javascript">
  
  var ListFormCurrencies;

  var listChanges;
  var ListBankingCurrencies;

  //console.log(ListFromCurrencies);
  ListCurrencies = <?php echo json_encode($ListCurrencies) ?>;

  var ListFromCurrencies;
  ListFromCurrencies = <?php echo json_encode($ListFromCurrencies) ?>;


  var ListFromCurrencies;
  ListToCurrencies = <?php echo json_encode($ListToCurrencies) ?>;

  var ListFromOperatorCurrencies;
  ListFromOperatorCurrencies = <?php echo json_encode($ListFromOperatorCurrencies) ?>;

  var ListToOperatorCurrencies;
  ListToOperatorCurrencies = <?php echo json_encode($ListToOperatorCurrencies) ?>;







  function updateFromCurrency(){

    FromBankingVal = $("#FromBanking").val();

    linegetmethod = '';

    //console.log(ListBankingCurrencies);

    if (ListBankingCurrencies[FromBankingVal]) {

        linegetmethod += '<option value="'+ListBankingCurrencies[FromBankingVal]['id']+'" data-subtext="'+ListBankingCurrencies[FromBankingVal]['name']+'">';
        linegetmethod += ListBankingCurrencies[FromBankingVal]['iso'];
        linegetmethod += "</option>";
    }


    var select = $('#FromCurrency');
    select.empty().append(linegetmethod);
    $('#FromCurrency').selectpicker('refresh');

    //updateTotal();

  }






  function updateToCurrency(){

    if($("#ToBanking").length) {

      ToBankingVal = $("#ToBanking").val();

      linegetmethod = '';

      //console.log(ListBankingCurrencies);

      if (ListBankingCurrencies[ToBankingVal]) {

          linegetmethod += '<option value="'+ListBankingCurrencies[ToBankingVal]['id']+'" data-subtext="'+ListBankingCurrencies[ToBankingVal]['name']+'">';
          linegetmethod += ListBankingCurrencies[ToBankingVal]['iso'];
          linegetmethod += "</option>";
      }


      var select = $('#ToCurrency');
      select.empty().append(linegetmethod);
      $('#ToCurrency').selectpicker('refresh');

      //updateTotal();

      }

  }





  $("#FromBanking").live('change keyup', function (e) {
    updateFromCurrency();
    updateTotal();
    updateChange();
    updateAmountTo();
    updateBankSlide(this.value);
  });

  $("#ToBanking").live('change keyup', function (e) {
    updateToCurrency();
    updateTotal();
    updateChange();
    updateAmountFrom();
    updateBankSlide(this.value);
  });

  $(document).ready(function(){

    setTimeout(function(){

      if($("#FromBanking").length) {
        updateFromCurrency();
      }

      if($("#ToBanking:not([disabled]").length) {
        updateToCurrency();
      }

      updateTotal();


    }, 0);

  });





  listChanges = <?php echo json_encode($listChanges) ?>;
  LiveChanges = listChanges,
  ListBankingCurrencies = <?php echo json_encode($ListBankingCurrencies) ?>;

  $("#FromOperator").live('change keyup', function (e) {

    updateFromCurrencies();
    updateTotal();

    updateChange();
    updateAmountTo();
  });

  $("#ToOperator").live('change keyup', function (e) {
    updateToCurrencies();
    updateTotal();

    updateChange();
    updateAmountFrom();
  });


  //updateChange();
  //updateAmountTo();




  function updateFromCurrencies() {
    FromOperatorVal = $("#FromOperator").val();

    linegetmethod = '';

    if (FromOperatorVal) {

      linegetmethodArray = new Array();

      jQuery.each(ListFromOperatorCurrencies[FromOperatorVal], function( ckey, cval ) {

        linegetmethod += '<option value="'+ListFromCurrencies[cval]['value']+'" data-icon="'+ListFromCurrencies[cval]['data-icon']+'" data-subtext="'+ListFromCurrencies[cval]['data-subtext']+'">';
        linegetmethod += ListFromCurrencies[cval]['name'];
        linegetmethod += "</option>";

      });

    }


    if ($("#FromOperator").length) {
      var select = $('#FromCurrency');
      select.empty().append(linegetmethod);
      $('#FromCurrency').selectpicker('refresh');
    }


  }




  function updateToCurrencies() {
    ToOperatorVal = $("#ToOperator").val();

    linegetmethod = '';

    if (ToOperatorVal) {

      linegetmethodArray = new Array();

      jQuery.each(ListToOperatorCurrencies[ToOperatorVal], function( ckey, cval ) {

        linegetmethod += '<option value="'+ListToCurrencies[cval]['value']+'" data-icon="'+ListToCurrencies[cval]['data-icon']+'" data-subtext="'+ListToCurrencies[cval]['data-subtext']+'">';
        linegetmethod += ListToCurrencies[cval]['name'];
        linegetmethod += "</option>";

      });

    }



    if ($("#FromCurrency").length) {
      var FromCurrency = $("#FromCurrency");
    }

    if ($("#ToCurrency").length) {
      var ToCurrency = $("#ToCurrency");
    }

    if ($("#ToCurrency1").length) {
      var ToCurrency = $("#ToCurrency1");
    }

    if ($("#ToOperator").length) {
      var select = ToCurrency;
      select.empty().append(linegetmethod);
      ToCurrency.selectpicker('refresh');
    }


  }



  function updateChange() {
    ToBankingVal = $("#ToBanking").val();

    //ToCurrencyVal = ListBankingCurrencies[ToBankingVal]['id'];

    //console.log(ToBankingVal);
    //FromOperatorVal = $("#FromOperator").val();
    //console.log(FromOperatorVal);




    if ($("#FromCurrency").length) {
      var FromCurrency = $("#FromCurrency");
    }

    if ($("#ToCurrency").length) {
      var ToCurrency = $("#ToCurrency");
    }

    if ($("#ToCurrency1").length) {
      var ToCurrency = $("#ToCurrency1");
    }

    FromCurrencyID = FromCurrency.val();
    ToCurrencyID = ToCurrency.val();



    if (ListCurrencies[FromCurrencyID] && ListCurrencies[ToCurrencyID]) {

        FromCurrency = ListCurrencies[FromCurrencyID]['name'];
        ToCurrency = ListCurrencies[ToCurrencyID]['name'];

        if (FromCurrencyID != ToCurrencyID) {

            livechange = filterFloat(LiveChanges[FromCurrencyID] [ToCurrencyID]);

            $('.livechange').css('display', 'block');
        }else{
            livechange = filterFloat(1);
            $('.livechange').css('display', 'none');
        }
        $('#changecurrency').val(livechange);

        $('.livechange span').html('1 '+FromCurrency+' = '+livechange+' '+ToCurrency);  
        //updateamountFrom(true);

    }else{

        livechange = filterFloat(1);
        $('.livechange').css('display', 'none');

    }





  }



  if ($("#FromCurrency").length) {
    var FromCurrency = $("#FromCurrency");
  }

  FromCurrency.live('change keyup', function (e) {
    updateChange();
    updateAmountTo();
  });

  if ($("#ToCurrency").length) {
    var ToCurrency = $("#ToCurrency");
    
    ToCurrency.live('change keyup', function (e) {
      updateChange();
      updateAmountFrom();
    });

  }


  updateChange();




  var FromAmount = $(".FromAmount");
  var ToAmount = $(".ToAmount");


  jQuery.each(FromAmount, function( Tselect, Toperation ) {

    var cleavefromphone = new Cleave(this, {
        numeral: true,
        delimiter: ' ',
        stripLeadingZeroes : false,
        numeralPositiveOnly : true,
        //ZnumeralDecimalScale : 0,
        //numeralIntegerScale : 15,
    });


    FromCurrency.live('change keyup', function (e) {

      FromAmountValDecimal = ListCurrencies[FromCurrencyID]['decimal'];

      //cleavefromphone.setFormatter(FromAmountValDecimal);

    });


  });


  jQuery.each(ToAmount, function( Tselect, Toperation ) {

    var cleavefromphone = new Cleave(this, {
        numeral: true,
        delimiter: ' ',
    });

  });



  var FromAmountVal;
  FromAmountVal = filterFloat(FromAmount.val());

  function updateTotal(){

    if ($("#FromCurrency").length) {
      var FromCurrency = $("#FromCurrency");
    }

    if ($("#ToCurrency").length) {
      var ToCurrency = $("#ToCurrency");
    }

    if ($("#ToCurrency1").length) {
      var ToCurrency = $("#ToCurrency1");
    }


    FromCurrencyID = FromCurrency.val();
    ToCurrencyID = ToCurrency.val();

    FromCurrency = ListFromCurrencies[FromCurrencyID]['name'];

    //console.log('ste');
    //console.log(ToCurrency.val());

    if (ToCurrencyID) {
      ToCurrency = ListToCurrencies[ToCurrencyID]['name'];
    }else{
      ToCurrency = null;
      //console.log(ToCurrencyID);
    }


    FromAmountValDecimal = ListCurrencies[FromCurrencyID]['decimal'];

    fee = filterFloat(((FromAmountVal*1/100)+0.004999).toFixed(FromAmountValDecimal));
    TG = filterFloat((FromAmountVal + fee).toFixed(FromAmountValDecimal));


    $('.fee').html(formatMoney(fee, FromAmountValDecimal, ' ')+' '+FromCurrency);
    $('.TG').html(formatMoney(TG, FromAmountValDecimal, ' ')+' '+FromCurrency);

    $('#fee').val(fee);
    $('#TG').val(TG);

  }


  function updateAmount(){

  }


    var callbacksFromAmount, callbacksToAmount;
    callbacksFromAmount = callbacksToAmount = true;


    function updateAmountFrom(updateType){
        let callbacksOriginal = callbacksFromAmount;
        callbacksToAmount = false;

        FromAmountVal = filterFloat(FromAmount.val());
        if (updateType && !FromAmount.val() && ToAmount.val()) {
          callbacksToAmount = callbacksOriginal;
          updateAmountTo();
          return;
        }

        FromAmountValDecimal = ListCurrencies[FromCurrencyID]['decimal'];
        FromAmountValI = formatMoney((FromAmountVal+0.004999).toFixed(FromAmountValDecimal), FromAmountValDecimal, ' ');


        if (callbacksFromAmount) {
            callbacksFromAmount = false;

            if (updateType) {
              FromAmount.val(FromAmountValI);
              FromAmountVal = filterFloat(FromAmount.val());
            }

            updateAmountFrom();

        }

      ToAmountVal = filterFloat(FromAmountVal*livechange);
      if (callbacksFromAmount) {
          updateAmountTo(true);
      /*
        if (updatelimitTo()) {
          updateamountTo(true);
        }else{
          //updateamountTo(false); // Nonon
        }    
      */
      }



      ToAmountValDecimal = ListCurrencies[ToCurrency.val()]['decimal'];
      ToAmountValI = formatMoney((ToAmountVal-0.004999).toFixed(ToAmountValDecimal), ToAmountValDecimal, ' ');


      if (FromCurrencyID == ToCurrencyID) {
        ToAmountVal = FromAmountVal;
        ToAmountValI = FromAmountValI;
      }

      ToAmount.val(ToAmountValI);

      //updatefinal();


        //console.log(callbacksOriginal);
        //console.log(callbacksFromAmount);

        if (callbacksOriginal) {
            callbacksFromAmount = callbacksToAmount = true;
            updateTotal();
        }

    }



    function updateAmountTo(updateType){
        let callbacksOriginal = callbacksToAmount;
        callbacksFromAmount = false;

        ToAmountVal = filterFloat(ToAmount.val());
        if (updateType && !ToAmount.val() && FromAmount.val()) {
          callbacksFromAmount = callbacksOriginal;
          updateAmountFrom();
          return;
        }

        ToAmountValDecimal = ListCurrencies[ToCurrencyID]['decimal'];
        ToAmountValI = formatMoney((ToAmountVal+0.004999).toFixed(ToAmountValDecimal), ToAmountValDecimal, ' ');

        if (callbacksToAmount) {
            callbacksToAmount = false;

            if (updateType) {
              ToAmount.val(ToAmountValI);
            }

            ToAmountVal = filterFloat(ToAmount.val());

            updateAmountTo();

            //updatelimitTo();
        }

        FromAmountVal = filterFloat(ToAmountVal/livechange);

        FromAmountValDecimal = ListCurrencies[FromCurrency.val()]['decimal'];
        FromAmountValI = formatMoney((FromAmountVal+0.004999).toFixed(FromAmountValDecimal), FromAmountValDecimal, ' ');

        if (callbacksToAmount) {
            updateAmountFrom();
            //updatelimitFrom();
        }else{
            FromAmount.val(FromAmountValI);

        }

        //updatefinal();


        //console.log(callbacksOriginal);
        if (callbacksOriginal) {
            callbacksFromAmount = callbacksToAmount = true;
            updateTotal();
        }


    }



  $(".FromAmount").live('change keyup', function (e) {
    updateType = false;
    if(e.type == 'change'){
      updateType = true;
    }
    //callbacksFromAmount = true;
    //updateAmount();
    updateAmountFrom(updateType);
  });

  $(".ToAmount").live('change keyup', function (e) {
    updateType = false;
    if(e.type == 'change'){
      updateType = true;
    }

    //callbacksToAmount = true;
    //updateAmount();
    updateAmountTo(updateType);
  });


/*

  function updateOperaCurrency(){

    ToBankingVal = $("#ToBanking").val();
    ListFromOperaCurrencyVal = ListBankingCurrencies[ToBankingVal];

    console.log(ListToOperaCurrencies[ListFromOperaCurrencyVal]);
  }


*/


  function updateTransaction(){

    var TransactionRefVal = ($('.TransactionRef:checked').val());

    var TranactionRefList = <?php echo json_encode(array('#FromOperator' => 'Deposit', '#FromBanking' => 'Deposit', '#FromBanking' => 'Transfer', '#ToBanking' => 'Transfer', '#ToOperator' => 'Send')) ?>;

      jQuery.each(TranactionRefList, function( Tselect, Toperation ) {
        $(Tselect+":not(.notTrans)").attr('disabled', 'disabled');
        $(Tselect+":not(.notTrans)").css('display', 'none');
        $(Tselect+":not(.notTrans)").addClass("d-none");

        if (TransactionRefVal == Toperation) {
          $(Tselect+":not(.notTrans)").removeAttr('disabled');
          $(Tselect+":not(.notTrans)").trigger('change');
        }
      });


    setTimeout(function(){

      jQuery.each(TranactionRefList, function( Tselect, Toperation ) {
        if (TransactionRefVal == Toperation) {
          $(Tselect+":not(.notTrans)").parent().removeClass("d-none");
        }
      });

    }, 0);


    jQuery.each(TranactionRefList, function( Tselect, Top ) {
      $(Tselect+":not(.notTrans)").selectpicker('refresh');
    });

  }


  $(".TransactionRef").live('change keyup', function (e) {
    updateTransaction();
    //console.log(this.value);
  });


  $(document).ready(function(){

    if ($(".TransactionRef").length) {

      setTimeout(function(){
        $(".TransactionRef").trigger('change');
      }, 0);

    }

  })



  $(".Amount").live('change', function (e) {
    var AmountVal;
      AmountVal = filterFloat($(this).val());
      AmountValI = formatMoney((AmountVal+0.004999).toFixed(2), 2, ' ');
      $(this).val(AmountValI);
  });





  ForCleaveArray = <?php echo json_encode(array('#TargetAmount', 'firstAmount', 'autoAmount')) ?>;

  jQuery.each(ForCleaveArray, function( ckey, cval ) {

    if($(cval).length) {

      var cleavefromphone = new Cleave(cval, {
          numeral: true,
          delimiter: ' ',
          stripLeadingZeroes : false,
          numeralPositiveOnly : true,
      });

    }

  });


  $(document).ready(function(){


    updateToCurrencies();
    updateFromCurrencies();
    updateTotal();

    updateChange();
    updateToCurrency();
    updateAmountFrom(true);
  });


</script>