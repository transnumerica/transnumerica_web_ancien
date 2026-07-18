<?php 
    $this->set('title_for_layout', Configure::read('Company.name').' - '.Op::translate(Configure::read('Company.slogan'), 'fr-'));

    $this->set('robots', 'all');

    $this->set('navHome', "active");

    $TermImg = '/img/logow.png';

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
					<div class="col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">

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
						<div class="color-inherit">

							<h2 class="main-title"><?php echo $Textes['cgv']['Texte']['name']; ?></h2>
							<?php echo $Textes['cgv']['Texte']['contenu']; ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section><!-- video section -->


</div>
