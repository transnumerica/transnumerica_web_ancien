<?php

    $this->set('title_for_layout', Configure::read('Company.name').' - Connexion');

    $this->set('robots', 'all');

    $optionsli = array('width' => 231, 'height' => 231, 'quality' => 80, 'space' => false);

    
?>

<?php if(isset($erreurK)){ ?>
      <script>
        //alert("<?php echo $erreurK; ?>");
      </script>;
<?php } ?>





  <!-- Content
  ============================================= -->
  <div id="content">
    <div class="container py-5">
      <div class="row">
        <div class="col-md-9 col-lg-7 col-xl-5 mx-auto">
          <div class="bg-white border-primary shadow-md rounded p-3 pt-sm-4 pb-sm-5 px-sm-5">
            <h3 class="font-weight-400 text-center mb-4 text-primary">Connexion</h3>
            <hr class="mx-n4">
            <p class="lead text-center">Nous sommes heureux de vous revoir!</p>

            <?php echo $this->Form->create(false,  array('type' => 'file', 'id' => 'signupForm', 'novalidate' => false, 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100'))))); ?>

              <div class="form-group my-3">
                <?php
                echo $this->Form->input('User.username', array('id' => '', 'data-bv-field' => 'youSend', 'div' => false, 'class' => 'form-control', 'placeholder' => 'E-mail ou Tel', 'required' => ''/*, 'label' => 'E-mail'*/));
                ?>

              </div>


              <div class="form-group my-3">
                <?php
                unset($this->request->data['User']['password']);

                echo $this->Form->input('User.password', array('id' => 'pass', 'data-bv-field' => 'youSend', 'label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => 'Mot de passe', 'required' => ''/*, 'label' => 'Mot de passe'*/));
                ?>

              </div>


              <!--div class="row">
                <div class="col-sm"><a class="btn-link" href="#">Mot de passe oublié ?</a></div>
              </div-->


              <div class="d-flex justify-content-center" ><?php echo $this->Form->button('<span>Me Connecter</span>', array('type' => 'submit', 'id' => 'UsernameForm', 'class' => 'btn btn-primary btn-block my-4', 'name' => 'form', 'value' => 'login')); ?></div>

            <?php echo $this->Form->end(); ?>

            <p class="text-3 text-muted text-center mb-0">Vous n'avez pas de compte? <a class="btn-link" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'reg')) ?>">Inscription</a></p>
            <p class="text-3 text-muted text-center mb-0"><a class="btn-link" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'passforgot')) ?>">Mot de passe oublié?</a></p>
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
