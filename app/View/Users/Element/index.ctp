<?php

    $this->set('MenuHome', 'active');

?>

    <!-- Middle Panel
    ============================================= -->
    <div class="col-lg-9">

      <div class="row">

        <?php

        foreach ($AuthUser['Banking'] as $BankingType => $Bankings) {
        ?>

          <?php

          foreach ($Bankings as $Banking) {
            if ($Banking[$BankingType]) {
          ?>


          <!--div class=" col-xl-4 col-md-6 mb-4">
            <div class="card <?php echo mb_strtolower($Banking[$BankingType]['ref']) ?>">
              <div class="card-body text-center shadow-sm rounded">
                <h6 class="mt-4"><?php echo $Banking[$BankingType]['fullname'] ?><strong class="naccount"> (<?php echo $Banking[$BankingType]['ref'] ?>)</strong></h6>

                <h4 class="mt-3 mb-3">
                  <?php echo $Banking['Currency']['symbol'] ?> 50.00 <?php echo $Banking['Currency']['iso'] ?>
                </h4>

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

          <?php
            }
          }

          ?>

        <?php
        }

        ?>


      </div>





    
			<!--div class="row">
				<div class=" col-xl-4 col-md-6 mb-4">
					<div class="card">
						<div class="card-body text-center">
							<span class="btn btn-danger btn-lg btn-rounded">
								<i class="fas fa-dollar-sign"></i>
							</span>
							<h6 class="mt-4"><strong>Epagne</strong></h6>
															<span>No recent transfers</span>
													</div>
					</div>
				</div>

				<div class=" col-xl-4 col-md-6 mb-4">
					<div class="card">
						<div class="card-body text-center">
							<span class="btn btn-success btn-lg btn-rounded"><i class="fas fa-database"></i></span>
							<h6 class="mt-4"><strong>Emprunt</strong></h6>
															<span>No recent withdraw</span>
													</div>
					</div>
				</div>

				<div class=" col-xl-4 col-md-6 mb-4">
					<div class="card">
						<div class="card-body text-center">
							<span class="btn btn-warning btn-lg btn-rounded"><i class="fas fa-bell"></i></span>
							<h6 class="mt-4"><strong>Inconnu</strong></h6>
							<span> 
																	No
																pending ticket	
							</span> 
							<small><a class="text-warning" href="https://royalscripts.com/product/wallet/account/support-ticket">Create Ticket</a></small>
						</div>
					</div>
				</div>
			</div-->

          <!--div class="profile-componet-area">
            <div class="heading-area">
              <h3 class="title">
                Profile Completeness
              </h3>
              <span class="persentence">15%</span>
            </div>

            <div class="content">
              <div class="row">
                <div class="col-lg-3 col-md-6">
                  <div class="single-componet">
                    <div class="icon">
                      <img src="https://royalscripts.com/product/wallet/assets/userpanel/img/phone.png" alt="">
                    </div>
                    <i class="material-icons">
                      check_circle
                      </i>
                    <p>Mobile Added</p>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6">
                  <div class="single-componet">
                    <div class="icon">
                      <img src="https://royalscripts.com/product/wallet/assets/userpanel/img/envelop.png" alt="">
                    </div>
                    <i class="material-icons">
                      check_circle
                      </i>
                    <p>Email Added</p>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6">
                  <a href="https://royalscripts.com/product/wallet/account/bankaccounts" class="single-componet add">
                    <div class="icon">
                      <img src="https://royalscripts.com/product/wallet/assets/userpanel/img/card.png" alt="">
                    </div>
                    <i class="material-icons">
						 
							radio_button_unchecked
						                      </i>
                    <p>Add Card</p>
                  </a>
                </div>
				
                <div class="col-lg-3 col-md-6">
                  <a href="https://royalscripts.com/product/wallet/account/bankaccounts" class="single-componet add">
                    <div class="icon">
						<img src="https://royalscripts.com/product/wallet/assets/userpanel/img/bank.png" alt="">
                    </div>
                    <i class="material-icons">
						 
							radio_button_unchecked
						                      </i>
                    <p>Add Bank Acc</p>
                    </a>
                </div>
              </div>
            </div>
		  </div-->
		  
          <!--div class="transaction-area mt-30">
            <div class="heading-area">
              <h3 class="title">
                Recent Transactions
              </h3>
            </div>
            <div class="content">
              <div class="heading-menu">
				<div class="row">
					<div class="col-md-2 col-2">
					  <h6>
						  Date
					  </h6>
					</div>
					<div class="col-md-3 col-4">
						<h6>
							Action
						</h6>
					</div>
					<div class="col-md-3 col-3">
						<h6>
							Amount 
						</h6>
					</div>
					<div class="col-md-2 col-3">
						<h6>
							Status
						</h6>
					</div>
				  </div>
			  </div>
			  


            <div class="accordion" id="accordionTransactions">

            <h5 class="text-danger text-center pb-3 pt-5 font-italic">No transaction record avialable.</h5>
            </div>

            </div>
          </div-->
        

          
          <!-- Profile Completeness
          =============================== -->
          <!--div class="bg-white shadow-sm rounded p-4 mb-4">
            <h3 class="text-5 font-weight-400 d-flex align-items-center mb-4">Profile Completeness<span class="border text-success rounded-pill font-weight-500 text-2 px-3 py-1 ml-2">50%</span></h3>
            <hr class="mb-4 mx-n4">
            <div class="row profile-completeness">
              <div class="col-sm-6 col-md-3 mb-4 mb-md-0">
                <div class="border rounded text-center px-3 py-4"> <span class="d-block text-10 text-light mt-2 mb-3"><i class="fas fa-mobile-alt"></i></span> <span class="text-5 d-block text-success mt-4 mb-3"><i class="fas fa-check-circle"></i></span>
                  <p class="mb-0">Mobile Added</p>
                </div>
              </div>
              <div class="col-sm-6 col-md-3 mb-4 mb-md-0">
                <div class="border rounded text-center px-3 py-4"> <span class="d-block text-10 text-light mt-2 mb-3"><i class="fas fa-envelope"></i></span> <span class="text-5 d-block text-success mt-4 mb-3"><i class="fas fa-check-circle"></i></span>
                  <p class="mb-0">Email Added</p>
                </div>
              </div>
              <div class="col-sm-6 col-md-3 mb-4 mb-sm-0">
                <div class="border rounded text-center px-3 py-4"> <span class="d-block text-10 text-light mt-2 mb-3"><i class="fas fa-credit-card"></i></span> <span class="text-5 d-block text-light mt-4 mb-3"><i class="far fa-circle "></i></span>
                  <p class="mb-0"><a class="btn-link stretched-link" href="">Add Card</a></p>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="border rounded text-center px-3 py-4"> <span class="d-block text-10 text-light mt-2 mb-3"><i class="fas fa-university"></i></span> <span class="text-5 d-block text-light mt-4 mb-3"><i class="far fa-circle "></i></span>
                  <p class="mb-0"><a class="btn-link stretched-link" href="">Add Bank Account</a></p>
                </div>
              </div>
            </div>
          </div-->
          <!-- Profile Completeness End --> 
          
          <!-- Recent Activity
          =============================== -->
          <div class="bg-white shadow-sm rounded py-4 mb-4">
            <h3 class="text-5 font-weight-400 d-flex align-items-center px-4 mb-4">Activité récente</h3>
            
            <!-- Title
            =============================== -->
            <div class="transaction-title py-2 px-4">
              <div class="row font-weight-00">
                <div class="col-2 col-sm-1 text-center"><span class="">Date</span></div>
                <div class="col col-sm-6">Description</div>
                <div class="col-auto col-sm-1 d-none d-sm-block text-center">Méthode</div>
                <div class="col-auto col-sm-2 d-none d-sm-block text-center">Status</div>
                <div class="col-3 col-sm-2 text-right">Montant</div>
              </div>
            </div>
            <!-- Title End --> 
            
            <!-- Transaction List
            =============================== -->
            <div class="transaction-list">

              <?php

              $Status[-1] = array('name' => 'Echoué', 'text' => 'danger', 'i' => 'fa-times-circle');
              $Status[0] = array('name' => 'En cours', 'text' => 'warning', 'i' => 'fa-ellipsis-h');
              $Status[1] = array('name' => 'success', 'text' => 'success', 'i' => 'fa-check-circle');

              foreach ($Transactions as $key => $Transaction) {

                $amount = $Transaction['amount'];

                //$amount = Op::lessenumber($Transaction['amount'], array('min' => 2));

              ?>

              <div class="transaction-item px-4 py-3" data-toggle="modal" data-target="#transaction-detail<?php echo $Transaction['id'] ?>">
                <div class="row align-items-center flex-row">
                  <div class="col-2 col-sm-1 text-center"> <span class="d-block text-4 font-weight-300"><?php echo CakeTime::format($Transaction['date'], '%d') ?></span> <span class="d-block text-1 font-weight-300 text-uppercase text-nowrap"><?php echo str_replace(' '.date('y'), '', CakeTime::format($Transaction['date'], '%b %y')) ?></span> </div>
                  <div class="col col-sm-6"> <span class="d-block text-4"><?php echo $Transaction['name'] ?>
                    

                  <span class="text-<?php echo $Status[$Transaction['done']]['text'] ?> d-inline d-sm-none text-3" data-toggle="tooltip" data-original-title="<?php echo $Status[$Transaction['done']]['name'] ?>"><i class="fas <?php echo $Status[$Transaction['done']]['i'] ?>"></i></span>


                  </span> <span class="text-muted"><?php echo $Transaction['description'] ?></span> </div>
                  <div class="col-auto col-sm-1 d-none d-sm-block text-center"> <span class="d-block text-2"><?php echo $Transaction['mod'] ?></span></div>
                  
                  <div class="col-auto col-sm-2 d-none d-sm-block text-center text-3"> <span class="text-<?php echo $Status[$Transaction['done']]['text'] ?>" data-toggle="tooltip" data-original-title="<?php echo $Status[$Transaction['done']]['name'] ?>"><i class="fas <?php echo $Status[$Transaction['done']]['i'] ?>"></i></span> </div>

                  <?php

                  $textPrice = 3;
                  $colPrice = 3;
                  if(strlen($amount) >= 9){
                    $textPrice = 1;
                    $colPrice = 5;
                  }

                  ?>

                  <div class="col-5 col-sm-2 text-right text-4"> <span class="text-<?php echo $textPrice ?> text-nowrap"><?php echo $Transaction['sign'].number_format($amount, 2, '.', ' ') ?></span> <span class="text-2 text-uppercase">(<?php echo $Transaction['currency'] ?>)</span> </div>
                </div>
              </div>

              <?php
              }
              ?>

            </div>
            <!-- Transaction List End --> 
            
            <!-- Transaction Item Details Modal
            =========================================== -->

            <?php

            foreach ($Transactions as $key => $Transaction) {

              if($Transaction['ref'] == 'Transfer'){

                $amount = $Transaction['amount'];

                $uid = @$Transaction['data']['Transfer']['uid'];

                if (strlen($uid) == 10) {
                  $uid = substr($uid, 0, 3).'-'.substr($uid, 3, 3).'-'.substr($uid, 6, 4);
                }

                //debug($Transaction['data']); exit();

            ?>

            <div id="transaction-detail<?php echo $Transaction['id'] ?>" class="modal fade" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered transaction-details" role="document">
                <div class="modal-content">
                  <div class="modal-body">
                    <div class="row no-gutters">
                      <div class="col-sm-5 d-flex justify-content-center bg-primary rounded-left py-4">
                        <div class="my-auto text-center">
                          <div class="text-17 text-white my-3"><!--i class="fas fa-building"></i--><img class="rounded-circle" src="<?php echo Router::url(Op::resizedURL($AuthUser['Info']['profil'], array('width' => 100, 'height' => 100, 'quality' => 80, 'space' => false))) ?>" alt=""></div>
                          <h3 class="text-4 text-white font-weight-400 my-3"><?php echo $Transaction['data']['Transfer']['to_firstname'].' '.$Transaction['data']['Transfer']['to_name'] ?></h3>
                          <div class="text-6 font-weight-500 text-white my-4"><?php echo number_format($Transaction['data']['Transfer']['to_amount'], 2, '.', ' ').' '.$Transaction['data']['ToCurrency']['iso'] ?></div>
                          <p class="text-white"><?php echo CakeTime::format($Transaction['date'], '%d %B %Y') ?></p>
                          <p class="text-4 text-white"><?php echo $uid ?></p>
                        </div>
                      </div>
                      <div class="col-sm-7">
                        <h5 class="text-5 font-weight-400 m-3">Détails de la transaction
                          <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        </h5>
                        <hr>
                        <div class="px-3">
                          <ul class="list-unstyled">
                            <li class="mb-2">Montant <span class="float-right text-3"><?php echo number_format($Transaction['data']['Transfer']['from_amount'], 2, '.', ' ').' '.$Transaction['currency'] ?></span></li>
                            <li class="mb-2">Frais <span class="float-right text-3"><?php echo number_format($Transaction['data']['Transfer']['fee'], 2, '.', ' ').' '.$Transaction['currency'] ?></span></li>
                          </ul>
                          <hr class="mb-2">
                          <p class="d-flex align-items-center font-weight-500 mb-4">Montant payé <span class="text-3 ml-auto"><?php echo number_format($Transaction['data']['Transfer']['TG'], 2, '.', ' ').' '.$Transaction['currency'] ?></span></p>
                          <ul class="list-unstyled">
                            <li class="font-weight-500">Envoyé par:</li>
                            <li class="text-muted"><?php echo $Transaction['data']['Transfer']['from_firstname'].' '.$Transaction['data']['Transfer']['from_name'] ?></li>
                          </ul>
                          <ul class="list-unstyled">
                            <li class="font-weight-500">Via:</li>
                            <li class="text-muted"><?php echo $Transaction['data']['Sendmethod']['name'] ?></li>
                          </ul>
                          <ul class="list-unstyled">
                            <li class="font-weight-500">Transaction ID:</li>
                            <li class="text-muted"><?php echo $Transaction['data']['Transaction']['serial'] ?></li>
                          </ul>
                          <ul class="list-unstyled">
                            <li class="font-weight-500">Description:</li>
                            <li class="text-muted">Envoie de <?php echo number_format($Transaction['data']['Transfer']['to_amount'], 2, '.', ' ').' '.$Transaction['data']['ToCurrency']['iso'] ?> à <?php echo $Transaction['data']['Transfer']['to_firstname'].' '.$Transaction['data']['Transfer']['to_name'] ?> le <?php echo CakeTime::format($Transaction['date'], '%A %d %B %Y '.htmlentities('à').' %H:%M') ?></li>
                          </ul>
                          <ul class="list-unstyled">
                            <li class="font-weight-500">Status:</li>
                            <li class="text-muted"><?php echo $Status[$Transaction['done']]['name'] ?><span class="text-<?php echo $Status[$Transaction['done']]['text'] ?> text-3 ml-1"><i class="fas <?php echo $Status[$Transaction['done']]['i'] ?>"></i></span></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <?php

              }elseif($Transaction['ref'] == 'Receive') {

            ?>

            <div id="transaction-detail" class="modal fade" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered transaction-details" role="document">
                <div class="modal-content">
                  <div class="modal-body">
                    <div class="row no-gutters">
                      <div class="col-sm-5 d-flex justify-content-center bg-primary rounded-left py-4">
                        <div class="my-auto text-center">
                          <div class="text-17 text-white my-3"><i class="fas fa-building"></i></div>
                          <h3 class="text-4 text-white font-weight-400 my-3">Envato Pty Ltd</h3>
                          <div class="text-6 font-weight-500 text-white my-4">$557.20</div>
                          <p class="text-white">15 March 2020</p>
                        </div>
                      </div>
                      <div class="col-sm-7">
                        <h5 class="text-5 font-weight-400 m-3">Détails de la transaction
                          <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        </h5>
                        <hr>
                        <div class="px-3">
                          <ul class="list-unstyled">
                            <li class="mb-2">Payment Amount <span class="float-right text-3">$562.00</span></li>
                            <li class="mb-2">Fee <span class="float-right text-3">-$4.80</span></li>
                          </ul>
                          <hr class="mb-2">
                          <p class="d-flex align-items-center font-weight-500 mb-4">Total Amount <span class="text-3 ml-auto">$557.20</span></p>
                          <ul class="list-unstyled">
                            <li class="font-weight-500">Paid By:</li>
                            <li class="text-muted">Envato Pty Ltd</li>
                          </ul>
                          <ul class="list-unstyled">
                            <li class="font-weight-500">Transaction ID:</li>
                            <li class="text-muted">26566689645685976589</li>
                          </ul>
                          <ul class="list-unstyled">
                            <li class="font-weight-500">Description:</li>
                            <li class="text-muted">Envato March 2020 Member Payment</li>
                          </ul>
                          <ul class="list-unstyled">
                            <li class="font-weight-500">Status:</li>
                            <li class="text-muted"><?php echo $Status[$Transaction['done']]['name'] ?><span class="text-<?php echo $Status[$Transaction['done']]['text'] ?> text-3 ml-1"><i class="fas <?php echo $Status[$Transaction['done']]['i'] ?>"></i></span></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <?php

              }

            }

            ?>

            <!-- Transaction Item Details Modal End --> 
            
            <!-- View all Link
            =============================== -->
            <div class="text-center mt-4"><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'transactions')) ?>" class="btn-link text-3">Voir tout<i class="fas fa-chevron-right text-2 ml-2"></i></a></div>
            <!-- View all Link End --> 
            
          </div>
          <!-- Recent Activity End --> 
        </div>
        <!-- Middle Panel End --> 
