<?php

  if(!empty($success)){
    $viewElement = $this->element('./../'.$this->viewPath.'/Element/'.$this->action.'_success');
  }else{
    $viewElement = $this->element('./../'.$this->viewPath.'/Element/'.$this->action);
  }

  extract($this->viewVars);

?>
  <!-- Content
  ============================================= -->
  <div id="content" class="dashbord-content py-5">
    <div class="container">



        <div id="flashauth"><?php echo $this->Flash->render();?></div>





    <?php
        echo $this->Html->css('/owl/assets/owl.carousel.min.css', array('once' => true));
        echo $this->Html->script('/owl/owl.carousel.min.js', array('data-cfasync' => 'false', 'once' => true));
    ?>  

<style type="text/css">
  
  .carousel-wrap {
    width: 1000px;
    margin: auto;
    position: relative;
  }

  /*
  .owl-carousel .owl-nav{
    overflow: hidden;
    height: 0px;
  }
  */

  .owl-theme .owl-dots .owl-dot.active span, 
  .owl-theme .owl-dots .owl-dot:hover span {
      background: #2caae1;
  }


  .owl-carousel .item {
      text-align: center;
  }
  .owl-carousel .nav-btn{
      height: 47px;
      position: absolute;
      width: 26px;
      cursor: pointer;
      top: 100px !important;

      background-color: rgb(0 0 0 / 35%);
      text-align: center;
      color: #fff;
      font-size: 16px;
      width: 36px;
      height: 36px;
      line-height: 36px;
      border-radius: 0.25rem;
      -webkit-transition: all 0.3s ease-in-out;
      transition: all 0.3s ease-in-out;
      -webkit-box-shadow: 0px 5px 15px rgb(0 0 0 / 15%);
      box-shadow: 0px 5px 15px rgb(0 0 0 / 15%);


  }

  .owl-carousel .owl-prev.disabled,
  .owl-carousel .owl-next.disabled{
    pointer-events: none;
    opacity: 0.2;
  }

  .owl-carousel .prev-slide{
      left: -3px;
  }
  .owl-carousel .next-slide{
      right: -3px;
  }
  .owl-carousel .prev-slide:hover{
     background-position: 0px -53px;
  }
  .owl-carousel .next-slide:hover{
    background-position: -24px -53px;
  }

  span.img-text {
    text-decoration: none;
    outline: none;
    transition: all 0.4s ease;
    -webkit-transition: all 0.4s ease;
    -moz-transition: all 0.4s ease;
    -o-transition: all 0.4s ease;
    cursor: pointer;
    width: 100%;
    font-size: 23px;
    display: block;
    text-transform: capitalize;
  }
  span.img-text:hover {
    color: #2caae1;
  }
.owl-stage{
    left:-30px;
}

.owl-nav button{
  display: block;
}

</style>











          <?php

          if (!@$LightBank) {
          ?>

          <div class="row">

          <div class="col-12 col-sm-12 col-lg-12 mb-3">


            <div class="owl-carousel owl-theme" data-items-xs="1" data-items-sm="2" data-items-md="2" data-items-lg="4" data-nav="1" data-stagepadding="30" data-margin="20" data-dots="0">

              <?php

              $BankingActive = true;
              //foreach ($AuthUser['Banking'] as $BankingType => $Bankings) {
              ?>

                <?php

                //foreach ($Bankings as $Banking) {
                  //if ($Banking[$BankingType]) {

                ?>



                <!--div class="col-12 col-sm-12 col-lg-12 mb-4"-->
                  <div class="account-card account-card-primary <?php echo mb_strtolower($Banking[$BankingType]['ref']) ?> rounded p-3 mb-lg-0">
                    <p class="text-4" style="line-height: 1;"><span class="text-4"><?php echo $Banking[$BankingType]['fullname'] ?><br/><span class="text-1 coloriz">(<?php echo $Banking['Type']['fullname'] ?>)</span></p>
                    <p class="d-flex align-items-center"> <!--span class="account-card-expire text-uppercase d-inline-block opacity-7 mr-2">$<br>
                      USD<br>
                      </span--> <span class="text-4 font-weight-500"><?php echo $Banking['Currency']['symbol'] ?> <?php echo number_format($Banking['Banking']['balance'], ($Banking['Currency']['decimal'] ? 2 : 0), ',', ' ') ?> <?php echo $Banking['Currency']['iso'] ?></span> 

                      <?php

                      if ($BankingActive) {
                        $BankingActive = false;
                      ?>

                      <span class="badge badge-warning text-0 font-weight-500 rounded-pill px-2 ml-auto">Active</span>

                      <?php
                      }

                      ?>

                       </p>
                    <p class="d-flex align-items-center m-0"> <span class="text-uppercase opacity-10 coloriz"><?php echo $Banking['Banking']['iban2'] ?></span> <img class="ml-auto" src="<?php echo Router::url(Op::resizedURL('/img/logo.png', array('width' => 48, 'height' => 30, 'quality' => 80, 'space' => true))) ?>" alt="Current" title=""> </p>
                    <div class="account-card-overlay rounded">
                      <a href="#" data-target="#edit-card-details" data-toggle="modal" class="text-light btn-link mx-2"><span class="mr-1"><i class="fas fa-edit"></i></span>Gérer</a>
                      <!--a href="#" class="text-light btn-link mx-2"><span class="mr-1"><i class="fas fa-minus-circle"></i></span>Delete</a-->
                     </div>
                  </div>
                <!--/div-->

                <?php
                  //}
                //}

                ?>

              <?php
              //}

              ?>



                <!--div class="col-12 col-sm-12 col-lg-12 mb-4"-->
                 <a href="" data-target="#add-new-card-details" data-toggle="modal" class="account-card-new d-flex align-items-center rounded h-100 p-3 mb-lg-0">
                  <p class="w-100 text-center line-height-4 m-0"> <span class="text-3"><i class="fas fa-plus-circle"></i></span> <span class="d-block text-body text-3">Nouveau compte</span> </p>
                  </a>
                <!--/div-->

              </div>

            </div>

            </div>


          <?php
          }


          ?>




          <div id="edit-card-details" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title font-weight-400">Modifier</h5>
                  <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body p-4">
                  <!--form id="updateCard" method="post">
                    <div class="form-group">
                      <label for="edircardNumber">Card Number</label>
                      <div class="input-group">
                        <div class="input-group-prepend"> <span class="input-group-text"><img class="ml-auto" src="images/payment/visa.png" alt="visa" title=""></span> </div>
                        <input type="text" class="form-control" data-bv-field="edircardNumber" id="edircardNumber" disabled value="XXXXXXXXXXXX4151" placeholder="Card Number">
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="editexpiryDate">Expiry Date</label>
                          <input id="editexpiryDate" type="text" class="form-control" data-bv-field="editexpiryDate" required value="07/24" placeholder="MM/YY">
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="editcvvNumber">CVV <span class="text-info ml-1" data-toggle="tooltip" data-original-title="For Visa/Mastercard, the three-digit CVV number is printed on the signature panel on the back of the card immediately after the card's account number. For American Express, the four-digit CVV number is printed on the front of the card above the card account number."><i class="fas fa-question-circle"></i></span></label>
                          <input id="editcvvNumber" type="password" class="form-control" data-bv-field="editcvvNumber" required value="321" placeholder="CVV (3 digits)">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="editcardHolderName">Card Holder Name</label>
                      <input type="text" class="form-control" data-bv-field="editcardHolderName" id="editcardHolderName" required value="Smith Rhodes" placeholder="Card Holder Name">
                    </div>
                    <button class="btn btn-primary btn-block" type="submit">Update Card</button>
                  </form-->
                </div>
              </div>
            </div>
          </div>
          <!-- Add New Card Details Modal
          ================================== -->
          <div id="add-new-card-details" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title font-weight-400">Nouveau compte</h5>
                  <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body p-4">


                  <?php echo $this->Form->create(false,  array('type' => 'file', 'id' => 'signupForm', 'novalidate' => false, 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100'))))); ?>

                    <div class="form-group">


                      <?php

                      $BankingTypes = Hash::combine($BankingTypes, '{n}.Type.id', '{n}')

                      ?>

                      <style type="text/css">

                        .radio p {
                            font-size: inherit;
                        }

                      </style>

                      <div class="radio">
                        <?php
                        $BankingType = 'Saving';
                        echo $this->Form->radio('type', array($BankingType => '<span class="text-3 font-weight-500">'.$BankingTypes[$BankingType]['Type']['fullname'].'</span>'), array('legend' => false, 'hiddenField' => false, 'checked' => true)); ?>
                        <?php echo $BankingTypes[$BankingType]['Type']['description'] ?>
                      </div>

                      <br>


                      <div class="radio">
                        <?php
                        $BankingType = 'Bwakisa';
                        echo $this->Form->radio('type', array($BankingType => '<span class="text-3 font-weight-500">'.$BankingTypes[$BankingType]['Type']['fullname'].'</span>'), array('legend' => false, 'hiddenField' => false, 'checked' => false)); ?>
                        <?php echo $BankingTypes[$BankingType]['Type']['description'] ?>
                      </div>

                      <br>


                      <div class="radio">
                        <?php
                        $BankingType = 'Atd';
                        echo $this->Form->radio('type', array($BankingType => '<span class="text-3 font-weight-500">'.$BankingTypes[$BankingType]['Type']['fullname'].'</span>'), array('legend' => false, 'hiddenField' => false, 'checked' => false)); ?>
                        <?php echo $BankingTypes[$BankingType]['Type']['description'] ?>
                      </div>

                      <br>


                      <div class="radio text-muted">
                        <?php
                        $BankingType = 'Current';
                        echo $this->Form->radio('type', array($BankingType => '<span class="text-3 font-weight-500">'.$BankingTypes[$BankingType]['Type']['fullname'].'</span>'), array('legend' => false, 'hiddenField' => false, 'disabled' => true, 'div' => true)); ?>
                        <?php echo $BankingTypes[$BankingType]['Type']['description'] ?>
                      </div>


                      <br>

                      <div class="radio text-muted">
                        <?php
                        $BankingType = 'Loan';
                        echo $this->Form->radio('type', array($BankingType => '<span class="text-3 font-weight-500">'.$BankingTypes[$BankingType]['Type']['fullname'].'</span>'), array('legend' => false, 'hiddenField' => false, 'disabled' => true)); ?>
                        <?php echo $BankingTypes[$BankingType]['Type']['description'] ?>
                      </div>

                    </div>


                    <!-- Button -->
                    <div class="form-group">
                    <label class="col-md-5 control-label"></label>
                    <!--div class="col-md-2">
                    <button type="submit" class="btn btn-info">Next</button>
                    </div-->
                    </div>
                    <?php echo $this->Form->button('<span>Continuer</span>', array('type' => 'submit', 'id' => 'UsernameForm', 'class' => 'btn btn-primary btn-block', 'name' => 'form', 'value' => 'createBankAccount')); ?>

                  <?php echo $this->Form->end(); ?>


                </div>
              </div>
            </div>
          </div>
          <!-- Credit or Debit Cards End --> 





























      <div class="row"> 
        <!-- Left Panel
        ============================================= -->

        <div class="col-lg-3">

          <!-- Profile Details
          =============================== -->
          <!--div class="bg-white shadow-sm rounded text-center p-3 mb-4">
            <div class="profile-thumb mt-3 mb-4"> <img class="rounded-circle" src="<?php echo Router::url(Op::resizedURL($AuthUser['Info']['profil'], array('width' => 100, 'height' => 100, 'quality' => 80, 'space' => false))) ?>" alt="">
              <div class="profile-thumb-edit custom-file bg-primary text-white" data-toggle="tooltip" title="Change Profile Picture"> <i class="fas fa-camera position-absolute"></i>
                <input type="file" class="custom-file-input" id="customFile">
              </div>
            </div>
            <p class="text-3 font-weight-500 mb-2">Bonjour, <?php echo $AuthUser['Info']['fullname'] ?></p>
            <p class="mb-2"><a href="profile.html" class="text-5 text-light" data-toggle="tooltip" title="Edit Profile"><i class="fas fa-edit"></i></a></p>
          </div-->
          <!-- Profile Details End --> 
          

          <!--a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'sendmoney')) ?>" class="btn btn-primary btn-block"><span class="text-2 mr-3"><i class="fas fa-money-bill-wave"></i></span>Envoyer de l'argent</a>
            <hr-->

          <!-- Available Balance
          =============================== -->
          <!--div class="bg-white shadow-sm rounded text-center p-3 mb-4">
            <div class="text-17 text-light my-3"><i class="fas fa-wallet"></i></div>
            <h3 class="text-9 font-weight-400">$2956.00</h3>
            <p class="mb-2 text-muted opacity-8">Available Balance</p>
            <hr class="mx-n3">
            <div class="d-flex"><a href="#" class="btn-link mr-auto">Withdraw</a> <a href="#" class="btn-link ml-auto">Deposit</a></div>
          </div-->
          <!-- Available Balance End --> 
          
          <!-- Need Help?
          =============================== -->
          <!--div class="bg-white shadow-sm rounded text-center p-3 mb-4">
            <div class="text-17 text-light my-3"><i class="fas fa-comments"></i></div>
            <h3 class="text-5 font-weight-400 my-4">Need Help?</h3>
            <p class="text-muted opacity-8 mb-4">Have questions or concerns regrading your account?<br>
              Our experts are here to help!.</p>
            <a href="#" class="btn btn-primary btn-block">Chate with Us</a> </div-->
          <!-- Need Help? End --> 





          <?php

          if (@$LightBank) {
          ?>


            <style type="text/css">
                
              .owl-stage{
              left:-10px;
              }

              .account-card-new {
                  min-height: inherit;
              }

            </style>



            <div class="owl-carousel owl-theme" data-items-xs="1" data-items-sm="1" data-items-md="1" data-items-lg="1" data-nav="1" data-stagepadding="5" data-margin="5" data-dots="0">

              <?php

              $BankingActive = true;
              foreach ($AuthUser['Banking'] as $BankingType => $Bankings) {
              ?>

                <?php

                foreach ($Bankings as $Banking) {
                  if ($Banking[$BankingType]) {

                ?>



                  <div class="account-card account-card-primary <?php echo mb_strtolower($Banking[$BankingType]['ref']) ?> rounded p-3 mb-4 mb-lg-4">
                    <p class="text-4" style="line-height: 1;"><span class="text-4"><?php echo $Banking[$BankingType]['fullname'] ?><br/><span class="text-1 coloriz">(<?php echo $Banking['Type']['fullname'] ?>)</span></p>
                    <p class="d-flex align-items-center"> <!--span class="account-card-expire text-uppercase d-inline-block opacity-7 mr-2">$<br>
                      USD<br>
                      </span--> <span class="text-4 font-weight-500"><?php echo $Banking['Currency']['symbol'] ?> <?php echo number_format($Banking['Banking']['balance'], ($Banking['Currency']['decimal'] ? 2 : 0), ',', ' ') ?> <?php echo $Banking['Currency']['iso'] ?></span> 

                      <?php

                      if ($BankingActive) {
                        $BankingActive = false;
                      ?>

                      <span class="badge badge-warning text-0 font-weight-500 rounded-pill px-2 ml-auto">Active</span>

                      <?php
                      }

                      ?>

                       </p>
                    <p class="d-flex align-items-center m-0"> <span class="text-uppercase opacity-10 coloriz"><?php echo $Banking['Banking']['iban2'] ?></span> <img class="ml-auto" src="<?php echo Router::url(Op::resizedURL('/img/logo.png', array('width' => 48, 'height' => 30, 'quality' => 80, 'space' => true))) ?>" alt="Current" title=""> </p>
                    <div class="account-card-overlay rounded">
                      <a href="#" data-target="#edit-card-details" data-toggle="modal" class="text-light btn-link mx-2"><span class="mr-1"><i class="fas fa-edit"></i></span>Gérer</a>
                      <!--a href="#" class="text-light btn-link mx-2"><span class="mr-1"><i class="fas fa-minus-circle"></i></span>Delete</a-->
                     </div>
                  </div>

                <?php
                  }
                }

                ?>

              <?php
              }

              ?>



                <div class="col-12 col-sm-12 col-lg-12 mb-4" style=" display: grid; min-height: 100px; "> <a href="" data-target="#add-new-card-details" data-toggle="modal" class="account-card-new d-flex align-items-center rounded h-100 p-3 mb-4 mb-lg-4">
                  <p class="w-100 text-center line-height-4 m-0"> <span class="text-3"><i class="fas fa-plus-circle"></i></span> <span class="d-block text-body text-3">Nouveau compte</span> </p>
                  </a> </div>
              </div>


          <?php
          }

          ?>

















          <!--div class="mb-4">
            <div class="card loan">
              <div class="card-body text-center shadow-sm rounded pt-4">
                <h5 class="">Emprunt</h5>

                <h5 class="">
                  $ <?php echo number_format(Op::random_float(array('min' => 30, 'max' => 300, 'cache' => '+2 min')), 0, ',', ' ') ?> USD
                </h5>

                <h5 class="mt-1 mb-1">
                  FC <?php echo number_format(Op::random_float(array('min' => 30, 'max' => 600000, 'cache' => '+2 min')), 0, ',', ' ') ?> CDF
                </h5>

                <div>
                  <span>Numéro de compte : <strong class="naccount">156454548854</strong></span>                  
                </div>
              </div>
            </div>


            <div class="card">

              <div class="row">

                <div class=" col-xl-6 col-md-6 mb-6">

                  <div class="card-body text-center">
                    <span class="btn btn-danger btn-rounded rounded-small">
                    <i class="fas fa-plus"></i>
                    </span>
                    <h6 class="mt-4"><strong>Depot</strong></h6>
                  </div>

                </div>


                <div class=" col-xl-6 col-md-6 mb-6">

                  <div class="card-body text-center">
                    <span class="btn btn-success btn-rounded rounded-small">
                    <i class="fas fa-dollar-sign"></i>
                    </span>
                    <h6 class="mt-4"><strong>Retrait</strong></h6>
                  </div>

                </div>


              </div>

            </div>


          </div-->



          <div class="aside-area mb-4">
            <div class="main-menu" id="sidemenu">
              <!--div class="current-balance">
                <div class="content">
                  <p class="amount">$50</p>
                    <span class="label">Current Balance</span>
                    <a href="javasctipt:;" class="text-secondary d-block" data-toggle="modal" data-target="#exampleModalScrollable">
                      <small>My Accounts</small>
                    </a>
                </div>
              </div-->
              <ul class="nav">
                <li class="nav-item">
                  <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'index')) ?>" class="nav-link <?php echo @$MenuHome ?>"><i class="material-icons">home</i>Tableau de bord</a>
                </li>



                <li class="nav-item">
                  <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'deposit')) ?>" class="nav-link <?php echo @$MenuDeposit ?>"><i class="material-icons">collections_bookmark</i>Dépot</a>
                </li>

                <li class="nav-item">
                  <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'send')) ?>" class="nav-link  <?php echo @$MenuSend ?>" ><i class="material-icons">send</i>Transfert</a>
                </li>

                <!--li class="nav-item">
                  <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'saving_create')) ?>" class="nav-link  <?php echo @$MenuSaving ?>" ><i class="material-icons">monetization_on</i>Eparne</a>
                </li-->

                <!--li class="nav-item">
                  <a href="https://royalscripts.com/product/wallet/account/requestmoney" class="nav-link  " ><i class="material-icons">list</i>Emprunt</a>
                </li-->

                <!--li class="nav-item">
                  <a href="https://royalscripts.com/product/wallet/account/transaction/all" class="nav-link  "><i class="material-icons">card_travel</i>Transactions</a>
                </li-->

                <!--li class="nav-item">
                  <a href="https://royalscripts.com/product/wallet/account/exchange" class="nav-link  "><i class="material-icons">autorenew</i>Bureau de change</a>
                </li-->

                <!--li class="nav-item">
                  <a href="https://royalscripts.com/product/wallet/account/invoicelist" class="nav-link  "><i class="material-icons">receipt</i>Invoice</a>
                </li-->

                <!--li class="nav-item">
                  <a href="https://royalscripts.com/product/wallet/account/support-ticket" class="nav-link  "><i class="material-icons">contact_support</i>Ticket</a>
                </li-->

                <!--li class="nav-item">
                  <a href="https://royalscripts.com/product/wallet/account/profile" class="nav-link  "><i class="material-icons">person</i>Account</a>
                </li-->

                <!--li class="nav-item">
                  <a href="https://royalscripts.com/product/wallet/account/bankaccounts" class="nav-link"><i class="material-icons">credit_card</i>Cards &amp; Bank Accounts</a>
                </li-->
              </ul>


            </div>














            <div class="bg-white mt-30 d-none d-md-block">

              <!--h4 class="title">
                  Dépot éffectué
              </h4-->


                    <h3 class="text-5 font-weight-400 d-flex align-items-center px-4 pt-sm-4">Epargne</h3>
                    <hr class="p-0 m-0">

                <?php

                  foreach ($AuthUser['Banking']['Saving'] as $key => $Saving) {
                    //debug($Saving['Saving']['target_amount']);
                    //debug($Saving['Banking']['balance']);
                    //debug($Saving);
                    //exit;
                  ?>
                  <div class="col-lg-12 p-3 pt-sm-4">


                  <?php


                    //debug($Saving);
                    echo $Saving['Saving']['fullname'].' <span class="float-right" style="color: #28a745;">'.$Saving['Saving']['Project']['name'].'</span>';


                    $PercentAmount = $Saving['Banking']['balance'] * 100 / $Saving['Saving']['target_amount'];



                    $begin = $Saving['Banking']['created'];
                    $end = $Saving['Saving']['target_date'];

                    $dayEat = Op::daydiff($begin, date('Y-m-d'));
                    $dayLeft = Op::daydiff(date('Y-m-d'), $end);
                    $dayTotal = Op::daydiff($begin, $end);

                    $PercentTime = $dayEat * 100 / $dayTotal;

                  ?>


                  <?php

                  $PercentAmountClass = array();

                  if ($PercentAmount < 50) {
                    $PercentAmountClass[] = 'bg-danger';
                    $PercentAmountClass[] = 'text-danger';
                  }elseif($PercentAmount < 75) {
                    $PercentAmountClass[] = 'bg-orange';
                    $PercentAmountClass[] = 'text-orange';
                  }else{
                    $PercentAmountClass[] = 'bg-teal';
                    $PercentAmountClass[] = 'text-teal';
                  }

                  ?>


                  <div style="
                      width: 100%;
                      background: #bbb;
                      color: #bbb;
                      position: relative;
                  ">H
                    <div class="<?php echo implode(' ', $PercentAmountClass) ?>" style="
                        /* display: block; */
                        width: <?php echo number_format($PercentAmount, 2, '.', '') ?>%;
                        position: absolute;
                        top: 0;
                    ">.</div>
                    <div style="
                        /* display: block; */
                        width: 100%;
                        /* background: #000000; */
                        color: #ffffff;
                        position: absolute;
                        top: 0;
                        text-align: center;
                        margin: 0;
                    "><?php echo $Saving['Saving']['target_amount']-$Saving['Banking']['balance'].' '.$Saving['Currency']['symbol'].' '.$Saving['Currency']['iso'] ?> restant (<?php echo number_format($PercentAmount, 2, ',', ' ') ?>%)
                    </div>
                  </div>



                  <?php

                  $PercentTimeClass = array();

                  if ($PercentTime < 50) {
                    $PercentTimeClass[] = 'bg-danger';
                    $PercentTimeClass[] = 'text-danger';
                  }elseif($PercentTime < 75) {
                    $PercentTimeClass[] = 'bg-orange';
                    $PercentTimeClass[] = 'text-orange';
                  }else{
                    $PercentTimeClass[] = 'bg-teal';
                    $PercentTimeClass[] = 'text-teal';
                  }

                  ?>


                  <div class="mt-1" style="
                      width: 100%;
                      background: #bbb;
                      color: #bbb;
                      position: relative;
                  ">H
                    <div class="<?php echo implode(' ', $PercentTimeClass) ?>" style="
                        /* display: block; */
                        width: <?php echo number_format($PercentTime, 2, '.', '') ?>%;
                        position: absolute;
                        top: 0;
                    ">.</div>
                    <div style="
                        /* display: block; */
                        width: 100%;
                        /* background: #000000; */
                        color: #ffffff;
                        position: absolute;
                        top: 0;
                        text-align: center;
                        margin: 0;
                    "><?php echo $dayLeft.' jour(s) ' ?> restant (<?php echo number_format($PercentTime, 2, ',', ' ') ?>%)
                    </div>
                  </div>



                  </div>
                  <?php

                  }

                ?>

            </div>





            <div class="bg-white mt-30 d-none d-md-block">

              <!--h4 class="title">
                  Dépot éffectué
              </h4-->


                    <h3 class="text-5 font-weight-400 d-flex align-items-center px-4 pt-sm-4">Bwakisa Carte</h3>
                    <hr class="p-0 m-0">

                <?php

                  foreach ($AuthUser['Banking']['Bwakisa'] as $key => $Bwakisa) {
                    //debug($Saving['Saving']['target_amount']);
                    //debug($Saving['Banking']['balance']);
                    //debug($Saving);
                    //exit;
                  ?>
                  <div class="col-lg-12 p-3 pt-sm-4">


                  <?php

                    $maxBwakisa = 0;
                    foreach ($Bwakisa['Banking']['trans']['in'] as $tra) {
                      if($tra['value'] > $maxBwakisa){
                        $maxBwakisa = $tra['value'];                        
                      }
                    }

                    //debug($Saving);
                    echo $Bwakisa['Bwakisa']['fullname'].' <span class="float-right" style="color: #28a745;">'.number_format($Bwakisa['Banking']['balance'], ($Bwakisa['Currency']['decimal'] ? 2 : 0), ',', ' ') ?> <?php echo $Bwakisa['Currency']['iso'].' ('.$maxBwakisa.')</span>';


                    $PercentAmount = $Bwakisa['Banking']['balance'];


                    $dayEat = count($Bwakisa['Banking']['trans']['in']);
                    $dayTotal = 31;
                    $dayLeft = 31-$dayEat;

                    $PercentTime = $dayEat * 100 / $dayTotal;

                  ?>


                  <?php

                  $PercentAmountClass = array();

                  if ($PercentAmount < 50) {
                    $PercentAmountClass[] = 'bg-danger';
                    $PercentAmountClass[] = 'text-danger';
                  }elseif($PercentAmount < 75) {
                    $PercentAmountClass[] = 'bg-orange';
                    $PercentAmountClass[] = 'text-orange';
                  }else{
                    $PercentAmountClass[] = 'bg-teal';
                    $PercentAmountClass[] = 'text-teal';
                  }

                  ?>



                  <?php

                  $PercentTimeClass = array();

                  if ($PercentTime < 50) {
                    $PercentTimeClass[] = 'bg-danger';
                    $PercentTimeClass[] = 'text-danger';
                  }elseif($PercentTime < 75) {
                    $PercentTimeClass[] = 'bg-orange';
                    $PercentTimeClass[] = 'text-orange';
                  }else{
                    $PercentTimeClass[] = 'bg-teal';
                    $PercentTimeClass[] = 'text-teal';
                  }

                  ?>


                  <div class="mt-1" style="
                      width: 100%;
                      background: #bbb;
                      color: #bbb;
                      position: relative;
                  ">H
                    <div class="<?php echo implode(' ', $PercentTimeClass) ?>" style="
                        /* display: block; */
                        width: <?php echo number_format($PercentTime, 2, '.', '') ?>%;
                        position: absolute;
                        top: 0;
                    ">.</div>
                    <div style="
                        /* display: block; */
                        width: 100%;
                        /* background: #000000; */
                        color: #ffffff;
                        position: absolute;
                        top: 0;
                        text-align: center;
                        margin: 0;
                    "><?php echo $dayLeft.' dépot(s) ' ?> restant (<?php echo number_format($PercentTime, 2, ',', ' ') ?>%)
                    </div>
                  </div>



                  </div>
                  <?php

                  }

                ?>

            </div>







            <div class="bg-white mt-30 d-none d-md-block">

              <div class="col-lg-12 p-3 pt-sm-3" style=" display: grid;">
               <a href="" data-target="#add-new-card-details" data-toggle="modal" class="d-flex align-items-center rounded h-100 p-0 mb-lg-0">
                <p class="w-100 text-center line-height-4 m-0"> <span class="text-3"><i class="fas fa-plus-circle"></i></span> <span class="d-block text-body text-3">Nouveau project</span> </p>
                </a>
              </div>

            </div>




















            <div class="help-box mt-30 d-none d-md-block">
              <div class="icon-area">
                <div class="icon">
                  <img src="<?php echo Router::url('/img/chat-icon.png') ?>" alt="" style="width: 30px">
                </div>
              </div>
              <div class="content">
                <h4 class="title">
                  Besoin d'aide?
                </h4>
                <p class="text">
                  Have questions or concerns
                    regrading your account?
                    Our experts are here to help!.
                </p>
              </div>
            </div>

          </div>
        </div>

      <!-- Button trigger modal -->

  <!-- Modal -->
  <div class="modal fade bd-example-modal-lg" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalScrollableTitle">Accounts Balance</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
            <table class="table table-hover text-left balance-table">
                <thead>
                  <tr>
                    <th scope="col">Currency Name</th>
                    <th scope="col">Currency Code</th>
                    <th scope="col">Balance</th>
                    <th scope="col">Rate</th>
                    <th scope="col">Default</th>
                  </tr>
                </thead>
                <tbody>
                                                    <tr>
                    <td scope="row">
                        <span class="rounded-circle currency-sign-box mr-2">$</span>
                        <span>US Dollar</span>
                    </td>
                    <td>USD</td>
                    <td>$0</td>
                    <td>1</td>
                    <td>
                                                    <span class="btn btn-success btn-sm">Default</span>
                                            </td>
                  </tr>
                
                </tbody>
              </table>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

        <!-- Left Panel End --> 

<style type="text/css">

.current .card-body {
background: #cfe4ff;
    background: #91a5f4;
    background: -moz-linear-gradient(-45deg, #91a5f4 0%, #b08cf9 86%);
    background: -webkit-linear-gradient( 
-45deg, #91a5f4 0%, #b08cf9 86%);
    background: linear-gradient( 
135deg, #91a5f4 0%, #b08cf9 86%);
    background: linear-gradient( 
135deg, hsl(228deg 82% 97%) 0%, hsl(217deg 89% 84%) 86%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#91a5f4', endColorstr='#b08cf9',GradientType=1 );
    transition: all 0.4s ease-in-out;
}

.current .card-body .naccount {
    color: #4285f4;
}

.saving .card-body {
    background: #caffd6;
    background: #cfe4ff;
    background: #91a5f4;
    background: -moz-linear-gradient(-45deg, #91a5f4 0%, #b08cf9 86%);
    background: -webkit-linear-gradient( 
-45deg, #91a5f4 0%, #b08cf9 86%);
    background: linear-gradient( 
135deg, #91a5f4 0%, #b08cf9 86%);
    background: linear-gradient( 
135deg, hsl(228deg 82% 97%) 0%, hsl(116deg 100% 84%) 86%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#91a5f4', endColorstr='#b08cf9',GradientType=1 );
    transition: all 0.4s ease-in-out;
}

.saving .card-body .naccount {
    color: #28a745;
}

</style>        



    <?php

      echo $viewElement;

    ?>
 
      </div>
    </div>
  </div>
  <!-- Content end --> 
  