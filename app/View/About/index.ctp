<?php

    $this->set('lieu', true);
    $this->set('pageTitle', 'Qui sommes-nous?');
    $this->set('activeAboutUs', 'active');

    $this->set('robots', 'all');


?>

  <!-- Content
  ============================================= -->
  <div id="content">
    
    <!-- Who we are
    ============================================= -->
    <section class="section">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 d-flex">
            <div class="my-auto px-0 px-lg-5 mx-2">
              <h2 class="text-9">Trans Numerica</h2>
              <?php echo str_replace(array('<p'), array('<p class="text-4"'), $Textes['about']['Texte']['contenu']) ?>
            </div>
          </div>
          <div class="col-lg-6 my-auto text-center"> <img class="img-fluid rounded-lg" src="<?php echo Router::url(Op::resizedURL('/img/logo.png', array('width' => 400, 'height' => 400, 'quality' => 80, 'space' => true))) ?>" alt=""> </div>
        </div>
      </div>
    </section>
    <!-- Who we are end -->
    
    <!-- Our Values
    ============================================= -->
    <section class="section bg-white">
      <div class="container">
        <div class="row no-gutters">
          <div class="col-lg-6 order-2 order-lg-1">
            <div class="row">
              <div class="col-6 col-lg-7 ml-auto mb-lg-n5"> <img class="img-fluid rounded-lg" src="<?php echo Router::url(Op::resizedURL('/img/art/fly.png', array('width' => 400, 'height' => 300, 'quality' => 80, 'space' => true))) ?>" alt="banner"> </div>
              <div class="col-6 col-lg-8 mt-lg-n5"> <img class="img-fluid rounded-lg" src="<?php echo Router::url(Op::resizedURL('/img/art/carr.png', array('width' => 400, 'height' => 300, 'quality' => 80, 'space' => true))) ?>" alt="banner"> </div>
            </div>
          </div>
          <div class="col-lg-6 d-flex order-1 order-lg-2">
            <div class="my-auto px-0 px-lg-5">
              <h2 class="text-9 mb-4">Nos Valeurs</h2>
              <h4 class="text-4 font-weight-500">Notre Mission</h4>
              <?php echo str_replace(array('<p'), array('<p class="text-3"'), $Textes['mission']['Texte']['contenu']) ?>
              <h4 class="text-4 font-weight-500 mb-2">Notre Vision</h4>
              <?php echo str_replace(array('<p'), array('<p class="text-3"'), $Textes['vision']['Texte']['contenu']) ?>
              <h4 class="text-4 font-weight-500 mb-2">Notre But</h4>
              <?php echo str_replace(array('<p'), array('<p class="text-3"'), $Textes['but']['Texte']['contenu']) ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Our Values end -->
    
    <!-- Leadership
    ============================================= -->
    <section class="section">
      <div class="container">
        <h2 class="text-9 text-center">La Direction</h2>
        <p class="lead text-center mb-5">L'équipe dirigeante de la Start-Up</p>
        <div class="row">

          <?php

          foreach ($Textes as $keyT => $vT) {
            if(strstr(mb_strtolower($keyT), mb_strtolower('staff'))) {
              $StaffKeys[] = $vT;
            }
          }

          foreach ($StaffKeys as $key => $staff) {
            $imgStaff = '/img/emptyprofil.png';
            if(is_file(WWW_ROOT.$staff['Texte']['file1'])){
              $imgStaff = $staff['Texte']['file1'];
            }

          ?>

          <div class="col-sm-6 col-md-4 text-center mb-4 mb-md-0">
            <div class="team rounded d-inline-block"> <img class="img-fluid rounded" alt="" src="<?php echo Router::url(Op::resizedURL($imgStaff, array('width' => 200, 'height' => 250, 'quality' => 80, 'space' => false))) ?>">
              <h3><?php echo $staff['Texte']['name'] ?></h3>
              <p class="text-muted"><?php echo $staff['Texte']['description'] ?></p>
              <ul class="social-icons social-icons-sm d-inline-flex">

                <?php

                preg_match_all( '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', Op::HtmltoText($staff['Texte']['contenu']), $graphstaffUrls);

                foreach ($graphstaffUrls[0] as $key => $sUrl) {

                  $spClass = null;
                  if (strstr($sUrl, 'facebook.com')) {
                    $spClass = 'social-icons-facebook';
                    $siClass = 'fab fa-facebook-f';
                  }elseif (strstr($sUrl, 'twitter.com')) {
                    $spClass = 'social-icons-twitter';
                    $siClass = 'fab fa-twitter';
                  }elseif (strstr($sUrl, 'instagram.com')) {
                    $spClass = 'social-icons-instagram';
                    $siClass = 'fab fa-instagram';
                  }

                  if ($spClass) {
                  ?>

                  <li class="<?php echo $spClass ?>"><a data-toggle="tooltip" href="<?php echo Router::url($sUrl) ?>" target="_blank" title="" data-original-title="Facebook"><i class="<?php echo $siClass ?>"></i></a></li>

                  <?php
                  }
                }
                ?>

              </ul>
            </div>
          </div>


          <?php

          }

          ?>


          <!--div class="col-sm-6 col-md-3 text-center mb-4 mb-md-0">
            <div class="team rounded d-inline-block"> <img class="img-fluid rounded" alt="" src="images/team/leader-4.jpg">
              <h3>Miky Sheth</h3>
              <p class="text-muted">General Manager</p>
              <ul class="social-icons social-icons-sm d-inline-flex">
                <li class="social-icons-facebook"><a data-toggle="tooltip" href="" target="_blank" title="" data-original-title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                <li class="social-icons-twitter"><a data-toggle="tooltip" href="" target="_blank" title="" data-original-title="Twitter"><i class="fab fa-twitter"></i></a></li>
                <li class="social-icons-google"><a data-toggle="tooltip" href="" target="_blank" title="" data-original-title="Google"><i class="fab fa-google"></i></a></li>
              </ul>
            </div>
          </div-->
        </div>
      </div>
    </section>
    <!-- Leadership end -->
    
    <!-- Our Investors
    ============================================= -->
    <section class="section bg-white">
      <div class="container">
        <h2 class="text-9 text-center">Développement</h2>
        <p class="lead text-center mb-5">Developed by Ubisoft Annecy (Haute Savoie - France), 6 rue André FUMEX Annecy, 74000, Haute Savoie, France, in collaboration with Multi Joe And Ste Digital Kin/RDC.</p>
        <!--div class="brands-grid separator-border">
          <div class="row align-items-center">
            <div class="col-6 col-sm-4 col-lg-2 text-center"><a href=""><img class="img-fluid" src="images/partner/partner-1.png" alt="Brands"></a></div>
            <div class="col-6 col-sm-4 col-lg-2 text-center"><a href=""><img class="img-fluid" src="images/partner/partner-2.png" alt="Brands"></a></div>
            <div class="col-6 col-sm-4 col-lg-2 text-center"><a href=""><img class="img-fluid" src="images/partner/partner-3.png" alt="Brands"></a></div>
            <div class="col-6 col-sm-4 col-lg-2 text-center"><a href=""><img class="img-fluid" src="images/partner/partner-4.png" alt="Brands"></a></div>
            <div class="col-6 col-sm-4 col-lg-2 text-center"><a href=""><img class="img-fluid" src="images/partner/partner-5.png" alt="Brands"></a></div>
            <div class="col-6 col-sm-4 col-lg-2 text-center"><a href=""><img class="img-fluid" src="images/partner/partner-6.png" alt="Brands"></a></div>
            <div class="col-6 col-sm-4 col-lg-2 text-center"><a href=""><img class="img-fluid" src="images/partner/partner-7.png" alt="Brands"></a></div>
            <div class="col-6 col-sm-4 col-lg-2 text-center"><a href=""><img class="img-fluid" src="images/partner/partner-8.png" alt="Brands"></a></div>
            <div class="col-6 col-sm-4 col-lg-2 text-center"><a href=""><img class="img-fluid" src="images/partner/partner-9.png" alt="Brands"></a></div>
            <div class="col-6 col-sm-4 col-lg-2 text-center"><a href=""><img class="img-fluid" src="images/partner/partner-10.png" alt="Brands"></a></div>
            <div class="col-6 col-sm-4 col-lg-2 text-center"><a href=""><img class="img-fluid" src="images/partner/partner-11.png" alt="Brands"></a></div>
            <div class="col-6 col-sm-4 col-lg-2 text-center"><a href=""><img class="img-fluid" src="images/partner/partner-1.png" alt="Brands"></a></div>
          </div>
        </div-->
      </div>
    </section>
    <!-- Our Investors end -->
    
    <!-- Testimonial
    ============================================= -->
    <!--section class="section">
      <div class="container">
        <h2 class="text-9 text-center">Ce que les gens disent de nous</h2>
        <p class="lead text-center mb-4">Une expérience qui ne s'oublie pas</p>
        <div class="owl-carousel owl-theme" data-autoplay="true" data-nav="true" data-loop="true" data-margin="30" data-slideby="2" data-stagepadding="5" data-items-xs="1" data-items-sm="1" data-items-md="2" data-items-lg="2">
          <div class="item">
            <div class="testimonial rounded text-center p-4">
              <p class="text-4">“Texte factice simple à utiliser et à prix raisonnable de l'industrie de l'imprimerie et de la composition. Certains d'entre eux seraient plus intéressés par ces questions et, autant que possible, je serais offensé.”</p>
              <strong class="d-block font-weight-500">Jay Shah</strong> <span class="text-muted">Founder at Icomatic Pvt Ltd</span> </div>
          </div>
          <div class="item">
            <div class="testimonial rounded text-center p-4">
              <p class="text-4">“Je suis heureux de travailler avec l'industrie de l'imprimerie et de la composition. Certaines poursuites s'intéresseraient davantage à celles-ci et, quant au nombre d'entre elles qui seraient poursuivies, j'ai peut-être raison..”</p>
              <strong class="d-block font-weight-500">Patrick Cary</strong> <span class="text-muted">Freelancer from USA</span> </div>
          </div>
          <div class="item mh-100">
            <div class="testimonial rounded text-center p-4">
              <p class="text-4">“Réservation rapides et faciles à utiliser dans votre devis préféré. Bien meilleur rapport qualité-prix que sur place.”</p>
              <strong class="d-block font-weight-500">De Mortel</strong> <span class="text-muted">Online Retail</span> </div>
          </div>
          <div class="item">
            <div class="testimonial rounded text-center p-4">
              <p class="text-4">“Je les ai utilisés deux fois maintenant. De bons tarifs, un service très efficace. Excellent.”</p>
              <strong class="d-block font-weight-500">Chris Tom</strong> <span class="text-muted">Utilisateur de Londre</span> </div>
          </div>
          <div class="item">
            <div class="testimonial rounded text-center p-4">
              <p class="text-4">“C'est une très bonne idée de réserver et gagner du temps. Les tarifs sont équitables et vous pouvez effectuer les transactions sans souci !”</p>
              <strong class="d-block font-weight-500">Mauri Lindberg</strong> <span class="text-muted">Utilisateur d'Australie</span> </div>
          </div>
          <div class="item">
            <div class="testimonial rounded text-center p-4">
              <p class="text-4">“Je ne l'essaye que depuis quelques jours. Mais jusqu'à présent excellent. Semble fonctionner parfaitement. Pour le moment, je ne l'utilise que pour acheter des tickets à ma Famille au Congo.”</p>
              <strong class="d-block font-weight-500">Dennis Jacques</strong> <span class="text-muted">Utilisateur des États-Unis</span> </div>
          </div>
        </div>
        <div class="text-center mt-4"><a href="#" class="btn-link text-4">See more people review<i class="fas fa-chevron-right text-2 ml-2"></i></a></div>
      </div>
    </section-->
    <!-- Testimonial end -->
    
    <!-- JOIN US
    ============================================= -->
    <!--section class="section bg-primary py-5">
      <div class="container text-center">
        <div class="row">
          <div class="col-sm-6 col-md-3">
            <div class="featured-box text-center">
              <div class="featured-box-icon text-light mb-2"> <i class="fas fa-globe"></i> </div>
              <h4 class="text-12 text-white mb-0">180+</h4>
              <p class="text-4 text-white mb-0">Countries</p>
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="featured-box text-center">
              <div class="featured-box-icon text-light mb-2"> <i class="fas fa-dollar-sign"></i> </div>
              <h4 class="text-12 text-white mb-0">120</h4>
              <p class="text-4 text-white mb-0">Currencies</p>
            </div>
          </div>
          <div class="col-sm-6 col-md-3 mt-4 mt-md-0">
            <div class="featured-box text-center">
              <div class="featured-box-icon text-light mb-2"> <i class="fas fa-users"></i> </div>
              <h4 class="text-12 text-white mb-0">2.5M</h4>
              <p class="text-4 text-white mb-0">Users</p>
            </div>
          </div>
          <div class="col-sm-6 col-md-3 mt-4 mt-md-0">
            <div class="featured-box text-center">
              <div class="featured-box-icon text-light mb-2"> <i class="far fa-life-ring"></i> </div>
              <h4 class="text-12 text-white mb-0">24X7</h4>
              <p class="text-4 text-white mb-0">Support</p>
            </div>
          </div>
        </div>
      </div>
    </section-->
    <!-- JOIN US end -->
    
  </div>
  <!-- Content end --> 
  
