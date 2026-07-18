<!doctype html>
<html lang="fr">

<!-- Mirrored from iconicthemes.net/adonis/demo-02/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 07 Jan 2020 19:52:48 GMT -->
<head>


<?php


        $devMode = Configure::read('debug') > 0;

        $title = $this->fetch('title');
        if (!empty($title)) echo '<title>'.$title.'</title>';

        if (empty($robots)) $robots = 'noindex, nofollow';
        echo $this->Html->meta(array('name' => 'robots', 'content' => $robots));

        echo $this->Html->meta('logo', Router::url(Op::resizedURL('/logo.png', array('width' => 60, 'height' => 60, 'quality' => 80, 'space' => true)), true), array('type' => 'icon'));

        if (!isset($_SERVER['HTTP_REFERER'])) {
            echo $this->Html->meta(array('property' => 'og:title', 'content' => $title));
            echo $this->Html->meta(array('property' => 'og:url', 'content' => $this->Html->url(null, true)));
            echo $this->Html->meta(array('property' => 'og:description', 'content' => Configure::read('Company.slogan')));
            echo $this->Html->meta(array('property' => 'og:site_name', 'content' => "Coiffure Job") );
            echo $this->Html->meta(array('property' => 'og:type', 'content' => "website") );
            echo $this->Html->meta(array('property' => 'og:image', 'content' => Router::url('/img/logoF.png')));
            echo $this->Html->meta(array('property' => 'og:locale', 'content' => "fr_FR") );

            echo $this->Html->meta(array('name' => 'twitter:title', 'content' => $title));
            echo $this->Html->meta(array('name' => 'twitter:description', 'content' => Configure::read('Company.slogan')));
            echo $this->Html->meta(array('name' => 'twitter:card', 'content' => 'summary'));
            echo $this->Html->meta(array('name' => 'twitter:site', 'content' => '@coiffurejob'));
            echo $this->Html->meta(array('name' => 'twitter:creator', 'content' => '@Antic Communcation'));
            echo $this->Html->meta(array('name' => 'twitter:domain', 'content' => 'coiffure-job.com'));
            echo $this->Html->meta(array('name' => 'twitter:url', 'content' => $this->Html->url(null, true)));

            echo $this->Html->charset();
            echo $this->Html->meta(array('http-equiv' => 'content-language', 'content' => 'fr'));

            echo $this->Html->meta(array('name' => 'description', 'content' => Configure::read('Company.slogan')));
            echo $this->Html->meta(array('name' => 'reply-to', 'content' => 'contact@shopcongo.com'));
            echo $this->Html->meta(array('name' => 'publisher', 'content' => 'Antic Communication'));
            echo $this->Html->meta(array('name' => 'category', 'content' => 'internet'));

            echo $this->Html->meta(array('name' => 'keywords', 'content' => 'cpdf,rdc,kinshasa, centre, centre congolais'));
        }
        


        echo $this->Html->script('/js/jquery-3.0.0.js', array('once' => true));
    ?>

        <script type="text/javascript">
            var window; 
            console2 = window.console.log;
            window.console.log = false;
            jQuery.migrateMute = true;
        </script>

    <?php

        echo $this->Html->script('/js/jquery-migrate-3.3.2.js', array('once' => true));
        echo $this->Html->script('/js/jquery-migrate-1.4.1.js', array('once' => true));
?>

        <script type="text/javascript">
            window.console.log = console2;
        </script>

<script src="<?php echo $this->base ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script> 
<script src="<?php echo $this->base ?>/vendor/bootstrap-select/js/bootstrap-select.min.js"></script> 

    <?php
        $this->AssetCompress->addCss(array('normalize.css','cake.css'),'all.css');
        $this->AssetCompress->addScript(array('session.js'),'all1');


        //echo $this->AssetCompress->includeAssets($devMode);
        echo $this->AssetCompress->includeAssets(true);


        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');

?>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--link rel="apple-touch-icon" sizes="180x180" href="<?php echo $this->base ?>/assets/icon/apple-touch-icon.png">

    <link rel="manifest" href="http://iconicthemes.net/adonis/assets/icon/site.webmanifest"-->

    <!-- Bootstrap core CSS -->



    
    <?php
        echo $this->Html->css('/css/color.css', array('once' => true));
        echo $this->Html->css('/css/colors.min.css', array('once' => true));
    ?>







<!-- Web Fonts
============================================= -->
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Rubik:300,300i,400,400i,500,500i,700,700i,900,900i' type='text/css'>

<!-- Stylesheet
============================================= -->
<?php
    //echo $this->Html->css('/vendor/bootstrap/css/bootstrap.min.css', array('once' => true));
    /*echo $this->Html->css('/vendor/font-awesome/css/all.min.css', array('once' => true));
    echo $this->Html->css('/vendor/bootstrap-select/css/bootstrap-select.min.css', array('once' => true));
    echo $this->Html->css('/vendor/currency-flags/css/currency-flags.min.css', array('once' => true));
    echo $this->Html->css('/css/style1.css', array('once' => true));
    echo $this->Html->css('/css/stylesheet.css', array('once' => true));*/
?>
<!-- Colors Css -->
<link id="color-switcher" type="text/css" rel="stylesheet" href="#" />




<!-- Web Fonts
============================================= -->
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>

<!-- Stylesheet
============================================= -->
<?php
    echo $this->Html->css('/bootstrap/css/bootstrap.min.css', array('once' => true));
    echo $this->Html->css('/font-awesome/css/all.min.css', array('once' => true));
    echo $this->Html->css('/owl.carousel/assets/owl.carousel.min.css', array('once' => true));
    echo $this->Html->css('/owl.carousel/assets/owl.theme.default.min.css', array('once' => true));
    echo $this->Html->css('/jquery-ui/jquery-ui.css', array('once' => true));
    echo $this->Html->css('/daterangepicker/daterangepicker.css', array('once' => true));
    echo $this->Html->css('/jquery-seat-charts/jquery.seat-charts.css', array('once' => true));
    echo $this->Html->css('/css/stylesheet.css', array('once' => true));
?>

<?php
    echo $this->Html->css('/css/color.css', array('once' => true));
    echo $this->Html->css('/css/colors.min.css', array('once' => true));
    echo $this->Html->css('/css/color-blue.css', array('once' => true));
?>

</head>




<body>
<!-- Preloader -->
<!--div id="preloader"><div data-loader="dual-ring"></div></div--><!-- Preloader End -->

<!-- Document Wrapper   
============================================= -->
<div id="main-wrapper"> 
  
  <!-- Header
  ============================================= -->
  <header id="header">
    <div class="container">
      <div class="header-row">
        <div class="header-column justify-content-start"> 
          
          <!-- Logo
          ============================================= -->
          <div class="logo"> <a href="<?php echo Router::url('/') ?>" class="d-flex" title="Transnumerica"><img src="<?php echo Router::url(Op::resizedURL('/img/logo.png', array('max-height' => 80, 'quality' => 80, 'space' => true))) ?>" style="height: 75px; padding: 5px 0px;" alt="Transnumerica" /></a> </div>
          <!-- Logo end --> 
          
        </div>
        <div class="header-column justify-content-end"> 
          
          <!-- Primary Navigation
          ============================================= -->




          <style type="text/css">

            .home-2 .header-top-lang .top-lang {
                color: var(--color-dark)
            }

            .home-2 .header-top-social span {
                color: var(--color-dark)
            }

            .home-2 .header-top-social a {
                color: var(--theme-color)
            }

            .home-2 .navbar {
                background: var(--theme-color)
            }

            .home-2 .nav-right .sidebar-btn .nav-right-link {
                background: var(--color-dark)
            }

            .home-2 .nav-right .cart-btn span {
                background: var(--theme-color2)
            }

            .home-2 .main {
                margin-top: 0
            }

            .home-2 .hero-single {
                padding-top: 140px;
                padding-bottom: 160px
            }

          </style>


          <?php


          $Pays[CakeSession::read('Localisation')] = '';


          $Pays['CD'] = array('name' => 'Congo-Kinshasa', 'flag' => '/img/flag/rdc.png');
          $Pays['CG'] = array('name' => 'Congo-Brazza', 'flag' => '/img/flag/congo.png');



          ?>


          <div class="home-2">
            <div class="header-top-lang">
              <div class="dropdown">
                <a href="#" class="top-lang dropdown-toggle" data-bs-toggle="dropdown"><!--i class="fa fa-globe"--></i><img src="<?php echo Router::url(Op::resizedURL($Pays[CakeSession::read('Localisation')]['flag'], array('width' => 25, 'height' => 15, 'quality' => 80, 'space' => false))) ?>" style=" vertical-align: middle; "> <span class="d-none d-md-inline"><?php echo $Pays[CakeSession::read('Localisation')]['name'] ?></span></a>
                <ul class="dropdown-menu dropdown-menu-end" style="z-index: 9999;">
                  <?php
                    foreach ($Pays as $keyPay => $Pay) {
                  ?>
                    <li><a class="dropdown-item" href="<?php echo Router::url(array('controller' => 'country', 'action' => 'flag', $keyPay)) ?>"><img src="<?php echo Router::url(Op::resizedURL($Pay['flag'], array('width' => 25, 'height' => 15, 'quality' => 80, 'space' => false))) ?>" style=" vertical-align: middle; "> <?php echo $Pay['name'] ?></a></li>
                  <?php
                    }
                  ?>

                </ul>
              </div>
            </div>
          </div>

          <?php
            echo $this->Html->script('/js/bootstrap.bundle.min.js', array('once' => true));
          ?>


          <nav class="primary-menu navbar navbar-expand-lg">
            <div id="header-nav" class="collapse navbar-collapse">
              <ul class="navbar-nav">
                <!--li class="dropdown active"> <a class="dropdown-toggle" href="#">Home</a>
                  <ul class="dropdown-menu">
                    <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Index 1</a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.html">Recharge or Bill Payment</a></li>
                        <li><a class="dropdown-item" href="booking-hotels.html">Booking</a></li>
                      </ul>
                    </li>
                    <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Index 2</a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index-2.html">Recharge or Bill Payment</a></li>
                        <li><a class="dropdown-item" href="booking-hotels-2.html">Booking</a></li>
                      </ul>
                    </li>
                    <li><a class="dropdown-item" href="index-3.html">Index 3 - (Recharge & Bill)</a></li>
                    <li><a class="dropdown-item" href="index-4.html">Index 4 - (Booking)</a></li>
                    <li><a class="dropdown-item" href="index-5.html">Index 5 - (Recharge & Bill)</a></li>
                    <li><a class="dropdown-item" href="index-6.html">Index 6 - (Booking)</a></li>
                    <li><a class="dropdown-item" href="index-7.html">Index 7 - (Recharge & Bill)</a></li>
                    <li><a class="dropdown-item" href="index-8.html">Index 8 - (Booking)</a></li>
                    <li><a class="dropdown-item" href="index-9.html">Index 9 - (Booking)</a></li>
                    <li><a class="dropdown-item" href="index-10.html">Index 10 - (Recharge & Bill)</a></li>
                    <li><a class="dropdown-item" href="index-11.html">Index 11 - (Mobile Top-Up)</a></li>
                  </ul>
                </li>
                <li class="dropdown"> <a class="dropdown-toggle" href="#">Recharge & Bill Payment</a>
                  <ul class="dropdown-menu dropdown-menu-sm">
                    <li>
                      <div class="row">
                        <div class="col-lg-6">
                          <ul class="dropdown-mega-submenu">
                            <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Mobile</a>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="index.html">Layout 1</a></li>
                                <li><a class="dropdown-item" href="index-2.html">Layout 2</a></li>
                              </ul>
                            </li>
                            <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">DTH</a>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="recharge-bill-dth.html">Layout 1</a></li>
                                <li><a class="dropdown-item" href="recharge-bill-dth-2.html">Layout 2</a></li>
                              </ul>
                            </li>
                            <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Data Card</a>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="recharge-bill-datacard.html">Layout 1</a></li>
                                <li><a class="dropdown-item" href="recharge-bill-datacard-2.html">Layout 2</a></li>
                              </ul>
                            </li>
                            <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Broadband</a>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="recharge-bill-broadband.html">Layout 1</a></li>
                                <li><a class="dropdown-item" href="recharge-bill-broadband-2.html">Layout 2</a></li>
                              </ul>
                            </li>
                            <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Landline</a>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="recharge-bill-landline.html">Layout 1</a></li>
                                <li><a class="dropdown-item" href="recharge-bill-landline-2.html">Layout 2</a></li>
                              </ul>
                            </li>
                            <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Cable TV</a>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="recharge-bill-cabletv.html">Layout 1</a></li>
                                <li><a class="dropdown-item" href="recharge-bill-cabletv-2.html">Layout 2</a></li>
                              </ul>
                            </li>
                            <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Electricity</a>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="recharge-bill-electricity.html">Layout 1</a></li>
                                <li><a class="dropdown-item" href="recharge-bill-electricity-2.html">Layout 2</a></li>
                              </ul>
                            </li>
                            <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Metro</a>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="recharge-bill-metro.html">Layout 1</a></li>
                                <li><a class="dropdown-item" href="recharge-bill-metro-2.html">Layout 2</a></li>
                              </ul>
                            </li>
                            <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Gas</a>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="recharge-bill-gas.html">Layout 1</a></li>
                                <li><a class="dropdown-item" href="recharge-bill-gas-2.html">Layout 2</a></li>
                              </ul>
                            </li>
                            <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Water</a>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="recharge-bill-water.html">Layout 1</a></li>
                                <li><a class="dropdown-item" href="recharge-bill-water-2.html">Layout 2</a></li>
                              </ul>
                            </li>
                          </ul>
                        </div>
                        <div class="col-lg-6">
                          <ul class="dropdown-mega-submenu">
                            <li><a class="dropdown-item" href="recharge-order.html">Order</a></li>
                            <li><a class="dropdown-item" href="recharge-order-summary.html">Order Summary</a></li>
                            <li><a class="dropdown-item" href="recharge-payment.html">Payment</a></li>
                            <li><a class="dropdown-item" href="recharge-payment-success.html">Payment Success</a></li>
                            <li><a class="dropdown-item" href="recharge-plans.html">Recharge - Plans</a></li>
                            <li><a class="dropdown-item" href="recharge-plans-2.html">Recharge - Plans 2</a></li>
                            <li><a class="dropdown-item" href="recharge-plans-3.html">Recharge - Plans 3</a></li>
                            <li><a class="dropdown-item" href="recharge-invoice.html" target="_blank">Invoice</a></li>
                            <li><a class="dropdown-item" href="email-template/recharge-email-template/index.html" target="_blank">Email Template</a></li>
                          </ul>
                        </div>
                      </div>
                    </li>
                  </ul>
                </li>
                <li class="dropdown dropdown-mega"> <a class="dropdown-toggle" href="#">Booking</a>
                  <ul class="dropdown-menu">
                    <li>
                      <div class="row">
                        <div class="col-lg"> <span class="sub-title">Hotels</Span>
                          <ul class="dropdown-mega-submenu">
                            <li><a class="dropdown-item" href="booking-hotels.html">Home Layout 1</a></li>
                            <li><a class="dropdown-item" href="booking-hotels-2.html">Home Layout 2</a></li>
                            <li><a class="dropdown-item" href="booking-hotels-list.html">Hotel List</a></li>
                            <li><a class="dropdown-item" href="booking-hotels-grid.html">Hotel Grid</a></li>
                            <li><a class="dropdown-item" href="booking-hotels-details.html">Hotel Details</a></li>
                            <li><a class="dropdown-item" target="_blank" href="booking-hotels-invoice.html">Invoice</a></li>
                            <li><a class="dropdown-item" target="_blank" href="email-template/hotel-email-template/index.html">Email Template</a></li>
                          </ul>
                        </div>
                        <div class="col-lg"> <span class="sub-title">Flights</Span>
                          <ul class="dropdown-mega-submenu">
                            <li><a class="dropdown-item" href="booking-flights.html">Home Layout 1</a></li>
                            <li><a class="dropdown-item" href="booking-flights-2.html">Home Layout 2</a></li>
                            <li><a class="dropdown-item" href="booking-flights-one-way.html">One Way Trip List</a></li>
                            <li><a class="dropdown-item" href="booking-flights-round-trip.html">Round Trip List</a></li>
                            <li><a class="dropdown-item" href="booking-flights-confirm-details.html">Confirm Details</a></li>
                            <li><a class="dropdown-item" target="_blank" href="booking-flights-itinerary.html">Itinerary</a></li>
                            <li><a class="dropdown-item" target="_blank" href="booking-flights-invoice.html">Invoice</a></li>
                            <li><a class="dropdown-item" target="_blank" href="email-template/flight-email-template/index.html">Email Template</a></li>
                          </ul>
                        </div>
                        <div class="col-lg"> <span class="sub-title">Trains</Span>
                          <ul class="dropdown-mega-submenu">
                            <li><a class="dropdown-item" href="booking-trains.html">Home Layout 1</a></li>
                            <li><a class="dropdown-item" href="booking-trains-2.html">Home Layout 2</a></li>
                            <li><a class="dropdown-item" href="booking-trains-list.html">Trains List</a></li>
                            <li><a class="dropdown-item" href="booking-trains-confirm-details.html">Confirm Details</a></li>
                            <li><a class="dropdown-item" target="_blank" href="booking-trains-invoice.html">Invoice</a></li>
                            <li><a class="dropdown-item" target="_blank" href="email-template/train-email-template/index.html">Email Template</a></li>
                          </ul>
                        </div>
                        <div class="col-lg"> <span class="sub-title">Bus</Span>
                          <ul class="dropdown-mega-submenu">
                            <li><a class="dropdown-item" href="booking-bus.html">Home Layout 1</a></li>
                            <li><a class="dropdown-item" href="booking-bus-2.html">Home Layout 2</a></li>
                            <li><a class="dropdown-item" href="booking-bus-list.html">Bus List</a></li>
                            <li><a class="dropdown-item" href="booking-bus-confirm-details.html">Confirm Details</a></li>
                            <li><a class="dropdown-item" target="_blank" href="booking-bus-invoice.html">Invoice</a></li>
                            <li><a class="dropdown-item" target="_blank" href="email-template/bus-email-template/index.html">Email Template</a></li>
                          </ul>
                        </div>
                        <div class="col-lg"> <span class="sub-title">Cars</Span>
                          <ul class="dropdown-mega-submenu">
                            <li><a class="dropdown-item" href="booking-cars.html">Home Layout 1</a></li>
                            <li><a class="dropdown-item" href="booking-cars-2.html">Home Layout 2</a></li>
                            <li><a class="dropdown-item" href="booking-cars-list.html">Cars List</a></li>
                            <li><a class="dropdown-item" href="booking-cars-grid.html">Cars Grid</a></li>
                            <li><a class="dropdown-item" href="booking-cars-grid-2.html">Cars Grid 2</a></li>
                            <li><a class="dropdown-item" href="booking-cars-details.html">Car Details</a></li>
                            <li><a class="dropdown-item" target="_blank" href="booking-cars-invoice.html">Invoice</a></li>
                            <li><a class="dropdown-item" target="_blank" href="email-template/car-email-template/index.html">Email Template</a></li>
                          </ul>
                        </div>
                      </div>
                    </li>
                  </ul>
                </li>
                <li class="dropdown"> <a class="dropdown-toggle" href="#">Features</a>
                  <ul class="dropdown-menu">
                    <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Headers</a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.html">Light Version (Default)</a></li>
                        <li><a class="dropdown-item" href="index-7.html">Left Navigation (Alternate)</a></li>
                        <li><a class="dropdown-item" href="index-4.html">Dark Version</a></li>
                        <li><a class="dropdown-item" href="index-5.html">Primary Version</a></li>
                        <li><a class="dropdown-item" href="index-8.html">Transparent</a></li>
                        <li><a class="dropdown-item" href="feature-page-header-custom-background-with-transparent-header.html">Transparent with border</a></li>
                      </ul>
                    </li>
                    <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Navigation DropDown</a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.html">Light Version (Default)</a></li>
                        <li><a class="dropdown-item" href="index-3.html">Dark Version</a></li>
                        <li><a class="dropdown-item" href="index-6.html">Primary Version</a></li>
                      </ul>
                    </li>
                    <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Page Headers</a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="feature-page-header-left-alignment.html">Left Alignment</a></li>
                        <li><a class="dropdown-item" href="feature-page-header-center-alignment.html">Center Alignment</a></li>
                        <li><a class="dropdown-item" href="feature-page-header-light.html">Light Version</a></li>
                        <li><a class="dropdown-item" href="feature-page-header-dark.html">Dark Version</a></li>
                        <li><a class="dropdown-item" href="feature-page-header-primary.html">Primary Version</a></li>
                        <li><a class="dropdown-item" href="feature-page-header-custom-background.html">Custom Background</a></li>
                        <li><a class="dropdown-item" href="feature-page-header-custom-background-with-transparent-header.html">Custom Background 2</a></li>
                      </ul>
                    </li>
                    <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Footer</a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.html">Light Version Default</a></li>
                        <li><a class="dropdown-item" href="index-7.html">Alternate Version</a></li>
                        <li><a class="dropdown-item" href="feature-footer-dark.html">Dark Version</a></li>
                        <li><a class="dropdown-item" href="feature-footer-primary.html">Primary Version</a></li>
                      </ul>
                    </li>
                    <li><a class="dropdown-item" href="feature-layout-boxed.html">Layout Boxed</a></li>
                  </ul>
                </li>
                <li class="dropdown"> <a class="dropdown-toggle" href="#">Blog</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="blog.html">Blog Standard</a></li>
                    <li><a class="dropdown-item" href="blog-grid.html">Blog Grid</a></li>
                    <li><a class="dropdown-item" href="blog-list.html">Blog List</a></li>
                    <li><a class="dropdown-item" href="blog-single.html">Blog Single Right Sidebar</a></li>
                    <li><a class="dropdown-item" href="blog-single-left-sidebar.html">Blog Single Left Sidebar</a></li>
                  </ul>
                </li>
                <li class="dropdown"> <a class="dropdown-toggle" href="#">Pages</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="about-us.html">About Us</a></li>
                    <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Login/Signup</a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="login.html">Login</a></li>
                        <li><a class="dropdown-item" href="signup.html">Sign Up</a></li>
                        <li><a class="dropdown-item" href="forgot-password.html">Forgot Password</a></li>
                        <li><a class="dropdown-item" href="otp.html">OTP - One Time Password</a></li>
                      </ul>
                    </li>
                    <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">My Profile</a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.html">Personal Information</a></li>
                        <li><a class="dropdown-item" href="profile-favourites.html">Favourites</a></li>
                        <li><a class="dropdown-item" href="profile-cards.html">Credit or Debit Cards</a></li>
                        <li><a class="dropdown-item" href="profile-notifications.html">Notifications</a></li>
                        <li><a class="dropdown-item" href="profile-orders-history.html">Orders History</a></li>
                        <li><a class="dropdown-item" href="profile-password.html">Change Password</a></li>
                      </ul>
                    </li>
                    <li><a class="dropdown-item" href="payment.html">Payment</a></li>
                    <li><a class="dropdown-item" href="payment-2.html">Payment 2</a></li>
                    <li><a class="dropdown-item" href="help.html">Help</a></li>
                    <li><a class="dropdown-item" href="faq.html">Faq</a></li>
                    <li><a class="dropdown-item" href="support.html">Support</a></li>
                    <li><a class="dropdown-item" href="contact-us.html">Contact Us</a></li>
                    <li><a class="dropdown-item" href="404.html">404</a></li>
                    <li><a class="dropdown-item" href="coming-soon.html" target="_blank">Coming Soon</a></li>
                    <li><a class="dropdown-item" href="elements.html">Elements</a></li>
                    <li><a class="dropdown-item" href="elements-2.html">Elements 2</a></li>
                  </ul>
                </li-->
                <li class="<?php echo @$activeAboutUs ?>"><a href="<?php echo Router::url(array('controller' => 'about', 'action' => 'index')) ?>">Qui sommes-nous</a>
                </li>
              </ul>
            </div>
          </nav>
          <!-- Primary Navigation end -->
          
          <!-- Collapse Button
          =============================== -->
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#header-nav"> <span></span> <span></span> <span></span> </button>
          
          <!-- Login Signup
          =============================== -->
          <nav class="login-signup navbar navbar-expand separator ml-sm-2 pl-sm-2">
            <ul class="navbar-nav">
            


 
          <!-- Login & Signup Link
          ============================== -->

              <?php

              if (empty($AuthUser['Info']['id'])) {
                
              

              ?>

              <li><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'login')) ?>">Connexion</a> </li>
              <li class="align-items-center h-auto ml-sm-3"><a class="btn btn-primary" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'reg')) ?>">Inscription</a></li>


              <?php

              }else{
                
              ?>

              <!--li class="dropdown language"> <a class="dropdown-toggle" href="#">Fr</a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">English</a></li>
                  <li><a class="dropdown-item" href="#">French</a></li>
                  <li><a class="dropdown-item" href="#">Русский</a></li>
                  <li><a class="dropdown-item" href="#">简体中文</a></li>
                  <li><a class="dropdown-item" href="#">Türkçe</a></li>
                </ul>
              </li-->
              <!--li class="dropdown notifications"> <a class="dropdown-toggle" href="#"><span class="text-5"><i class="far fa-bell"></i></span><span class="count">3</span></a>
                <ul class="dropdown-menu">
                  <li class="text-center text-3 py-2">Notifications (3)</li>
                  <li class="dropdown-divider mx-n3"></li>
                  <li><a class="dropdown-item" href="#"><i class="fas fa-bell"></i>A new digital FIRC document is available for you to download<span class="text-1 text-muted d-block">22 Jul 2020</span></a></li>
                  <li><a class="dropdown-item" href="#"><i class="fas fa-bell"></i>Updates to our privacy policy. Please read.<span class="text-1 text-muted d-block">04 March 2020</span></a></li>
                  <li><a class="dropdown-item" href="#"><i class="fas fa-bell"></i>Update about <?php echo Configure::read('Company.name') ?> fees<span class="text-1 text-muted d-block">18 Feb 2020</span></a></li>
                  <li class="dropdown-divider mx-n3"></li>
                  <li><a class="dropdown-item text-center text-primary px-0" href="notifications.html">See all Notifications</a></li>
                </ul>
              </li-->
              <li class="dropdown profile ml-2"> <a class="px-0 dropdown-toggle" href="#"><img class="rounded-circle" style=" width: 40px; height: 40px; " src="<?php echo Router::url(Op::resizedURL($AuthUser['Info']['profil'], array('width' => 100, 'height' => 100, 'quality' => 80, 'space' => false))) ?>" alt=""></a>
                <ul class="dropdown-menu">
                  <li class="text-center text-3 py-2">Bonjour, <?php echo $AuthUser['Info']['firstname'] ?></li>
                  <li class="dropdown-divider mx-n3"></li>
                  <li><a class="dropdown-item" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'index')) ?>"><i class="fas fa-chalkboard-teacher"></i>Tableau de bord</a></li>
                  <!--li><a class="dropdown-item" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'index')) ?>"><i class="fas fa-redo-alt"></i>Transactions</a></li>
                  <li><a class="dropdown-item" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'index')) ?>"><i class="fas fa-user"></i>Mon Profil</a></li>
                  <li><a class="dropdown-item" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'index')) ?>"><i class="fas fa-shield-alt"></i>Sécurité</a></li-->
                  <!--li><a class="dropdown-item" href="settings-payment-methods.html"><i class="fas fa-credit-card"></i>Payment Methods</a></li>
                  <li><a class="dropdown-item" href="settings-notifications.html"><i class="fas fa-bell"></i>Notifications</a></li-->
                  <li class="dropdown-divider mx-n3"></li>
                  <!--li><a class="dropdown-item" href="help.html"><i class="fas fa-life-ring"></i>Need Help?</a></li-->
                  <li><a class="dropdown-item" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'logout')) ?>"><i class="fas fa-sign-out-alt"></i>Deconnexion</a></li>
                </ul>
              </li>


              <?php

              }

              ?>

            <!--li class="profile"><a class="pr-0" data-toggle="modal" data-target="#login-modal" href="#" title="Login / Sign up"><span class="d-none d-sm-inline-block">Connexion</span> <span class="user-icon ml-sm-2"><i class="fas fa-user"></i></span></a></li-->




            </ul>
          </nav>
        </div>
      </div>
    </div>
  </header>
  <!-- Header end --> 
  


    <!-- Page Header
  ============================================= -->

  <?php

  if (!empty($lieu)) {

  ?>

  <section class="page-header page-header-text-light bg-secondary">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h1><?php echo $pageTitle ?></h1>
        </div>
        <div class="col-md-4">
          <ul class="breadcrumb justify-content-start justify-content-md-end mb-0">
            <li><a href="<?php echo Router::url('/') ?>">Accueil</a></li>
            <!--li><a href="booking-bus.html">Bus</a></li-->
            <li class="active">Recherche Bus</li>
          </ul>
        </div>
      </div>
    </div>
  </section><!-- Page Header end -->

  <?php

  }

  ?>






<!-- Content
  ============================================= -->
  <div id="content">

    <div id="flashauth"><?php echo $this->Flash->render();?></div>
    <?php
        //echo $this->fetch('navbar');
        echo $this->fetch('content');
        //echo $this->Js->writeBuffer($options = array('safe' => false)); // Écrit les scripts en mémoire cache
    ?>
    
  </div>
  <!-- Content end --> 
  

  <!-- Footer
  ============================================= -->

<footer id="footer" class="bg-white mt-5 pt-4 pb-0 mt-0 shadow-lg">
    <!--section class="section bg-secondary text-light shadow-md pt-4 pb-3" style="background:#003b95!important;">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 col-md-4">
            <div class="featured-box text-center">
              <div class="featured-box-icon"> <i class="fas fa-lock"></i> </div>
              <h4>Paiements 100% Sécurisés</h4>
              <p>Déplacer les détails de votre carte vers un endroit beaucoup plus sécurisé.</p>
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="featured-box text-center">
              <div class="featured-box-icon"> <i class="fas fa-thumbs-up"></i> </div>
              <h4>Paiement de confiance</h4>
              <p>Protection de paiement à 100 %. Politique de retour facile.</p>
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="featured-box text-center">
              <div class="featured-box-icon"> <i class="far fa-life-ring"></i> </div>
              <h4>Assistance 24h/24 et 7j/7</h4>
              <p>Nous sommes là pour vous aider. Vous avez une question et besoin d'aide ? <a href="#">Cliquez ici</a></p>
            </div>
          </div>
        </div>
      </div>
    </section-->
    <div class="container">
      <div class="row g-4">
        <div class="col-sm-6 col-md-5">
          <!--h4 class="text-3 fw-400 mb-3">Services</h4-->
          <img src="<?php echo Router::url(Op::resizedURL('/img/logo.png', array('max-height' => 80, 'quality' => 80, 'space' => true))) ?>" style="display: block;">
          <span class="text-dark"><?php echo str_replace(array(''), array(''), $Textes['about']['Texte']['contenu']) ?></span>
        </div>

        <div class="col-sm-6 col-md">
          <h4 class="text-3 fw-400 mb-3">Service</h4>
          <ul class="nav flex-column">
            <li class="nav-item"> <a class="nav-link text-dark" href="<?php echo Router::url(array('controller' => 'about', 'action' => 'index')) ?>">Qui sommes-nous</a></li>
            <li class="nav-item"> <a class="nav-link text-dark" href="#">Rechercher Bus</a></li>
            <li class="nav-item"> <a class="nav-link text-dark" href="#">Rechercher Hôtels </a></li>
            <li class="nav-item"> <a class="nav-link text-dark" href="#">Rechercher Vols</a></li>
            <li class="nav-item"> <a class="nav-link text-dark" href="#">Terme de référence</a></li>
          </ul>
        </div>


        <?php

        //debug($Textes['phone']['Texte']['contenu']);
        preg_match_all( '#<p[^>]*>(.+?)</p>#', Op::MinifyHtml($Textes['phone']['Texte']['contenu']), $graphPhones);

        $lastgraphPhonesKey = $graphPhones[1];
        $lastgraphPhonesKey = end($lastgraphPhonesKey);
        foreach ($graphPhones[1] as $graphPhonekey => $graphPhone) {
          if($lastgraphPhonesKey == $graphPhonekey){
            $linegraphPhone = 'inline';
          }else{
            $linegraphPhone = 'inline-block';
          }

          $graphPhones[1][$graphPhonekey] = '<a class="nav-link text-dark" href="tel:'.str_replace(array('(',')', ' '), '', Op::HtmltoText(Op::HtmltoText($graphPhone))).'" style="display: '.$linegraphPhone.';">'.Op::HtmltoText($graphPhone).'</a>';
        }

        preg_match_all( '#<p[^>]*>(.+?)</p>#', Op::MinifyHtml($Textes['mail']['Texte']['contenu']), $graphMails);
        foreach ($graphMails[1] as $graphMailkey => $graphMail) {
          $graphMails[1][$graphMailkey] = '<a class="nav-link text-dark" href="mailto:'.Op::HtmltoText(Op::HtmltoText($graphMail)).'" style="display: inline-block;">'.Op::HtmltoText($graphMail).'</a>';
        }

        preg_match_all( '#<p[^>]*>(.+?)</p>#', Op::MinifyHtml($Textes['address']['Texte']['contenu']), $graphAddress);

        ?>


        <div class="col-12 col-lg-3">
          <h4 class="text-3 fw-400 mb-3">Contact</h4>
          <ul class="nav flex-column">
            <li class="nav-item"> <?php echo (implode(' / ', $graphPhones[1])) ?></li>
            <li class="nav-item"> <?php echo (implode(' / ', $graphMails[1])) ?></li>
            <li class="nav-item"> <a class="nav-link text-dark" href="#"><?php echo Op::HtmltoText(implode('<br>', $graphAddress[1])) ?></a></li>
          </ul>
          <!--h4 class="text-3 fw-400 mb-3">Paiement</h4>
          <div class="mb-3">
            <div class="input-group newsletter">
              <input class="form-control" placeholder="Your Email Address" name="newsletterEmail" id="newsletterEmail" type="text">
              <span class="input-group-append">
              <button class="btn btn-secondary" type="submit" data-bs-toggle="tooltip" title="" data-bs-original-title="Subscribe" aria-label="Subscribe"><i class="fas fa-paper-plane"></i></button>
              </span> </div>
            <div class="form-text text-muted">Subscribe to receive latest offers and updates.</div>
          </div-->
          <h4 class="text-3 fw-400 mt-3 mb-3">Paiement par</h4>
          <ul class="payments-types">
            <li><a href="#" target="_blank"> <img data-bs-toggle="tooltip" src="<?php echo Router::url(Op::resizedURL('/images/payment/mpesa.png', array('width' => 47, 'height' => 34, 'quality' => 80, 'space' => true))) ?>" alt="visa" title="" data-bs-original-title="Visa" aria-label="Visa"></a></li>
            <li><a href="#" target="_blank"> <img data-bs-toggle="tooltip" src="<?php echo Router::url(Op::resizedURL('/images/payment/airtelmoney.png', array('width' => 47, 'height' => 34, 'quality' => 80, 'space' => true))) ?>" alt="visa" title="" data-bs-original-title="Visa" aria-label="Visa"></a></li>
            <li><a href="#" target="_blank"> <img data-bs-toggle="tooltip" src="<?php echo Router::url(Op::resizedURL('/images/payment/orangemoney.png', array('width' => 47, 'height' => 34, 'quality' => 80, 'space' => true))) ?>" alt="visa" title="" data-bs-original-title="Visa" aria-label="Visa"></a></li>

            <li><a href="#" target="_blank"> <img data-bs-toggle="tooltip" src="<?php echo Router::url('/images/payment/visa.png') ?>" alt="visa" title="" data-bs-original-title="Visa" aria-label="Visa"></a></li>
            <li><a href="#" target="_blank"> <img data-bs-toggle="tooltip" src="<?php echo Router::url('/images/payment/paypal.png') ?>" alt="paypal" title="" data-bs-original-title="PayPal" aria-label="PayPal"></a></li>
            <li><a href="#" target="_blank"> <img data-bs-toggle="tooltip" src="<?php echo Router::url('/images/payment/mastercard.png') ?>" alt="mastercard" title="" data-bs-original-title="American Express" aria-label="American Express"></a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright pt-4 mt-4">
      <div class="container">
        <div class="row">
          <div class="col-lg d-flex align-items-center">
            <p class="copyright-text text-light">Copyright © <?php echo date('Y') ?> <a href="#" class="text-white"><b>Transnumerica</b></a>. All Rights Reserved.</p>
          </div>
          <div class="col-lg d-flex align-items-center align-items-lg-end flex-column">
            <ul class="social-icons social-icons-sm social-icons-colored">
              <li class="social-icons-facebook"><a data-bs-toggle="tooltip" href="http://www.facebook.com/" target="_blank" title="" data-bs-original-title="Facebook" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
              <li class="social-icons-twitter"><a data-bs-toggle="tooltip" href="http://www.twitter.com/" target="_blank" title="" data-bs-original-title="Twitter" aria-label="Twitter"><i class="fab fa-twitter"></i></a></li>
              <li class="social-icons-linkedin"><a data-bs-toggle="tooltip" href="http://www.linkedin.com/" target="_blank" title="" data-bs-original-title="Linkedin" aria-label="Linkedin"><i class="fab fa-linkedin-in"></i></a></li>
              <li class="social-icons-youtube"><a data-bs-toggle="tooltip" href="http://www.youtube.com/" target="_blank" title="" data-bs-original-title="Youtube" aria-label="Youtube"><i class="fab fa-youtube"></i></a></li>
              <li class="social-icons-instagram"><a data-bs-toggle="tooltip" href="http://www.instagram.com/" target="_blank" title="" data-bs-original-title="Instagram" aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer>


  <!--footer id="footer">
    <section class="section bg-secondary text-light shadow-md pt-4 pb-3" style="background:#003b95!important;">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 col-md-4">
            <div class="featured-box text-center">
              <div class="featured-box-icon"> <i class="fas fa-lock"></i> </div>
              <h4>Paiements 100% Sécurisés</h4>
              <p>Déplacer les détails de votre carte vers un endroit beaucoup plus sécurisé.</p>
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="featured-box text-center">
              <div class="featured-box-icon"> <i class="fas fa-thumbs-up"></i> </div>
              <h4>Paiement de confiance</h4>
              <p>Protection de paiement à 100 %. Politique de retour facile.</p>
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="featured-box text-center">
              <div class="featured-box-icon"> <i class="far fa-life-ring"></i> </div>
              <h4>Assistance 24h/24 et 7j/7</h4>
              <p>Nous sommes là pour vous aider. Vous avez une question et besoin d'aide ? <a href="#">Cliquez ici</a></p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <div class="container mt-4">
      <div class="row">
        <div class="col-md-4 mb-3 mb-md-0">
          <p>Paiement</p>
          <ul class="payments-types">
            <li><a href="#" target="_blank"> <img data-toggle="tooltip" src="<?php echo Router::url('/images/payment/visa.png') ?>" alt="visa" title="Visa"></a></li>
            <li><a href="#" target="_blank"> <img data-toggle="tooltip" src="<?php echo Router::url('/images/payment/discover.png') ?>" alt="discover" title="Discover"></a></li>
            <li><a href="#" target="_blank"> <img data-toggle="tooltip" src="<?php echo Router::url('/images/payment/paypal.png') ?>" alt="paypal" title="PayPal"></a></li>
            <li><a href="#" target="_blank"> <img data-toggle="tooltip" src="<?php echo Router::url('/images/payment/american.png') ?>" alt="american express" title="American Express"></a></li>
            <li><a href="#" target="_blank"> <img data-toggle="tooltip" src="<?php echo Router::url('/images/payment/mastercard.png') ?>" alt="discover" title="Discover"></a></li>
          </ul>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">

        </div>
        <div class="col-md-4 d-flex align-items-md-end flex-column">
          <p>Rester en contact</p>
          <ul class="social-icons">
            <li class="social-icons-facebook"><a data-toggle="tooltip" href="http://www.facebook.com/" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
            <li class="social-icons-twitter"><a data-toggle="tooltip" href="http://www.twitter.com/" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a></li>
            <li class="social-icons-google"><a data-toggle="tooltip" href="http://www.google.com/" target="_blank" title="Google"><i class="fab fa-google"></i></a></li>
            <li class="social-icons-linkedin"><a data-toggle="tooltip" href="http://www.linkedin.com/" target="_blank" title="Linkedin"><i class="fab fa-linkedin-in"></i></a></li>
            <li class="social-icons-youtube"><a data-toggle="tooltip" href="http://www.youtube.com/" target="_blank" title="Youtube"><i class="fab fa-youtube"></i></a></li>
            <li class="social-icons-instagram"><a data-toggle="tooltip" href="http://www.instagram.com/" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="footer-copyright">
        <ul class="nav justify-content-center">
          <li class="nav-item"> <a class="nav-link active" href="#">About Us</a> </li>
          <li class="nav-item"> <a class="nav-link" href="#">Faq</a> </li>
          <li class="nav-item"> <a class="nav-link" href="#">Contact</a> </li>
          <li class="nav-item"> <a class="nav-link" href="#">Support</a> </li>
          <li class="nav-item"> <a class="nav-link" href="#">Terms of Use</a> </li>
          <li class="nav-item"> <a class="nav-link" href="#">Privacy Policy</a> </li>
        </ul>
        <p class="copyright-text">Copyright © <?php echo date('Y') ?> <a href="#">Transnumerica</a>. All Rights Reserved.</p>
      </div>
    </div>
  </footer-->
  <!-- Footer end --> 
  
</div>
<!-- Document Wrapper end --> 

<!-- Back to Top
============================================= --> 
<a id="back-to-top" data-toggle="tooltip" title="Back to Top" href="javascript:void(0)"><i class="fa fa-chevron-up"></i></a> 

<!-- Login Modal
=========================== -->
<div id="login-modal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0">
      <div class="modal-body py-4 px-0">
        <button type="button" class="close close-outside" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        <!-- Login Form
        ====================== -->
        <div class="row">
          <div class="col-11 col-md-10 mx-auto">
            <ul class="nav nav-tabs nav-justified mb-4" role="tablist">
              <li class="nav-item"> <a class="nav-link text-5 line-height-3 active">Connexion</a> </li>
              <li class="nav-item"> <a class="nav-link text-5 line-height-3" href="" data-toggle="modal" data-target="#signup-modal" data-dismiss="modal">Inscription</a> </li>
            </ul>
            <p class="text-4 font-weight-300 text-muted text-center mb-4">Nous sommes heureux de vous revoir!</p>
            <form id="loginForm" method="post">
              <div class="form-group">
                <input type="email" class="form-control" id="emailAddress" required placeholder="Mobile or e-mail">
              </div>
              <div class="form-group">
                <input type="password" class="form-control" id="loginPassword" required placeholder="Mot de Passe">
              </div>
              <div class="row my-4">
                <div class="col">
                  <div class="form-check text-2 custom-control custom-checkbox">
                    <input id="remember-me" name="remember" class="custom-control-input" type="checkbox">
                    <label class="custom-control-label" for="remember-me">Se rappeler</label>
                  </div>
                </div>
                <div class="col text-2 text-right"><a class="btn-link" href="" data-toggle="modal" data-target="#forgot-password-modal" data-dismiss="modal">Mot de passe oublié ?</a></div>
              </div>
              <button class="btn btn-primary btn-block my-4" type="submit">Se connecter</button>
            </form>
            <!--div class="d-flex align-items-center my-3">
              <hr class="flex-grow-1">
              <span class="mx-2 text-2 text-muted">Or Login with Social Profile</span>
              <hr class="flex-grow-1">
            </div>
            <div class="d-flex  flex-column align-items-center mb-3">
              <ul class="social-icons social-icons-colored social-icons-circle">
                <li class="social-icons-facebook"><a href="#" data-toggle="tooltip" data-original-title="Log In with Facebook"><i class="fab fa-facebook-f"></i></a></li>
                <li class="social-icons-twitter"><a href="#" data-toggle="tooltip" data-original-title="Log In with Twitter"><i class="fab fa-twitter"></i></a></li>
                <li class="social-icons-google"><a href="#" data-toggle="tooltip" data-original-title="Log In with Google"><i class="fab fa-google"></i></a></li>
                <li class="social-icons-linkedin"><a href="#" data-toggle="tooltip" data-original-title="Log In with Linkedin"><i class="fab fa-linkedin-in"></i></a></li>
              </ul>
            </div-->
            <p class="text-2 text-center mb-0">Vous êtes nouveau sur Transnumérica ? <a class="btn-link" href="" data-toggle="modal" data-target="#signup-modal" data-dismiss="modal">S'inscrire</a></p>
          </div>
        </div>
        <!-- Login Form End --> 
      </div>
    </div>
  </div>
</div>
<!-- Login Modal End -->

<!-- Sign Up Modal
=========================== -->
<div id="signup-modal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0">
      <div class="modal-body py-4 px-0">
        <button type="button" class="close close-outside" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        <!-- Sign Up Form
        ====================== -->
        <div class="row">
          <div class="col-11 col-md-10 mx-auto">
            <ul class="nav nav-tabs nav-justified mb-4" role="tablist">
              <li class="nav-item"> <a class="nav-link text-5 line-height-3" href="" data-toggle="modal" data-target="#login-modal" data-dismiss="modal">Connexion</a> </li>
              <li class="nav-item"> <a class="nav-link text-5 line-height-3 active">Inscription</a> </li>
            </ul>
            <p class="text-4 font-weight-300 text-muted text-center mb-4">On dirait que vous êtes nouveau ici !</p>
            <form id="signupForm" method="post">
              <div class="form-group">
                <input type="text" class="form-control border-2" id="fullName" required placeholder="Nom complet">
              </div>
              <div class="form-group">
                <input type="email" class="form-control border-2" id="emailAddress" required placeholder="E-mail">
              </div>
              <div class="form-group">
                <input type="password" class="form-control border-2" id="loginPassword" required placeholder="Mot de passe">
              </div>
              <div class="form-group my-4">
                <div class="form-check text-2 custom-control custom-checkbox">
                  <input id="agree" name="agree" class="custom-control-input" type="checkbox">
                  <label class="custom-control-label" for="agree">J'accepte les <a href="#">Termes et Conditions</a>.</label>
                </div>
              </div>
              <button class="btn btn-primary btn-block my-4" type="submit">S'inscrire</button>
            </form>
            <!--div class="d-flex align-items-center my-3">
              <hr class="flex-grow-1">
              <span class="mx-2 text-2 text-muted">Or Sign Up with Social Profile</span>
              <hr class="flex-grow-1">
            </div>
            <div class="d-flex  flex-column align-items-center mb-3">
              <ul class="social-icons social-icons-colored social-icons-circle">
                <li class="social-icons-facebook"><a href="#" data-toggle="tooltip" data-original-title="Sign Up with Facebook"><i class="fab fa-facebook-f"></i></a></li>
                <li class="social-icons-twitter"><a href="#" data-toggle="tooltip" data-original-title="Sign Up with Twitter"><i class="fab fa-twitter"></i></a></li>
                <li class="social-icons-google"><a href="#" data-toggle="tooltip" data-original-title="Sign Up with Google"><i class="fab fa-google"></i></a></li>
                <li class="social-icons-linkedin"><a href="#" data-toggle="tooltip" data-original-title="Sign Up with Linkedin"><i class="fab fa-linkedin-in"></i></a></li>
              </ul>
            </div-->
            <p class="text-2 text-center mb-0">Vous avez déjà un compte? <a class="btn-link" href="" data-toggle="modal" data-target="#login-modal" data-dismiss="modal">Se connecter</a></p>
          </div>
        </div>
        <!-- Sign Up Form End --> 
      </div>
    </div>
  </div>
</div>
<!-- Sign Up Modal End -->

<!-- Forgot Password Modal
============================== -->
<div id="forgot-password-modal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0">
      <div class="modal-body py-4 px-0">
        <button type="button" class="close close-outside" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        <!-- Forgot Password Form
        =========================== -->
        <div class="row">
          <div class="col-11 col-md-10 mx-auto">
            <h3 class="text-center mt-3 mb-4">Forgot your password?</h3>
            <p class="text-center text-3 text-muted">Enter your Email or Mobile and we’ll help you reset your password.</p>
            <form id="forgotForm" class="form-border" method="post">
              <div class="form-group">
                <input type="text" class="form-control border-2" id="emailAddress" required placeholder="Enter Email or Mobile Number">
              </div>
              <button class="btn btn-primary btn-block my-4" type="submit">Continue</button>
            </form>
            <p class="text-center mb-0"><a class="btn-link" href="" data-toggle="modal" data-target="#login-modal" data-dismiss="modal">Return to Log In</a> <span class="text-muted mx-3">|</span> <a class="btn-link" href="" data-toggle="modal" data-target="#otp-modal" data-dismiss="modal">Request OTP</a></p>
          </div>
        </div>
        <!-- Forgot Password Form End --> 
      </div>
    </div>
  </div>
</div>
<!-- Forgot Password Modal End -->

<!-- OTP Modal
============================== -->
<div id="otp-modal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0">
      <div class="modal-body py-4 px-0">
        <button type="button" class="close close-outside" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        <!-- OTP Form
        =========================== -->
        <div class="row">
          <div class="col-11 col-md-10 mx-auto">
            <h3 class="text-center mt-3 mb-4">Two-Step Verification</h3>
            <p class="text-center"><img class="img-fluid" src="images/otp-icon.png" alt="verification"></p>
            <p class="text-muted text-3 text-center">Please enter the OTP (one time password) to verify your account. A Code has been sent to <span class="text-dark text-4">+1*******179</span></p>
            <form id="otp-screen" class="form-border" method="post">
              <div class="form-row">
                <div class="col form-group">
                  <input type="text" class="form-control border-2 text-center text-6 px-0 py-2" maxlength="1" required autocomplete="off">
                </div>
                <div class="col form-group">
                  <input type="text" class="form-control border-2 text-center text-6 px-0 py-2" maxlength="1" required autocomplete="off">
                </div>
                <div class="col form-group">
                  <input type="text" class="form-control border-2 text-center text-6 px-0 py-2" maxlength="1" required autocomplete="off">
                </div>
                <div class="col form-group">
                  <input type="text" class="form-control border-2 text-center text-6 px-0 py-2" maxlength="1" required autocomplete="off">
                </div>
              </div>
              <button class="btn btn-primary btn-block shadow-none my-4" type="submit">Verify</button>
            </form>
            <p class="text-2 text-center">Not received your code? <a class="btn-link" href="#">Resend code</a></p>
            <p class="text-2 text-center mb-0"><a class="btn-link" href="#">Call me</a></p>
          </div>
        </div>
        <!-- OTP Form End --> 
      </div>
    </div>
  </div>
</div>
<!-- OTP Modal End -->



<!-- All Others Modal Dialog
============================================= -->

<!-- All Others Modal Dialog end -->



<!-- Script --> 

<?php
    echo $this->Html->script('/jquery/jquery.min.js', array('once' => true));
    echo $this->Html->script('/jquery-ui/jquery-ui.min.js', array('once' => true));
    echo $this->Html->script('/bootstrap/js/bootstrap.bundle.min.js', array('once' => true));
    echo $this->Html->script('/owl.carousel/owl.carousel.min.js', array('once' => true));
    echo $this->Html->script('/bootstrap-spinner/bootstrap-spinner.js', array('once' => true));
    echo $this->Html->script('/daterangepicker/moment.min.js', array('once' => true));
    echo $this->Html->script('/daterangepicker/daterangepicker.js', array('once' => true));
    echo $this->Html->script('/jquery-seat-charts/jquery.seat-charts.min.js', array('once' => true));
?>


<!-- All Others Script JS
============================================= -->

<!-- All Others Script JS end -->


<?php
  echo $this->Html->script('/js/theme.js', array('once' => true));
?>

</body>
</html>

<?php
    //if ($devMode) {
    if (false) {
    echo '<div style="clear:both"></div>';
    echo "La page a mis ";
    echo round(microtime(true) - Configure::read('Time', microtime(true)),3);
    echo " Seconde à se générer";
    }

    if (!$this->request->is('mobile')) echo $this->element('sql_dump');
?>