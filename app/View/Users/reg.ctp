<?php

    $this->set('title_for_layout', Configure::read('Company.name').' - Inscription');

    $this->set('robots', 'all');

    $reg_data_is = isset($reg_data);


    echo $this->Html->script('/js/cleave.min.js', array('once' => true));
    echo $this->Html->script('/js/cleave-phone.i18n.js', array('once' => true));

    $Countries = isset($Countries)?$Countries:[];
    $GeoLoc = isset($GeoLoc)?$GeoLoc:[];
    $reg_data = isset($reg_data)?$reg_data:[];
    $errors = isset($errors)?$errors:[];
    

  ?>

<?php 
  function error_input($input_name_error){
    if(isset($input_name_error)){ 
?>
      <div class="error-message">
        <?php echo $input_name_error; ?>
      </div>
<?php 
    } 
  } 
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
            <p class="lead text-center">Vos informations sont en sécurités avec nous.</p>

            <?php echo $this->Form->create(false,  array('type' => 'file', 'id' => 'signupForm', 'novalidate' => false, 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100'))))); ?>




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



              <div class="form-group my-0 my-3">
                <!--<label for="youSend">Nom</label>-->
                <div class="row my-0">

                  <div class="mb-3 mb-md-0 col-md-5 col-lg-5 col-xl-5 mx-auto">

                  <?php
                  echo $this->Form->input('Info.firstname', array('id' => 'firstName', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => 'Prénom*', 'required' => '', 'value'=>$reg_data_is?$reg_data['Info']['firstname']:''));
                  error_input($errors['firstname']??null);
                  ?>

                  </div>

                  <div class=" col-md-7 col-lg-7 col-xl-7 mx-auto">

                  <?php
                  echo $this->Form->input('Info.name', array('id' => 'lastName', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => 'Nom*', 'required' => '', 'value'=>$reg_data_is?$reg_data['Info']['name']:''));
                  error_input($errors['name']??null);
                  ?>

                  </div>
                </div>
              </div>



              <div class="form-group my-3">
                <!--<label for="youSend">Téléphone</label>-->

                <?php
                  echo $this->Form->input('Info.phone', array('type' => 'text', 'id' => 'fphone', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => 'Téléphone*', 'required' => '', 'value'=>$reg_data_is?$reg_data['Info']['phone']:''));
                  error_input($errors['phone']??null);
                ?>

              </div>



              <div class="form-group my-3">
                <?php
                  echo $this->Form->input('User.email', array('id' => 'ererf', 'data-bv-field' => 'youSend', 'div' => false, 'class' => 'form-control', 'placeholder' => 'E-mail', 'required' => '', 'value'=>$reg_data_is?$reg_data['User']['email']:''));
                  error_input($errors['email']??null);
                ?>

              </div>

              <div class="form-group my-3">
                <?php
                  echo $this->Form->input('Info.birthday_', array('id' => 'ererf', 'data-bv-field' => 'youSend', 'div' => false, 'class' => 'form-control datepicker-bootstrap', 'placeholder' => 'Date de naissance', 'required' => '', 'value'=>$reg_data_is?$reg_data['Info']['birthday']:''));
                  error_input($errors['birthday']??null);
                ?>

              </div>


              <div class="form-group my-3">
                <?php
                  echo $this->Form->input('User.password', array('id' => 'ererf', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => 'Mot de passe*', 'required' => '', 'value'=>''));
                  error_input($errors['password']??null);
                ?>

              </div>

              <div class="form-group my-3">
                <?php
                echo $this->Form->input('User.passwd', array('id' => 'ererf', 'data-bv-field' => 'youSend', 'div' => false, 'class' => 'form-control', 'placeholder' => 'Confirmer mot de passe*', 'required' => '', 'value'=>''));
                ?>

              </div>



              <div class="my-3 form-check custom-control custom-checkbox">
                <?php echo $this->Form->input('User.tos', array('id' => 'remember-me1', 'type' => 'checkbox', 'class' => 'custom-control-input', 'error' => false, 'checked'=>$reg_data_is?$reg_data['User']['tos']:false)); ?>
                <label class="custom-control-label" for="remember-me1"><i class="check-box"></i>Accepter les <a href="<?php echo Router::url(array('controller' => 'policy', 'action' => 'index')); ?>" style="color:#2ca1b3" target="_blank">Conditions Générales d'Utilisation</a> ?</label>
              </div>
                <?php
                  //echo $this->Form->error('User.tos');
                  error_input($errors['tos']??null);
                  
                ?>






              <div class="d-flex justify-content-center" ><?php echo $this->Form->button('<span>Inscription</span>', array('type' => 'submit', 'id' => 'UsernameForm', 'class' => 'btn btn-primary btn-block my-4', 'name' => 'form', 'value' => 'register')); ?></div>

            <?php echo $this->Form->end(); ?>

            <p class="text-3 text-muted text-center mb-0">Vous avez déjà un compte? <a class="btn-link" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'login')) ?>">Connexion</a></p>
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