<?php

    $this->set('title_for_layout', Configure::read('Company.name').' - Suppression');

    $this->set('robots', 'all');

    $optionsli = array('width' => 231, 'height' => 231, 'quality' => 80, 'space' => false);

    $not_connected = isset($not_connected)?$not_connected:true;
?>





  <!-- Content
  ============================================= -->
  <div id="content">
    <div class="container py-5">
      <div class="row">
        <div class="col-md-9 col-lg-7 col-xl-5 mx-auto">
          <div class="bg-white border-primary shadow-md rounded p-3 pt-sm-4 pb-sm-5 px-sm-5">
            <h3 class="font-weight-400 text-center mb-4 text-danger">Suppression de Compte</h3>
            <hr class="mx-n4">
            <?php if($not_connected){ ?>
              <p class="lead text-center">Vous n'êtes pas connectés. Veuillez d'abord vous connecter, puis revenez sur ce lien afin de supprimer votre compte.</p>
              <div class="d-flex justify-content-center" ><a class="btn btn-primary btn-block my-4" href="/users/login">Se Connecter</a></div>
            <?php }else{ ?>
              <p class="lead text-center">Vous êtes sur le point de supprimer votre compte!</p>
              <p class="lead text-center">La suppression définitive de vos données sera effective dans 90 jours. Sans action de votre part durant ce délai, le compte sera supprimé définitivement. Si vous changez d'avis, il vous suffira de vous reconnecter avant l'échéance pour annuler la procédure.</p>
              <div class="d-flex justify-content-center" ><a class="btn btn-danger btn-block my-4" href="/users/ask_delete">Supprimer</a></div>
            <?php } ?>
            
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
