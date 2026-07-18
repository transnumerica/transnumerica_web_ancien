<style type="text/css">

.sidebar {
  display: inherit!important;
}

.widget-title {
  float: none!important;
}

</style>


    <link rel="stylesheet" href="<?php echo Router::url('/css/owl.theme.default.min.css') ?>">



                  <div class="widget">

                    <h4 class="widget-title">Suggestion</h4>

                      <div class="owl-carousel owl-theme">
                        

                        <?php
                        foreach ($SuggestionUsers as $Role_id => $GroupUsers) {

                        ?>

                        <div class="item">

                          <ul class="followers">

                            <?php
                            foreach ($GroupUsers as $SugUser) {
                              $AccountRef = $SugUser['Info']['ref'];
                            ?>

                            <li>
                              <figure class="<?php echo $SuggestionRoles[$Role_id]['Role']['class'] ?>"><a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'view', 'slug' => mb_strtolower(Inflector::slug($SugUser[$AccountRef]['fullname'])), 'id' => $SugUser['Info']['id'])) ?>"><img src="<?php echo Router::url(Op::resizedURL($SugUser[$AccountRef]['profil'], array('width' => 45, 'height' => 45, 'quality' => 80, 'space' => false))) ?>" alt=""></a></figure>
                              <div class="friend-meta">
                                <h4><a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'view', 'slug' => mb_strtolower(Inflector::slug($SugUser[$AccountRef]['fullname'])), 'id' => $SugUser['Info']['id'])) ?>" title=""><?php echo $SugUser[$AccountRef]['fullname'] ?></a></h4>
                                <a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'following', 'slug' => mb_strtolower(Inflector::slug($SugUser[$AccountRef]['fullname'])), 'id' => $SugUser['Info']['id'])) ?>" title="" class="underline">Suivre</a>
                              </div>
                            </li>

                            <?php
                            }
                            ?>

                          </ul>
      
                        </div>


                        <?php
                        }
                        ?>


                      </div>


                  </div><!-- who's following -->


<div class="widget">
    <h4 class="widget-title">Université</h4> 


    <?php

    if (!empty($Schools['School'])) {
      $Schools = array($Schools);
    }

    foreach ($Schools as $key => $School) {

      $AccountRef = $School['Info']['ref'];

      //debug($School);

    ?>


    <div class="your-page">
        <figure style=" width: 68px; max-width: inherit; ">
            <a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'view', 'slug' => mb_strtolower(Inflector::slug($School[$AccountRef]['fullname'])), 'id' => $School['Info']['id'])) ?>" title=""><img style=" border-radius: 0; " src="<?php echo Router::url(Op::resizedURL($School[$AccountRef]['profil'], array('width' => 70, 'height' => 80, 'quality' => 80, 'space' => true))) ?>" alt=""></a>
        </figure>
        <div class="page-meta">
            <a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'view', 'slug' => mb_strtolower(Inflector::slug($School[$AccountRef]['fullname'])), 'id' => $School['Info']['id'])) ?>" title="" class="underline"><?php echo $School['School']['name'] ?></a>
            <span><i class="fa fa-users"></i><a href="#" title="">Etudiants <em><?php echo $School['School']['student_count'] ?></em></a></span>
            <span><i class="fa fa-graduation-cap"></i><a href="#" title="">Formés <em><?php echo $School['School']['trained_count'] ?></em></a></span>
            <span><i class="ti-location-pin"></i><a href="#" title=""><?php echo $School['School']['town'] ?></a></span>
            <!--span><i class="ti-bell"></i><a href="#" title="">Notifications <em>2</em></a></span-->
        </div>
        <div class="page-likes">
            <!--ul class="nav nav-tabs likes-btn">
                <li class="nav-item"><a class="active" href="#link1" data-toggle="tab">j'aime</a></li>
                 <li class="nav-item"><a class="" href="#link2" data-toggle="tab">écrire</a></li>
                 <li><a class="" href="#link2">écrire</a></li>
            </ul-->
            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane active fade show " id="link1" >
                  <!--a href="#" title="weekly-likes">35 nouveaux "j'aime" cette semaine</a-->
                  <div class="users-thumb-list">

                  	<?php


                    $i = 0;
                  	foreach ($School['User'] as $key => $Student) {

                      $AccountRef = $Student['Info']['ref'];


                      if ($i == 10) {
                        break;
                      }

                      $i ++;


                  	?>

                      <a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'view', 'slug' => mb_strtolower(Inflector::slug($Student[$AccountRef]['fullname'])), 'id' => $Student['Info']['id'])) ?>" title="<?php echo $Student[$AccountRef]['fullname'] ?>" data-toggle="tooltip">
                          <img src="<?php echo Router::url(Op::resizedURL($Student['Info']['profil'], array('width' => 60, 'height' => 60, 'quality' => 80, 'space' => false))) ?>" alt="" class="<?php echo $Student['Role']['class'] ?>">  
                      </a>

                  	<?php

                  	}

                  	?>

                  </div>
              </div>
              <!--div class="tab-pane fade" id="link2" >
                  <span><i class="ti-eye"></i>440</span>
                  <a href="#" title="weekly-likes">440 new views this week</a>
                  <div class="users-thumb-list">
                    <a href="#" title="Anderw" data-toggle="tooltip">
                        <img src="images/resources/userlist-1.jpg" alt="">  
                    </a>
                    <a href="#" title="frank" data-toggle="tooltip">
                        <img src="images/resources/userlist-2.jpg" alt="">  
                    </a>
                    <a href="#" title="Sara" data-toggle="tooltip">
                        <img src="images/resources/userlist-3.jpg" alt="">  
                    </a>
                    <a href="#" title="Amy" data-toggle="tooltip">
                        <img src="images/resources/userlist-4.jpg" alt="">  
                    </a>
                    <a href="#" title="Ema" data-toggle="tooltip">
                        <img src="images/resources/userlist-5.jpg" alt="">  
                    </a>
                    <a href="#" title="Sophie" data-toggle="tooltip">
                        <img src="images/resources/userlist-6.jpg" alt="">  
                    </a>
                    <a href="#" title="Maria" data-toggle="tooltip">
                        <img src="images/resources/userlist-7.jpg" alt="">  
                    </a>  
                  </div>
              </div-->
            </div>
        </div>
    </div>

    <?php

    }


    ?>


</div><!-- page like wi -->
