<?php

    $this->set('title_for_layout', Configure::read('Company.name').' - Connexion');

    $this->set('robots', 'all');

    $optionsli = array('width' => 231, 'height' => 231, 'quality' => 80, 'space' => false);

    $codeSended = isset($codeSended)?$codeSended:'';

?>





  <!-- Content
  ============================================= -->
  <div id="content">
    <div class="container py-5">
      <div class="row">
        <div class="col-md-9 col-lg-7 col-xl-5 mx-auto">
          <div class="bg-white border-primary shadow-md rounded p-3 pt-sm-4 pb-sm-5 px-sm-5">
            <h3 class="font-weight-400 text-center mb-4 text-primary">Mot de passe oublié</h3>
            <hr class="mx-n4">
            <p class="lead text-center my-4">
              <?php 
                echo isset($messageAlert)?$messageAlert:"Recevez un code secret à travers votre adresse email, afin de changer votre mot de passe";
              ?>
            </p>
              

            <?php echo $this->Form->create(false,  array('url' => array('action'=>($codeSended?'/passforgotsave':'/passforgotsend'),'type' => 'post', 'id' => 'signupForm', 'novalidate' => false, 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100')))))); ?>

              <div class="form-group my-3">
                <?php
                  echo $this->Form->input('User.email', array('id' => 'email', 'readonly'=>$codeSended, 'value'=>$email??'', 'data-bv-field' => 'youSend', 'div' => false, 'class' => 'form-control', 'placeholder' => 'E-mail', 'required' => '',/* 'label' => 'E-mail'*/));
                ?>

              </div>

          <?php if(!$codeSended){ ?>

              <div class="d-flex justify-content-center" ><?php echo $this->Form->button('<span>Recevoir le code secret</span>', array('type' => 'submit', 'id' => 'UsernameForm', 'class' => 'btn btn-primary btn-block mt-1 mb-4', 'name' => 'form', 'value' => 'login')); ?></div>
          
          <?php }else{ ?>

              <div class="form-group my-3">
                <?php
                
echo $this->Form->input('codesecret', array('id' => 'codesecret', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => 'Code Secret', 'required' => '',/* 'label' => 'Code Secret'*/));
                ?>

              </div>


              <div class="form-group my-3">
                <?php
                unset($this->request->data['User']['password']);

echo $this->Form->input('User.password', array('id' => 'password', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => 'Mot de passe', 'required' => '', /*'label' => 'Nouveau Mot de passe'*/));
                ?>

              </div>

              <div class="form-group my-3">
                <?php
                unset($this->request->data['User']['passwordConf']);

echo $this->Form->input('User.passwordConf', array('id' => 'passwordConf', 'type'=>'password', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => 'Confirmer Mot de passe', 'required' => '', /*'label' => 'Nouveau Mot de passe'*/));
                ?>

              </div>

          


              <!--div class="row">
                <div class="col-sm"><a class="btn-link" href="#">Mot de passe oublié ?</a></div>
              </div-->

              <div class="d-flex justify-content-center">
                <div class="d-flex justify-content-center mx-1" ><a class='btn btn-danger btn-block my-4' href="<?php echo Router::url(['controller' => 'users', 'action' => 'passforgotcancel']); ?>">Retour</a></div>
                <div class="d-flex justify-content-center mx-1" ><?php echo $this->Form->button('<span>Modifier</span>', array('type' => 'submit', 'id' => 'UsernameForm', 'class' => 'btn btn-primary btn-block my-4', 'name' => 'form', 'value' => 'login')); ?></div>
              </div>

              <?php } ?>


            <?php echo $this->Form->end(); ?>

            
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Content end --> 
  





<?php

$passworda = 'email';
if($this->Form->error('User.password')) {
  $passworda = 'pass';
}

?>
