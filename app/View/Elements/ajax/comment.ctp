<?php
foreach ($Comments as $key => $Comment) {

    if (!empty($Comment['Comment'] )) {
        $Comment = Hash::merge($Comment['Comment'], $Comment);
    }

    $AccountRef = $Comment['Info']['ref'];

?>


<li>
    <div class="comet-avatar <?php echo $Comment['Info']['Role']['class'] ?>">
        <a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'view', 'slug' => mb_strtolower(Inflector::slug($Comment['Info'][$AccountRef]['fullname'])), 'id' => $Comment['Info']['id'])) ?>">
            <img src="<?php echo Router::url(Op::resizedURL($Comment['Info'][$AccountRef]['profil'], array('width' => 60, 'height' => 60, 'quality' => 80, 'space' => false))) ?>" class="<?php echo $Comment['Info']['Role']['class'] ?>" alt="">
        </a>
    </div>
    <div class="we-comment">
        <div class="coment-head">
            <h5><a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'view', 'slug' => mb_strtolower(Inflector::slug($Comment['Info'][$AccountRef]['fullname'])), 'id' => $Comment['Info']['id'])) ?>" title=""><?php echo $Comment['Info'][$AccountRef]['fullname'] ?></a></h5>
            <span><?php echo CakeTime::timeAgoInWords($Comment['created'], array('accuracy' => array('month' => 'month', 'hour' => 'hour', 'week' => 'week', 'day' => 'day'),'format' => '%d %B %Y - %H:%M', 'end' => '+3 month')) ?></span>
            <!--a class="we-reply" href="#" title="Reply"><i class="fa fa-reply"></i></a-->
        </div>
        <?php echo $Comment['content'] ?>
    </div>
</li>


<?php
}

?>
