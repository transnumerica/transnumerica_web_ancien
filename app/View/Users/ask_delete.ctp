<?php

    $this->set('title_for_layout', Configure::read('Company.name').' - Suppression');

    $this->set('robots', 'all');

    $optionsli = array('width' => 231, 'height' => 231, 'quality' => 80, 'space' => false);

?>





  <!-- Content
  ============================================= -->
  <div id="content">
    <div class="container py-5">
      <div class="row">
        <div class="col-md-9 col-lg-7 col-xl-5 mx-auto">
          <div class="bg-white border-primary shadow-md rounded p-3 pt-sm-4 pb-sm-5 px-sm-5">
            <h3 class="font-weight-400 text-center mb-4 text-danger">Demande de suppression de Compte</h3>
            <hr class="mx-n4">
            <p class="lead text-center">La suppression définitive de vos données sera effective dans 90 jours. Sans action de votre part durant ce délai, le compte sera supprimé définitivement. Si vous changez d'avis, il vous suffira de vous reconnecter avant l'échéance pour annuler la procédure.</p>

            <p class="lead text-center">Veuillez cliquer sur le bouton ci-dessous pour vous déconnecter et confirmer votre demande.</p>


            <div class="d-flex justify-content-center" ><a class="btn btn-danger btn-block my-4" href="/users/logout">Déconnexion</a></div>

            
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
