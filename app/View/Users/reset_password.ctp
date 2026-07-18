<?php 
    $this->set('title_for_layout', Configure::read('Company.name').' - '.Op::translate(Configure::read('Company.slogan'), 'fr-'));

    $this->set('robots', 'all');

    $this->set('navHome', "active");

    $TermImg = '/img/logow.png';

?>


<?php

	unset($this->request->data['User']['password']);
	unset($this->request->data['User']['passwd']);

?>


<div class="theme-layout">

    <div class="postoverlay"></div>

    <!-- Reponsive Header -->
        <?php echo $this->element('responsiveheader') ?>
    <!-- end Reponsive Header -->  

	<section>
		<div class="gap">
			<div class="container">
				<div class="row">
					<div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">

	                    <style type="text/css">

						    .artist-bg-1 {
						        background-image: url(<?php echo Router::url(Op::resizedURL($TermImg, array('width' => 231, 'height' => 231, 'quality' => 80, 'space' => false))) ?>);
						        border-radius: 50%!important;
						        width: 231px;
						        height: 231px;
						        margin: auto;
						        right: 0;
						        left: 0;
						    }

					    </style>

						<div class="album-cover-bg blur-bottom artist-bg-1"></div>
						<div class="color-inherit users form">

							<h2 class="main-title">Réinitialisez votre mot de passe</h2>


							<div class="editing-info" style="margin-top: 35px;">
								<!--h5 class="f-title"><i class="ti-info-alt"></i>Information du Profil</h5-->

									<?php echo $this->Form->create(false,  array('type' => 'file', 'novalidate' => true, 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100')))));

									?>

									<h6 style=" text-align: center; ">Veuillez saisir un nouveau mot de passe et le confirmer afin d'accéder à votre compte</h6>

									<div class="form-group row">
									    <label class="col-form-label col-lg-4">Nouveau mot de passe</label>
									    <div class="col-lg-8">
									        <?php echo $this->Form->input('User.password', array('required' => 'required', 'id' => 'emailInput')); ?>
										</div>
									</div>


									<div class="form-group row">
									    <label class="col-form-label col-lg-4">Confirmer mot de passe</label>
									    <div class="col-lg-8">
									        <?php echo $this->Form->input('User.passwd', array('required' => 'required')); ?>
										</div>
									</div>

									<div class="submit-btns">
							            <?php echo $this->Form->button('<span>Soumettre</span>', array('type' => 'submit', 'id' => 'UsernameForm', 'class' => 'mtr-btn signin', 'name' => 'form', 'value' => 'passverifieduser')); ?>
									</div>
								<?php echo $this->Form->end(); ?>

							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section><!-- video section -->


	<body onload="document.getElementById('emailInput').focus()"></body>


</div>
