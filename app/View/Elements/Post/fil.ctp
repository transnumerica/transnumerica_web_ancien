<?php


    if (!empty($NewsFeed['type'])) {
        $modelRef = ucfirst($NewsFeed['type']);
    }else{
        $modelRef = array_keys($NewsFeed)[0];
        $NewsFeed['type'] = strtolower($modelRef);
    }

    if (empty($NewsFeed['content'])) {
        $NewsFeed['content'] = '';
    }

    if (!empty($NewsFeed[$modelRef])) {
        $NewsFeed = Hash::merge($NewsFeed[$modelRef], $NewsFeed);                    
    }

    if($modelRef == 'Publication'){
        $delname = 'pubdel';
    }else{
        $delname = 'postdel';
    }


    $CommID = $modelRef.''.$NewsFeed['id'];
    $sendComm = 'send-comm-'.$NewsFeed['id'];
    $ChatTxt = 'chat-txt-'.$NewsFeed['id'];


?>

<div class="central-meta item">
    <div class="user-post" id="<?php echo $CommID ?>">
        <div class="friend-info">
            <figure class="<?php echo $NewsFeed['Info']['Role']['class'] ?>">

                        <?php

                        if($NewsFeed['Info']['id'] == $AuthUser['Info']['id']){

                        ?>

                <div class="dropdown tpost">
                    <a class="btn dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti-menu"></i>
                      </a>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenu">

                            <a href="<?php echo Router::url(array('controller' => 'ajax', 'action' => $delname, $NewsFeed['id'])) ?>" class="dropdown-item" type="button">Supprimer</a>
                      </div>
                </div>
                        <?php
                        }
                        ?>



                <div class="author-thmb">
                    <a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'view', 'slug' => mb_strtolower(Inflector::slug($NewsFeed['Info'][$NewsFeed['Info']['ref']]['fullname'])), 'id' => $NewsFeed['Info']['id'])) ?>">            
                        <img src="<?php echo Router::url(Op::resizedURL($NewsFeed['Info'][$NewsFeed['Info']['ref']]['profil'], array('width' => 60, 'height' => 60, 'quality' => 80, 'space' => false))) ?>" alt="">
                    </a>

                    <?php


                    $UserFollowerIds = Hash::extract($AuthUser['Following'], '{n}.following_id');
                    if($NewsFeed['Info'][$NewsFeed['Info']['ref']]['online'] AND $NewsFeed['Info']['User']['last_seen'] > date('Y-m-d h:i:s', strtotime('- 5min')) AND in_array($NewsFeed['Info']['id'], $UserFollowerIds)) {
                    ?>
                    <span class="status f-online"></span>
                    <?php
                    }
                    ?>

                </div>

            </figure>


            <?php

                $pubText = '';
                if ($NewsFeed['type'] == 'publication') {
                    $pubText = ' <span style=" color: #212529; font-weight: 400; ">a publié</span>';
                }

            ?>

            <div class="friend-name">
                <ins><a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'view', 'slug' => mb_strtolower(Inflector::slug($NewsFeed['Info'][$NewsFeed['Info']['ref']]['fullname'])), 'id' => $NewsFeed['Info']['id'])) ?>" title="<?php echo Router::url(array('controller' => 'profil', 'action' => 'view', 'slug' => mb_strtolower(Inflector::slug($NewsFeed['Info'][$NewsFeed['Info']['ref']]['fullname'])), 'id' => $NewsFeed['Info']['id'])) ?>"><?php echo $NewsFeed['Info'][$NewsFeed['Info']['ref']]['fullname'] ?></a><?php echo $pubText ?></ins>
                <span><?php echo CakeTime::timeAgoInWords($NewsFeed['created'],
                array('accuracy' => array('month' => 'month', 'hour' => 'hour', 'week' => 'week', 'day' => 'day'),'format' => '%d %B %Y - %H:%M', 'end' => '+3 month')) ?></span>
            </div>
            <div class="post-meta">


                <div class="description">
                    <?php
                        
                        $content = $NewsFeed['content'];
                        if(!Op::ishtml($content) OR preg_match("/(<p>)[\\x{0590}-\\x{05ff}\\x{0600}-\\x{06ff}]/u", $content)) { 
                            $content = '<p>'.$content.'</p>';
                        }

                        echo $content;
                    ?>

                </div>












                <?php


                if ($NewsFeed['type'] == 'publication') {

                        $tag = array();
                        $tag[] = $NewsFeed['Extension']['Type']['slug'];


                        $iclass = $NewsFeed['Extension']['Type']['iclass'];
                        if (!empty($NewsFeed['Extension']['iclass'])) {
                            $iclass = $NewsFeed['Extension']['iclass'];
                        }


                        $lien = Router::url(array('controller' => 'down', 'action' => 'publication', $NewsFeed['id']));

                    ?>

                    <div class="linked-image align-left" style=" width: 69px; ">
                        <a href="<?php echo $lien ?>" title=""><i class="<?php echo $iclass ?>" style=" font-size: 64px; "></i></a>
                    </div>
                    <div class="detail">
                        <span style=" font-size: 20px; font-weight: 500; "><a href="<?php echo $lien ?>"><?php echo $NewsFeed['name'] ?></a></span>
                        <?php echo $NewsFeed['description'] ?>
                        <!--a href="#" title="">www.sample.com</a-->
                    </div>      

                    <?php

                }elseif (!empty($NewsFeed['File'])) {

                    if (count($NewsFeed['File']) ==  1) {
                    
                    ?>


                        <a class="strip" href="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][0]['media'], array('max-height' => 800, 'quality' => 80, 'space' => true))) ?>" title="" data-strip-group="post<?php echo $NewsFeed['id'] ?>" data-strip-group-options="loop: false">
                            <img src="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][0]['media'], array('max-width' => 350, 'max-height' => 500, 'quality' => 80, 'space' => false))) ?>" alt="">
                        </a>


                    <?php

                    }elseif (count($NewsFeed['File']) ==  2) {
                    
                    ?>

                    <div class="row">
                      <div class="column">

                        <a class="strip" href="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][0]['media'], array('max-height' => 800, 'quality' => 80, 'space' => true))) ?>" title="" data-strip-group="post<?php echo $NewsFeed['id'] ?>" data-strip-group-options="loop: false">
                            <img src="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][0]['media'], array('width' => 350, 'height' => 500, 'quality' => 80, 'space' => false))) ?>" alt="">
                        </a>

                      </div>
                      <div class="column">

                        <a class="strip" href="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][1]['media'], array('max-height' => 800, 'quality' => 80, 'space' => true))) ?>" title="" data-strip-group="post<?php echo $NewsFeed['id'] ?>" data-strip-group-options="loop: false">
                            <img src="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][1]['media'], array('width' => 350, 'height' => 500, 'quality' => 80, 'space' => false))) ?>" alt="">
                        </a>

                      </div>

                    </div>

                    <?php

                    }elseif (count($NewsFeed['File']) ==  3) {
                    
                    ?>

                    <div class="row">
                      <div class="column">

                        <a class="strip" href="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][0]['media'], array('max-height' => 800, 'quality' => 80, 'space' => true))) ?>" title="" data-strip-group="post<?php echo $NewsFeed['id'] ?>" data-strip-group-options="loop: false">
                            <img src="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][0]['media'], array('width' => 350, 'height' => 500, 'quality' => 80, 'space' => false))) ?>" alt="">
                        </a>

                      </div>
                      <div class="column">

                        <a class="strip" href="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][1]['media'], array('max-height' => 800, 'quality' => 80, 'space' => true))) ?>" title="" data-strip-group="post<?php echo $NewsFeed['id'] ?>" data-strip-group-options="loop: false">
                            <img src="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][1]['media'], array('width' => 350, 'height' => 245, 'quality' => 80, 'space' => false))) ?>" alt="">
                        </a>

                        <a class="strip" href="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][2]['media'], array('max-height' => 800, 'quality' => 80, 'space' => true))) ?>" title="" data-strip-group="post<?php echo $NewsFeed['id'] ?>" data-strip-group-options="loop: false">
                            <img src="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][2]['media'], array('width' => 350, 'height' => 245, 'quality' => 80, 'space' => false))) ?>" alt="">
                        </a>

                      </div>

                    </div>

                    <?php

                    }elseif (count($NewsFeed['File']) <=  4) {
                    
                    ?>

                    <div class="row">
                      <div class="column">

                        <a class="strip" href="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][0]['media'], array('max-height' => 800, 'quality' => 80, 'space' => true))) ?>" title="" data-strip-group="post<?php echo $NewsFeed['id'] ?>" data-strip-group-options="loop: false">
                            <img src="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][0]['media'], array('width' => 350, 'height' => 245, 'quality' => 80, 'space' => false))) ?>" alt="">
                        </a>

                        <a class="strip" href="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][1]['media'], array('max-height' => 800, 'quality' => 80, 'space' => true))) ?>" title="" data-strip-group="post<?php echo $NewsFeed['id'] ?>" data-strip-group-options="loop: false">
                            <img src="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][1]['media'], array('width' => 350, 'height' => 245, 'quality' => 80, 'space' => false))) ?>" alt="">
                        </a>

                      </div>
                      <div class="column">

                        <a class="strip" href="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][2]['media'], array('max-height' => 800, 'quality' => 80, 'space' => true))) ?>" title="" data-strip-group="post<?php echo $NewsFeed['id'] ?>" data-strip-group-options="loop: false">
                            <img src="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][2]['media'], array('width' => 350, 'height' => 245, 'quality' => 80, 'space' => false))) ?>" alt="">
                        </a>

                        <a class="strip" href="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][3]['media'], array('max-height' => 800, 'quality' => 80, 'space' => true))) ?>" title="" data-strip-group="post<?php echo $NewsFeed['id'] ?>" data-strip-group-options="loop: false">
                            <img src="<?php echo Router::url(Op::resizedURL($NewsFeed['File'][3]['media'], array('width' => 350, 'height' => 245, 'quality' => 80, 'space' => false))) ?>" alt="">
                        </a>

                      </div>

                    </div>

                    <?php

                    }


                }


                ?>























































                <div class="we-video-info">
                    <ul>
                        <!--li>
                            <span class="views" data-toggle="tooltip" title="views">
                                <i class="fa fa-eye"></i>
                                <ins>1.2k</ins>
                            </span>
                        </li-->

                        <?php

                        $UserLikedList = Hash::extract($NewsFeed['Like'], '{n}.info_id');

                        if (in_array($AuthUser['Info']['id'], $UserLikedList)) {
                            $likeClass = 'like';                            
                            $likeUrlAction = 'unlike';                            
                        }else{
                            $likeClass = '';
                            $likeUrlAction = 'like';                            
                        }

                        ?>

                        <li>
                            <a href="<?php echo Router::url(array('controller' => 'ajax', 'action' => $likeUrlAction, ucfirst($NewsFeed['type']), $NewsFeed['id'])) ?>" class="likebutton">
                                <span class="<?php echo $likeClass ?>" data-toggle="tooltip" title="j'aime">
                                    <i class="fa fa-arrow-up"></i>
                                    <ins><?php echo count($NewsFeed['Like']) ?></ins>
                                </span>
                            </a>
                        </li>

                        <li>
                            <span class="comment" data-toggle="tooltip" title="commentaires">
                                <i class="fa fa-comments-o"></i>
                                <ins><?php echo count($NewsFeed['Comment']) ?></ins>
                            </span>
                        </li>

                        <!--li>
                            <span class="comment" data-toggle="tooltip" title="partage">
                                <i class="fa fa-refresh"></i>
                                <ins>52</ins>
                            </span>
                        </li>

                        <li>
                            <span class="dislike" data-toggle="tooltip" title="dislike">
                                <i class="ti-heart-broken"></i>
                                <ins>200</ins>
                            </span>
                        </li>
                        <li class="social-media">
                            <div class="menu">
                              <div class="btn trigger"><i class="fa fa-share-alt"></i></div>
                              <div class="rotater">
                                <div class="btn btn-icon"><a href="#" title=""><i class="fa fa-html5"></i></a></div>
                              </div>
                              <div class="rotater">
                                <div class="btn btn-icon"><a href="#" title=""><i class="fa fa-facebook"></i></a></div>
                              </div>
                              <div class="rotater">
                                <div class="btn btn-icon"><a href="#" title=""><i class="fa fa-google-plus"></i></a></div>
                              </div>
                              <div class="rotater">
                                <div class="btn btn-icon"><a href="#" title=""><i class="fa fa-twitter"></i></a></div>
                              </div>
                              <div class="rotater">
                                <div class="btn btn-icon"><a href="#" title=""><i class="fa fa-css3"></i></a></div>
                              </div>
                              <div class="rotater">
                                <div class="btn btn-icon"><a href="#" title=""><i class="fa fa-instagram"></i></a>
                                </div>
                              </div>
                                <div class="rotater">
                                <div class="btn btn-icon"><a href="#" title=""><i class="fa fa-dribbble"></i></a>
                                </div>
                              </div>
                              <div class="rotater">
                                <div class="btn btn-icon"><a href="#" title=""><i class="fa fa-pinterest"></i></a>
                                </div>
                              </div>

                            </div>
                        </li-->
                    </ul>
                </div>
            </div>
        </div>
        <div class="coment-area">
            <ul class="we-comet">


                <?php

                    echo $this->element('ajax/comment', array('Comments' => $NewsFeed['Comment']));

                ?>




                <!--li>
                    <div class="comet-avatar">
                        <img src="images/resources/comet-1.jpg" alt="">
                    </div>
                    <div class="we-comment">
                        <div class="coment-head">
                            <h5><a href="time-line.html" title="">Jason borne</a></h5>
                            <span>1 year ago</span>
                            <a class="we-reply" href="#" title="Reply"><i class="fa fa-reply"></i></a>
                        </div>
                        <p>we are working for the dance and sing songs. this video is very awesome for the youngster. please vote this video and like our channel</p>
                    </div>

                </li>
                <li>
                    <div class="comet-avatar">
                        <img src="images/resources/comet-2.jpg" alt="">
                    </div>
                    <div class="we-comment">
                        <div class="coment-head">
                            <h5><a href="time-line.html" title="">Sophia</a></h5>
                            <span>1 week ago</span>
                            <a class="we-reply" href="#" title="Reply"><i class="fa fa-reply"></i></a>
                        </div>
                        <p>we are working for the dance and sing songs. this video is very awesome for the youngster.
                            <i class="em em-smiley"></i>
                        </p>
                    </div>
                </li-->
                <li style="display: none">
                    <a href="#" title="" class="showmore underline">more comments</a>
                </li>
                <li class="post-comment">
                    <div class="comet-avatar">
                        <a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'view', 'slug' => mb_strtolower(Inflector::slug($AuthUser['Account']['fullname'])), 'id' => $AuthUser['Info']['id'])) ?>">
                            <img src="<?php echo Router::url(Op::resizedURL($AuthUser['Account']['profil'], array('width' => 60, 'height' => 60, 'quality' => 80, 'space' => false))) ?>" class="<?php echo $AuthUser['Role']['class'] ?>" alt="">
                        </a>
                    </div>
                    <div class="post-comt-box">

                        <?php echo $this->Form->create(false,  array('type' => 'file', 'novalidate' => true, 'url' => array('controller' => 'users', 'action' => 'login'), 'id' => $sendComm, 'class' => 'wmin-sm-400', 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> 'form-control', 'error' => array('attributes' => array('class' => 'error-message w-100'))))); ?>



                        <?php echo $this->Form->hidden('Comment.ref', array('value' => ucfirst($NewsFeed['type']))); ?>

                        <?php echo $this->Form->hidden('Comment.ref_id', array('value' => $NewsFeed['id'])); ?>

                        <?php

                            echo $this->Form->input('Comment.content', array('type' => 'textarea', 'id' => $ChatTxt, 'data-ref_id' => $NewsFeed['id'], 'rows' => 2, 'required' => 'required', 'placeholder' => 'Votre commetaire...')); 

                        ?>
                        
                            <!--div class="add-smiles">
                                <span class="em em-expressionless" title="add icon"></span>
                            </div>
                            <div class="smiles-bunch">
                                <i class="em em---1"></i>
                                <i class="em em-smiley"></i>
                                <i class="em em-anguished"></i>
                                <i class="em em-laughing"></i>
                                <i class="em em-angry"></i>
                                <i class="em em-astonished"></i>
                                <i class="em em-blush"></i>
                                <i class="em em-disappointed"></i>
                                <i class="em em-worried"></i>
                                <i class="em em-kissing_heart"></i>
                                <i class="em em-rage"></i>
                                <i class="em em-stuck_out_tongue"></i>
                            </div-->

                            <button class="postcomtsub" type="submit" name="form" value="login" title="send"><i class="fa fa-paper-plane" style=" color: #3ba3bd; "></i></button>
                            
                            <?php echo $this->Form->button('', array('type' => 'submit', 'id' => 'UsernameForm', 'name' => 'form', 'value' => 'login')); ?>

                        <?php echo $this->Form->end(); ?>


                    </div>
                </li>
            </ul>
        </div>
    </div>







 
    <?php

        $commgroup = array('i'.$NewsFeed['id'], 'r'.$NewsFeed['type']);
        sort($commgroup);
        $commgroup = serialize($commgroup);

        $commgroup = md5($commgroup);

    ?>

    <script>

        function commfire(){

            var senderfire = <?php echo $AuthUser['Info']['id'] ?>;
            var commgroup = '<?php echo $commgroup ?>';
            var nowtime = "<?php echo $nowtime ?>";

            const db = firebase.database();

            document.getElementById("<?php echo $sendComm ?>").addEventListener("submit", postChat);
            function postChat(e) {
                e.preventDefault();
                var timestamp = (new Date()).getTime();
                var timestamp2 = Math.round(timestamp / 1000);

                const chatTxt = document.getElementById("<?php echo $ChatTxt ?>");
                var message = chatTxt.value;
                chatTxt.value = "";
                db.ref(commgroup + "/" + timestamp).set({
                    usr: senderfire,
                    msg: message,
                    time: timestamp2,
                });

                var data = {'ref_id': "<?php echo $NewsFeed['id'] ?>",  ref: "<?php echo ucfirst($NewsFeed['type']) ?>", 'info_id' : senderfire, 'content' : message, 'timestamp': timestamp2};

                $.ajax({
                    type:"POST",
                    cache:false,
                    url:"<?php echo Router::url(array('controller' => 'ajax', 'action' => 'newcomment')) ?>",
                    data:data,    // multiple data sent using ajax
                    dataType: "json",
                    success: function (data) {
                        if (data) {
                            $("#<?php echo $CommID ?> span.comment ins").html(data.data.count);
                        }
                    }
                });

            }

            const fetchChat = db.ref(commgroup + "/");
            fetchChat.on("child_added", function (snapshot) {
                const messages = snapshot.val();
                var msgfire;

                if (messages.time > nowtime) {

                    if (Object.keys(allUser).includes(''+messages.usr+'')) {
                        cominsert(messages, '<?php echo $CommID ?>');
                    }else{

                        $.ajax({
                            url: "<?php echo $this->base ?>/ajax/getuser/"+messages.usr,
                            async: true,
                            cache: true,
                            context: document.body,
                            dataType: "json",
                            beforeSend : function () {

                            },

                            success : function (data, textStatus, request) {
                                if (data) {
                                    allUser[messages.usr] = data.data;
                                    cominsert(messages, '<?php echo $CommID ?>');
                                }
                            }

                        }).done(function(response) {

                        }).fail(function(jqXHR, textStatus){

                        });

                    }

                }

            });
            
        }

        commfire();

    </script>   


























</div>