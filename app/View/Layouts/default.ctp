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

        echo $this->Html->meta('logo', Router::url(Op::resizedURL('/logo_200.png', array('width' => 120, 'height' => 120, 'quality' => 200, 'space' => true)), true), array('type' => 'icon'));

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
<!--
<link id="color-switcher" type="text/css" rel="stylesheet" href="#" />
    -->



<!-- Web Fonts
============================================= -->
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>

<!-- Stylesheet
============================================= -->
<?php
/*
    echo $this->Html->css('/bootstrap/css/bootstrap.min.css', array('once' => true));
    echo $this->Html->css('/font-awesome/css/all.min.css', array('once' => true));
    echo $this->Html->css('/owl.carousel/assets/owl.carousel.min.css', array('once' => true));
    echo $this->Html->css('/owl.carousel/assets/owl.theme.default.min.css', array('once' => true));
    echo $this->Html->css('/jquery-ui/jquery-ui.css', array('once' => true));
*/
    echo $this->Html->css('/daterangepicker/daterangepicker.css', array('once' => true));
    echo $this->Html->css('/jquery-seat-charts/jquery.seat-charts.css', array('once' => true));
    //echo $this->Html->css('/css/stylesheet.css', array('once' => true));

    ?>

<?php
    echo $this->Html->css('/css/color.css', array('once' => true));
    echo $this->Html->css('/css/colors.min.css', array('once' => true));
    echo $this->Html->css('/css/color-blue.css', array('once' => true));
?>


<!-- Kv Start -->
<!-- Google Web Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> 


<!-- Icon Font Stylesheet -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

<!-- Libraries Stylesheet -->
<link href="/lib/animate/animate.min.css" rel="stylesheet">
<link href="/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


<!-- Customized Bootstrap Stylesheet -->
<link href="/css/bootstrap.min.css" rel="stylesheet">

<!-- Lib stylesheet datepicker -->
<link rel="stylesheet" href="/css/bootstrap-datepicker.min.css">


<!-- Template Stylesheet -->
<link href="/css/style.css" rel="stylesheet">
<link href="/css/style_kv.css?<?php echo time(); ?>" rel="stylesheet">
<link href="/css/seats.css?<?php echo time(); ?>" rel="stylesheet">
<!-- Kv End -->


</head>




<body>

<?php
  // On s'assure que $AuthUser est un tableau pour éviter les erreurs sur les visiteurs non connectés
  $AuthUser = isset($AuthUser) ? $AuthUser : array();

  $user_connected = !empty($AuthUser['Info']['id']);
  
  $user_id        = isset($AuthUser['User']['id']) ? $AuthUser['User']['id'] : null;
  $user_firstname = isset($AuthUser['Info']['firstname']) ? $AuthUser['Info']['firstname'] : '';
  $user_name      = isset($AuthUser['Info']['name']) ? $AuthUser['Info']['name'] : '';
  $user_fullname  = isset($AuthUser['Info']['fullname']) ? $AuthUser['Info']['fullname'] : '';
  $user_phone     = isset($AuthUser['Info']['phone']) ? $AuthUser['Info']['phone'] : '';
  $user_mail      = isset($AuthUser['User']['email']) ? $AuthUser['User']['email'] : '';
?>

<?php
/// A REMETTRE
/*
  $user_connected = !empty($AuthUser['Info']['id']);
  $user_id = $AuthUser['User']['id'];
  $user_firstname = $AuthUser['Info']['firstname'];
  $user_name = $AuthUser['Info']['name'];
  $user_fullname = $AuthUser['Info']['fullname'];
  $user_phone = $AuthUser['Info']['phone'];
  $user_mail = $AuthUser['User']['email'];
  */
?>

<script>
  var user = {
    connected : <?php echo $user_connected?"true":"false"; ?>,
    id: <?php echo '"'.$user_id.'"'; ?>,
    firstname: <?php echo '"'.$user_firstname.'"'; ?>,
    name: <?php echo '"'.$user_name.'"'; ?>,
    fullname: <?php echo '"'.$user_fullname.'"'; ?>,
    phone: <?php echo '"'.$user_phone.'"'; ?>,
    mail: <?php echo '"'.$user_mail.'"'; ?>,
    
  };

  console.log(user);

    
  let KTownsJson = JSON.parse(`<?php echo $KTownsJson??"{}"; ?>`); 
  console.log(KTownsJson);

</script>

<!-- Preloader -->
<!--div id="preloader"><div data-loader="dual-ring"></div></div--><!-- Preloader End -->

<!-- Document Wrapper   
============================================= -->
        <div class="d-none spinner-loading show position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
  
  <!-- Header
  ============================================= -->
  
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->
        
<?php 

  $rubrique = isset($rub)?$rub:'bus';
  

  if (!isset($espaceMarchand)) $espaceMarchand = false;
  if (!isset($merchantServices)) $merchantServices = [];
  

  $titreLogo = [
    "trans"=>$espaceMarchand?"espace":"trans",
    "numerica"=>$espaceMarchand?"marchand":"numerica",
  ];
  
?>
   
       <!-- Navbar & Hero Start -->
       <div class="container-fluid nav-bar nav-stick px-0 px-lg-4 py-2 py-lg-0 <?php echo $espaceMarchand?"bg-black":"bg-primary" ?> border-white border-radius-bottom">
            <div class="d-block px-3 px-lg-5 position-relative">
                <div class="d-flex p-0 m-0 position-relative justify-content-center">

                    <div class="align-items-center d-flex position-absolute top-0 start-0 mt-2">
                        <a href="/" class="d-flex me-1 me-sm-4" title="Transnumerica"><img class="img_logo" src="/img/logo_blanc_pure_icon.png" width="120" height="120"  /> </a>
                        <div class="d-block">
                          <div class="d-flex mt-2">
                            <h1 class="text-primary-clear me-2 font-kopiku titre_logo my-0"><?php echo $titreLogo['trans']; ?></h1> 
                            <h1 class="d-flex overflow-hidden text-white font-kopiku titre_logo my-0"> <?php echo $titreLogo['numerica']; ?></h1>
                            
                          </div>
                          <div class="text-white-grey mt-0" ><?php echo $user_fullname ?></div>
                        </div>
                    </div>
                    
                    <div class="d-flex p-0 align-items-center justify-content-center position-absolute top-0 end-0 mt-lg-4 mt-2">
                      <?php if (!$espaceMarchand && count($merchantServices)>0){ ?>
                        <a href="<?php echo Router::url(array('controller' => 'marchand', 'action' => 'index'), true) ?>" class="d-flex flex-nowrap align-self-center btn btn-white rounded-pill py-2 px-2 px-sm-4 me-1 em1-1  border-grey"><i class="fa fa-store pt-lg-1 me-lg-2"></i><span class="d-none d-lg-inline"></span><span class="d-none d-lg-inline ms-1">Marchand</span></a>
                      <?php }else if($espaceMarchand && count($merchantServices)>0){ ?>
                        <a href="<?php echo Router::url(array('controller' => 'home', 'action' => 'index'), true) ?>" class="d-flex flex-nowrap align-self-center btn btn-white rounded-pill py-2 px-2 px-sm-4 me-1 em1-1  border-grey"><i class="fa fa-arrow-left pt-lg-1 me-lg-2"></i><span class="d-none d-lg-inline"></span><span class="d-none d-lg-inline ms-1">Client</span></a>
                      <?php } ?>

                      <div class="dropdown">
                        <div class="d-flex flex-nowrap align-self-center btn btn-white rounded-pill py-2 px-2 px-sm-4 me-1 em1-1  border-grey" type="button" id="dropdownTelecharger1" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="fa fa-download pt-lg-1 me-lg-2"></i><span class="d-none d-lg-inline">Télécharger</span>
                    
                        </div>
                        <ul class="dropdown-menu" aria-labelledby="dropdownTelecharger1">
                          <li><a class="dropdown-item" href="<?php echo Router::url(['controller' => 'mobile', 'action' => 'android'], true) ?>"><i class="fab fa-android me-2" ></i><span>Android</span></a></li>
                          
                        
                        </ul>
                      </div>
                        

                      <?php if (!$user_connected) { ?>
                        <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'login'), true) ?>" class="d-flex flex-nowrap align-self-center btn btn-white rounded-pill py-2 px-2 px-sm-4 me-1 em1-1  border-grey"><i class="fa fa-user pt-lg-1 me-lg-2"></i><span class="d-none d-lg-inline">Se</span><span class="d-none d-lg-inline ms-1">Connecter</span></a>
                      <?php  }else{ ?>
                        <!-- <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'historique'), true) ?>" class="d-flex flex-nowrap align-self-center btn btn-white rounded-pill py-2 px-4 mx-1 em1-1  border-grey"><i class="fa fa-history pt-lg-1 me-lg-2"></i><span class="d-none d-lg-inline">Historique</span></a> -->
                        <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'logout'), true) ?>" class="d-flex flex-nowrap align-self-center btn btn-white rounded-pill py-2 px-2 px-sm-4 me-1 em1-1  border-grey"><i class="fa fa-power-off pt-lg-1 me-lg-2"></i><span class="d-none d-lg-inline">Se</span><span class="d-none d-lg-inline ms-1">Déconnecter</span></a>
                        
                      <?php  } ?>
                        
                      
                    </div>
                    
                  
                    <div class="p-0 <?php echo $espaceMarchand?"pt-0":"pt-5"; ?> pt-sm-5 cont2-rubriques position-relative">
                        <a href="" class="p-0 pt-0 pt-sm-5 cont1-rubriques ">
                            <nav class="navbar navbar-expand position-relative ">
                                <div class="align-items-center d-flex ">
                                    <a href="/" class="d-flex me-1 me-sm-4" title="Transnumerica"> </a>
                                </div>
                                <div class="dropdown <?php echo $espaceMarchand?"dropdown-rubriques-marchand":"dropdown-rubriques"; ?> mt-2 ">
                                
                                <button class="btn <?php echo $espaceMarchand?"btn-primary bg-black":"btn-primary" ?> dropdown-toggle rounded-pill border-white border-light " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  <?php
                                    $faRubriqueArray = [
                                      'bus'=>'fa-bus',
                                      'vol'=>'fa-plane',
                                      'hotel'=>'fa-hotel',
                                      'train'=>'fa-train',
                                      'taxi'=>'fa-taxi',
                                    ];
                                     
                                    echo '<i class="fa '.$faRubriqueArray[$rubrique].' me-2" ></i><span>'.ucfirst($rubrique).'</span>';
                                    
                                  ?>
                                    
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                      <li class="<?php if($espaceMarchand && !in_array('bus',$merchantServices)) echo 'd-none'; ?>" ><a class="dropdown-item<?php echo $rubrique=="bus"?" active":""; ?>" href="<?php echo Router::url(['controller' => $espaceMarchand?'marchand':'home', 'action' => 'index'], true) ?>"><i class="fa fa-bus me-2" ></i><span>Bus</span></a></li>
                                      <li class="<?php if($espaceMarchand && !in_array('vol',$merchantServices)) echo 'd-none'; ?>" ><a class="dropdown-item<?php echo $rubrique=="vol"?" active":""; ?>" href="<?php echo Router::url(['controller' => $espaceMarchand?'marchand':'home', 'action' => 'vol'], true) ?>"><i class="fa fa-plane me-2" ></i><span>Vol</span></a></li>
                                      <li class="<?php if($espaceMarchand && !in_array('hotel',$merchantServices)) echo 'd-none'; ?>" ><a class="dropdown-item<?php echo $rubrique=="hotel"?" active":""; ?>" href="<?php echo Router::url(['controller' => $espaceMarchand?'marchand':'home', 'action' => 'hotel'], true) ?>"><i class="fa fa-hotel me-2" ></i><span>Hotel</span></a></li>
                                      <li class="<?php if($espaceMarchand && !in_array('train',$merchantServices)) echo 'd-none'; ?>" ><a class="dropdown-item<?php echo $rubrique=="train"?" active":""; ?>" href="<?php echo Router::url(['controller' => $espaceMarchand?'marchand':'home', 'action' => 'train'], true) ?>"><i class="fa fa-train me-2" ></i><span>Train</span></a></li>
                                      <li class="<?php if($espaceMarchand && !in_array('taxi',$merchantServices)) echo 'd-none'; ?>" ><a class="dropdown-item<?php echo $rubrique=="taxi"?" active":""; ?>" href="<?php echo Router::url(['controller' => $espaceMarchand?'marchand':'home', 'action' => 'taxi'], true) ?>"><i class="fa fa-car me-2" ></i><span>Taxi</span></a></li>
                                   
                                    </ul>
                                </div>
                                <!-- <img src="img/logo.png" alt="Logo"> -->
                            </nav>
                        </a>
                        
                    </div>

                  

                </div>

              <?php if(!$espaceMarchand){ ?>

                <nav class="k-d-xs-none navbar navbar-expand p-0 mx-5 mx-md-0 overflow-auto">
                    <div class=" navbar-collapse " id="navbarCollapse">
                        <div class="navbar-nav mx-auto py-0 m-0 pb-4">
                        <?php 
                          function linkRubrique($urlArray, $actif, $titre, $fa){
                            if($actif){
                              return '<a href="'.Router::url($urlArray, true).'" class="align-self-center btn btn-primary rounded-pill py-1 px-2 m-2 em-lg-1-1 border-light border-white"><div class="d-flex flex-nowrap align-items-center text-white m-0 p-0 px-1"><i class="fa '.$fa.' me-2"></i><div class="mt-1">'.$titre.'</div></div></a>';
                            }else{
                              return '<a href="'.Router::url($urlArray, true).'" class="align-self-center btn btn-white rounded-pill py-1 px-2 m-2 em-lg-1-1 border-light border-white"><div class="d-flex flex-nowrap align-items-center text-primary m-0 p-0 px-1"><i class="fa '.$fa.' me-2"></i><div class="mt-1">'.$titre.'</div></div></a>';
                            }
                          }
                        ?>
                            <?php echo linkRubrique('/', $rubrique=='bus', 'Bus', 'fa-bus') ?>
                            <?php echo linkRubrique(['controller' => 'home', 'action' => 'vol'], $rubrique=='vol', 'Vol', 'fa-plane') ?>
                            <?php echo linkRubrique(['controller' => 'home', 'action' => 'hotel'], $rubrique=='hotel', 'Hotel', 'fa-hotel') ?>
                            <?php echo linkRubrique(['controller' => 'home', 'action' => 'train'], $rubrique=='train', 'Train', 'fa-train') ?>
                            <?php echo linkRubrique(['controller' => 'home', 'action' => 'taxi'], $rubrique=='taxi', 'Taxi', 'fa-car') ?>
                           
                            
                        </div>
                        
                    </div>
                </nav>

              <?php } ?>

            </div>
        </div>
        <!-- Navbar & Hero End -->


  <!-- Header end --> 

  <div id="main-wrapper"> 
  


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
            <li><a href="<?php echo Router::url('/', true); ?>">Accueil</a></li>
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


<?php
  if(isset($read_en_savoir_plus) && !$espaceMarchand){
?>

        <!-- Features Start -->
        <div class="container-fluid py-5 mt-4  image-shadow" style="background-color: #f2f2f2dd;">
            <div class="container py-2">
                <div class="text-center mx-auto pb-4 wow fadeInUp" data-wow-delay="0.5s" style="max-width: 800px;">
                    <h1 class="display-5 text-capitalize mb-3 text-primary" >En savoir plus </h1>
                    
                </div>
                
                <div class="wow fadeIn" data-wow-delay="0.7s">
                    <div id="carouselYoutubeControls" class="carousel slide position-relative " data-bs-interval = "false" data-interval = "false" >
                        <div class="carousel-inner position-relative">
                          
                        <?php
                          $is_first = true;
                          foreach ($Publicities as $key => $Publicity) {

                            //$Thumb = $Publicity['Publicity']['cover'];

                        ?>
                           
                            <div class="carousel-item <?php echo $is_first?'active':'' ?> p-2 ">
                              <div class="d-flex youtube ratio ratio-16x9 mx-auto">
                                  <iframe  class="rounded border-white m-0  p-0" style="" allowfullscreen
                                      src="<?php echo $Publicity['Publicity']['youtube']; ?>">
                                  </iframe> 
                              </div>
                              
                            </div>

                        <?php 
                            $is_first = false;
                          } 
                        ?>

                        </div>
                        <div class="d-flex align-items-center position-absolute top-0 start-0 h-100">
                            <div class="d-flex rounded-circle bg-primary btn btn-primary p-0 transparent" data-bs-target="#carouselYoutubeControls" data-bs-slide="prev"><span class="carousel-control-prev-icon m-2" aria-hidden="true"></span></div>
                          
                        </div>
                        <div class="d-flex align-items-center position-absolute top-0 end-0 h-100">
                            <div class="d-flex rounded-circle bg-primary btn btn-primary p-0 transparent nextYoutube" data-bs-target="#carouselYoutubeControls" data-bs-slide="next"><span class="carousel-control-next-icon m-2" aria-hidden="true"></span></div>
                          
                        </div>
                      </div>
                </div>
            </div>
        </div>
        <!-- Features End -->

        

        <!-- About Start -->
        <div class="container-fluid overflow-hidden about py-5">
            <div class=" py-2 mx-2">
                <div class="wow fadeInLeft pb-4 d-flex justify-content-center" data-wow-delay="0.2s">
                    <h1 class="display-5 text-capitalize text-shadow">Planifiez Votre <span class="text-primary">Voyage</span></h1>
                </div>
                <div>
                    <div class="wow fadeIn" data-wow-delay="0.7s">
                        <div class="owl-carousel owl-theme">
                          <div class="item active">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16/9;"
                                    src="../img/town/1/4f5cf6f6df6a426b9c06eaa6018ab9d1.jpg" />
                            </div>
                          </div>
                          <div class="item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16/9;"
                                    src="../img/town/2/7854348339f9607f8f9839802d59a362.jpg" />
                            </div>
                          </div>
                          <div class="item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16/9;"
                                    src="../img/town/3/b068f82ea229edc3be483493d1901b28.jpg" />
                            </div>
                          </div> 
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/4/b161a4fd20c96483563f789428509c35.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/5/a8f3845a2cb5ac61b19680b3d1c222ae.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/6/0e325e32ce6ce1cee117c76580b6cd30.gif" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/7/d758a12c69913d35b38d74c342c09877.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/9/399bf04e1b6845a1616793e22140d585.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/10/69b06935593153645aa4cc679b5c21aa.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/11/527b92d639da1cc525e5c9e37b487f8b.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/12/a933666b3f69d605c1854cd677ee7230.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/13/bdc664f532cd630c6f9e8fb2fe3ff163.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/14/7e7a9dce9b8be3db14496ebe828f4234.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/15/e6bceb3f294c9acce4be23eb5dfd1788.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/16/a318db1d2397dac6f40b9a1cf4b2da3a.jpg" />
                            </div>
                          </div>
                          <div class=" item">
                            <div class="w-100 d-flex justify-content-center">
                                <img class="rounded border-white m-2" style=" aspect-ratio: 16 / 9;"
                                    src="../img/town/17/794ba7578e8ecfbf0780cc928857ce7a.jpg" />
                            </div>
                          </div>
                          
                        </div>
                        
                      </div>
                </div>




                
            </div>
        </div>
        <!-- About End -->


<?php
  }
?>
        
        <!-- Footer Start -->
        <div class="image-shadow container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-4 col-xl-4">
                        <div class="footer-item d-flex flex-column">
                            <div class="footer-item">
                                <h4 class="text-white mb-4">Trans Numerica</h4>
                                <p class="mb-3">Nous sommes une plateforme numérique, specialisée dans la prestation des services en ligne en matière de réservations, achats et paiements securisés par mobile money et autres dans les secteurs du transports, du tourisme et de l'hôtellerie en RDC et dans les pays partenaires.</p>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-4">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4">Liens utiles</h4>
                            <a href="<?php echo Router::url(array('controller' => 'policy', 'action' => 'index'), true) ?>"><i class="fas fa-angle-right me-2"></i> Politique de confidentialité</a>
                            <a href="<?php echo Router::url(array('controller' => 'about', 'action' => 'index'), true) ?>"><i class="fas fa-angle-right me-2"></i> A propos de nous</a>
                            <a href="<?php echo Router::url(['controller' => 'home', 'action' => 'index'], true); ?>"><i class="fas fa-angle-right me-2"></i> Bus</a>
                            <a href="<?php echo Router::url(['controller' => 'home', 'action' => 'hotel'], true); ?>"><i class="fas fa-angle-right me-2"></i> Hotel</a>
                            <!--<a href="#"><i class="fas fa-angle-right me-2"></i> Nous écrire</a>-->
                            <!--<a href="#"><i class="fas fa-angle-right me-2"></i> Les termes et conditions</a>-->
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4 col-xl-4">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4">Contact</h4>
                            <a href="#"><i class="fa fa-map-marker-alt me-2"></i> 67, avenue Masikita Binza UPN, Ngaliema, Kinshasa</a>
                            <a href="mailto:transnumerica@gmail.com"><i class="fas fa-envelope me-2"></i> transnumerica@gmail.com</a>
                            <a href="tel:+243 821 500 289"><i class="fas fa-phone me-2"></i> +243 821 500 289 </a>
                            <a href="tel:+33 768 912 059" class="mb-3"><i class="fas fa-phone me-2"></i> +33 768 912 059</a>
                            <!--
                            <div class="d-flex">
                                <a class="btn btn-secondary btn-md-square rounded-circle me-3" href=""><i class="fab fa-facebook-f text-white"></i></a>
                                <a class="btn btn-secondary btn-md-square rounded-circle me-3" href=""><i class="fab fa-twitter text-white"></i></a>
                                <a class="btn btn-secondary btn-md-square rounded-circle me-3" href=""><i class="fab fa-instagram text-white"></i></a>
                                <a class="btn btn-secondary btn-md-square rounded-circle me-0" href=""><i class="fab fa-linkedin-in text-white"></i></a>
                            </div>
                            -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Copyright Start -->
        <div class="container-fluid copyright py-4">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-md-0">
                        <span class="text-body"><a href="/" class="border-bottom text-white"><i class="fas fa-copyright text-light me-2"></i>tnsarl.com</a>, All right reserved.</span>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- Copyright End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-secondary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

        


</div>
<!-- Document Wrapper end --> 

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
              <li class="nav-item"> <a class="nav-link text-5 line-height-3 active" href="<?php echo Router::url(['controller' => 'users', 'action' => 'login'], true); ?>" >Connexion</a> </li>
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
            <p class="text-2 text-center mb-0">Vous êtes nouveau sur Transnumérica ? <a class="btn-link" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'reg')); ?>" data-toggle="modal" data-target="#signup-modal" data-dismiss="modal">S'inscrire</a></p>
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
              <li class="nav-item"> <a class="nav-link text-5 line-height-3" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'login')); ?>" data-toggle="modal" data-target="#login-modal" data-dismiss="modal">Connexion</a> </li>
              <li class="nav-item"> <a class="nav-link text-5 line-height-3 active" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'reg')); ?>" >Inscription</a> </li>
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
            <p class="text-2 text-center mb-0">Vous avez déjà un compte? <a class="btn-link" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'login')); ?>" data-toggle="modal" data-target="#login-modal" data-dismiss="modal">Se connecter</a></p>
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
            <p class="text-center mb-0"><a class="btn-link" href="<?php echo Router::url(array('controller' => 'users', 'action' => 'login')); ?>" data-toggle="modal" data-target="#login-modal" data-dismiss="modal">Return to Log In</a> <span class="text-muted mx-3">|</span> <a class="btn-link" href="" data-toggle="modal" data-target="#otp-modal" data-dismiss="modal">Request OTP</a></p>
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
            <p class="text-center"><img class="img-fluid" src="/images/otp-icon.png" alt="verification"></p>
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



<!-- Modal facture -->
<div class="modal fade" id="factureModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="factureModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-primary" id="factureModalLabel">Facture <span class="operator-name"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-2 px-5">
          <div class="inner-modal-body container p-4">
            <div>
                
                <div class=" d-flex justify-content-between">
                <div class="" style="color:#000;" ><h4>Trans Numerica</h4></div>
                <div class="">
                    <div class="mb-3 mb-sm-0 d-block text-center">
                        <span class="text-uppercase" style="color:#0008;">Facture:</span>
                        <br/>
                        <span class=" text-3 text-uppercase" style="color:#000; " >ID-<span class="sale_id">256</span></span> 
                    </div>
                </div>
                </div>
                
                <hr class="hr" />

                <div class="details row row-cols-auto justify-content-between">


                </div>

                <hr class="hr" />
                
                <div class="d-flex justify-content-center p-3">
                    <img class="qr_link" src="#" />

                </div>

                <hr class="hr" />

                <div class="d-block text-center p-3">
                    <div class="total_details"></div>
                    <div class="d-block mt-3 py-2"><h3 class="mt-2 px-0 py-2 text-primary border-primary border-light rounded">Total : <span class=" total">150000 FC</span></h3></div>
                </div>

                <div class="py-2 remarques" style="color: #0008;" >
                    <p class="text-justify">**Toujours avoir sur soi un billet imprimé et une pièce d'identité lorsque vous voyagez.</p>
                </div>
                     
                <div class="modal_facture_hide_print">
                  <hr class="hr" />

                  <div class="text-center py-2"><h4>Trans Numerica</h4></div>

                  <hr class="hr" />
                </div>
                
                <div class="py-2 remarques" style="color: #0008;" >
                    <p class="text-justify"><strong>REMARQUE :</strong> Il s'agit d'un reçu généré par ordinateur et ne nécessite pas de signature physique.</p>
                </div>

            </div>
            <script language="javascript">
                function Clickheretoprint()
                { 
                    var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
                        disp_setting+="scrollbars=yes,width=800, height=400, left=100, top=25"; 
                    var content_vlue = document.getElementById("factureModal").innerHTML; 
                    
                    var docprint=window.open("","",disp_setting); 
                    //docprint.document.open(); 
                    docprint.document.write(`<html>
            <head>
                

                <title>Impression</title>
                
                

                <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Rubik:300,300i,400,400i,500,500i,700,700i,900,900i' type='text/css'>
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

                <!-- Libraries Stylesheet -->
                <link href="/lib/animate/animate.min.css" rel="stylesheet">
                <link href="/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


                <!-- Customized Bootstrap Stylesheet -->
                <link href="/css/bootstrap.min.css" rel="stylesheet">

                <!-- Lib stylesheet datepicker -->
                <link rel="stylesheet" href="/css/bootstrap-datepicker.min.css">


                <!-- Template Stylesheet -->
                <link href="/css/style.css" rel="stylesheet">
                <link href="/css/style_kv.css?<?php echo time(); ?>" rel="stylesheet">
                <link href="/css/seats.css?<?php echo time(); ?>" rel="stylesheet">
                <!-- Kv End -->

                <style>
                    ${getComputedStyle(document.documentElement).cssText}
                    @page { size: auto; margin: 5mm; }
                    body { padding: 20px;}

                    .remarques{
                      font-size: 0.5em !important;
                      padding: 0 !important;
                    }

                    .qr_link {width:40%;}
                    
                    
                    .modal_facture_hide_print{display:none;}
                </style>
                
                
                `);          
                    docprint.document.write('</head><body onLoad="self.print()" id="FT">');
                    docprint.document.write(content_vlue); 

                    docprint.document.write(`</body> </html>`);          
                    
                    //console.log(docprint.document.querySelector('.inner-modal-body'));
                    docprint.document.querySelector('.inner-modal-body').classList.remove('container');
                    docprint.document.querySelector('.modal-dialog').classList.remove('modal-dialog');
                    docprint.document.querySelector('.remarques').classList.remove('py-2');
                    
                    

                    //docprint.document.close(); 
                    docprint.focus();
                    /*
                    docprint.print();
                    docprint.close();
                    */

                    
                    setTimeout(() => {
                        docprint.print();
                        //docprint.document.close(); 
                        //docprint.close();
                    }, 500);
                    

                }
            </script>

            <div class="text-center modal_facture_hide_print">
                <a href="javascript:Clickheretoprint()" class="btn btn-primary"><i class="fa fa-print"></i> Imprimer</a>
            </div>
            
                  
          </div>
  
          
  
        </div>
        <div class="modal-footer modal_facture_hide_print">
          <button type="button" class="btn btn-primary px-3  " data-bs-dismiss="modal">Ok</button>
        </div>
    </div>
  </div>
</div>

<!-- Modal facture End -->



<!-- Modal Alert-->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-primary">
      <div class="modal-header">
        <h5 class="modal-title text-white" id="alertModalLabel">Alerte</h5>
        <button type="button" class="btn-close text-white btn-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-white-grey">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-bs-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Alert End -->

<!-- Modal Chargement-->
<div class="modal fade" id="chargementModal" tabindex="-1" aria-labelledby="chargementModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-primary">
      <div class="modal-header">
        <h5 class="modal-title text-white" id="chargementModalLabel">Chargement...</h5>
        <button type="button" class="btn-close text-white btn-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-white-grey">
        <div class="d-flex justify-content-center mb-2">
          <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;" >
            <span class="sr-only">Loading...</span>
          </div>
        </div>
        
        <div class="modal-body-message"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-bs-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Chargement End -->


<!-- All Others Modal Dialog
============================================= -->

<!-- All Others Modal Dialog end -->



<!-- Script --> 
<script src="/js/jquery-3.6.4.min.js"></script>
<?php
    //echo $this->Html->script('/jquery/jquery.min.js', array('once' => true));
    echo $this->Html->script('/jquery-ui/jquery-ui.min.js', array('once' => true));
    //echo $this->Html->script('/bootstrap/js/bootstrap.bundle.min.js', array('once' => true));
    echo $this->Html->script('/bootstrap-spinner/bootstrap-spinner.js', array('once' => true));
    echo $this->Html->script('/daterangepicker/moment.min.js', array('once' => true));
    echo $this->Html->script('/daterangepicker/daterangepicker.js', array('once' => true));
    echo $this->Html->script('/jquery-seat-charts/jquery.seat-charts.min.js', array('once' => true));
?>
<!-- JavaScript Libraries -->

<script src="/js/bootstrap-5.0.0.bundle.min.js"></script>
<script src="/lib/wow/wow.min.js"></script>
<script src="/lib/easing/easing.min.js"></script>
<script src="/lib/waypoints/waypoints.min.js"></script>
<script src="/lib/counterup/counterup.min.js"></script>
<script src="/lib/owlcarousel/owl.carousel.min.js"></script>

<!-- datepicker js -->
<script src="/js/bootstrap-datepicker.min.js"></script>


<!-- Template Javascript -->
<script src="/js/bus-seats-kv.js?<?php echo time(); ?>"></script>
<script src="/js/main.js?<?php echo time(); ?>"></script>

<!-- All Others Script JS
============================================= -->

<!-- All Others Script JS end -->


<?php
  //echo $this->Html->script('/js/theme.js', array('once' => true));
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