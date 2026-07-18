<?php

    $this->set('title_for_layout', Configure::read('Company.name').' - Inscription');

    $this->set('robots', 'all');



    echo $this->Html->script('/js/cleave.min.js', array('once' => true));
    echo $this->Html->script('/js/cleave-phone.i18n.js', array('once' => true));
  
    $messageAlert = isset($messageAlert)?$messageAlert:'';
    $Countries = isset($Countries)?$Countries:[];
    $GeoLoc = isset($GeoLoc)?$GeoLoc:[];
    $firstname = isset($firstname)?$firstname:'';
    $name = isset($name)?$name:'';
    $phone = isset($phone)?$phone:'';
    
  ?>


  <!-- Content
  ============================================= -->
  <div id="content">
    <div class="container py-5">
      <div class="row">
        <div class="col-md-10 col-lg-8 col-xl-6 mx-auto">
          <div class="bg-white border-primary shadow-md rounded p-3 pt-sm-4 pb-sm-5 px-sm-5">
            <h3 class="font-weight-400 text-center mb-4">Inscription</h3>
            <hr class="mx-4">
            <p class="lead text-center"><?php echo $messageAlert; ?></p>

            <?php 
              echo $this->Form->create(false,  array('action'=>'/reg_code','type' => 'post', 'id' => 'signupForm', 'novalidate' => false, 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100')))));
              //echo $this->Form->create(false,  array('type' => 'file', 'id' => 'signupForm', 'novalidate' => false, 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100'))))); 
            ?>
            



            <!--div class="row">
              
              <div class="col-lg-12">

                <div class="form-group">
                  <label for="youSend">Pays</label>

                    <?php

                      $ListCountries = $ListCode = array();
                      $CountryDefault = null;
                      foreach ($Countries as $key => $Country) {

                        $ListCode[$Country['Country']['id']]['c'] = $Country['Country']['code'];
                        $ListCode[$Country['Country']['id']]['n'] = $Country['Country']['mobile_code'];

                        $ListCountries[$Country['Country']['id']]['name'] = $Country['Country']['name'];
                        $ListCountries[$Country['Country']['id']]['value'] = $Country['Country']['id'];
                        $ListCountries[$Country['Country']['id']]['data-icon'] = $Country['Country']['icon'];
                        $ListCountries[$Country['Country']['id']]['data-subtext'] = $Country['Country']['fullname'];

                        if ($Country['Country']['code'] == $GeoLoc['country_code']) {
                          $CountryDefault = $Country['Country']['id'];
                        }

                      }


                      echo $this->Form->input('Info.country_id', array('id' => 'SenderCountry', 'label' => false, 'div' => false, 'data-style' => 'custom-select', 'data-container' => 'body', 'data-live-search' => false, 'class' => 'selectpicker form-control bg-transparent' , 'required' => '', 'options' => $ListCountries, 'default' => $CountryDefault, 'empty' => 'Séléctionner un pays'));
                    ?>
                  <span class="text-muted text-1">Ne pourra pas être modifier ultérieurement</span>
                </div>
              </div>

            </div-->

              <div class="mb-3 mb-md-0 mx-auto">
                <span class="me-1" id="firstName">Prénom :</span><span><?php echo $firstname; ?></span>
              </div>
              <div class="mb-3 mb-md-0 mx-auto">
                <span class="me-1" id="lastName">Nom :</span><span><?php echo $name; ?></span>
              </div>
              <div class="mb-3 mb-md-0 mx-auto">
                <span class="me-1" id="phone">Téléphone :</span><span><?php echo $phone; ?></span>
              </div>

            <?php if(isset($email)){ ?>
              <div class="mb-3 mb-md-0 mx-auto">
                <span class="me-1" id="email">Email :</span><span><?php echo $email; ?></span>
              </div>
            <?php } ?>

            <?php if(isset($birthday)){ ?>
              <div class="mb-3 mb-md-0 mx-auto">
                <span class="me-1" id="birthday">Date de naissance :</span><span><?php echo $birthday; ?></span>
              </div>
            <?php } ?>
              
              

              <div class="form-group my-3">
                <!--<label for="youSend">Téléphone</label>-->

                <?php
                  echo $this->Form->input('code.sms', array('type' => 'text', 'id' => 'phone_code', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => 'Code Téléphone', 'required' => ''));
                ?>

              </div>


            <?php if(isset($email)){ ?>
              <div class="form-group my-3">
                <?php 
                  echo $this->Form->input('code.mail', array('id' => 'email_code', 'data-bv-field' => 'youSend', 'div' => false, 'class' => 'form-control', 'placeholder' => 'Code E-mail', 'required' => ''));
                ?>
              </div>
            <?php } ?>


               






              <div class="d-flex justify-content-center" >
                
                <?php 
                  echo $this->Html->link('Retour',
                    ['controller' => 'users', 'action' => 'reg', '?'=>['recup_params'=>true]],
                    ['class' => 'btn btn-danger btn-block mx-2 my-4']
                  );//  url(['controller'=>'users', 'action'=>'reg']); 
                ?>
                <div class="d-flex justify-content-center mx-2" ><?php echo $this->Form->button('<span>Confirmer</span>', array('type' => 'submit', 'id' => 'UsernameForm', 'class' => 'btn btn-primary btn-block my-4', 'name' => 'form', 'value' => 'register')); ?></div>

              </div>
              

            <?php echo $this->Form->end(); ?>

            
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Content end --> 
  


<script type="text/javascript">


  var ListCode;

  ListCode = <?php echo json_encode($ListCode) ?>;
  

  function cleavephonechange(aaaa, cccval){
      aaaa.setRawValue("+"+ListCode[cccval]['n']);
      aaaa.setPhoneRegionCode(ListCode[cccval]['c']);
  }


  function cleavephonefrom(){

    SenderCountryVal = $("#SenderCountry").val();

    if ($('.cleavefromphone').length) {
      cleavephonechange(cleavefromphone, SenderCountryVal);
    }

  }

  function cleavephonefrom(){

    SenderCountryVal = $("#SenderCountry").val();

    if ($('#fphone').length) {
      cleavephonechange(cleavefromphone, SenderCountryVal);
    }

  }


  if ($('#fphone').length) {
    var cleavefromphone = new Cleave('#fphone', {phone: true, phoneRegionCode: ListCode[$("#SenderCountry").val()]['c']});
  }


$("#SenderCountry").live('change', function (e) {
  cleavephonefrom();
});

<?php
if (empty($this->request->data['User'])) {
?>
  cleavephonefrom();
<?php
}
?>



</script>