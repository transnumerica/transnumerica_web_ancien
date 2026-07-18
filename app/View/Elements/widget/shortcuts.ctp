<div class="widget">
    <h4 class="widget-title">Raccourcis</h4>
    <ul class="naves">

        <!--li>
            <i class="ti-home"></i>
            <a href="inbox.html" title="">Accueil</a>
        </li-->


        <li>
            <i class="ti-user"></i>
            <a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'view', 'slug' => mb_strtolower(Inflector::slug($AuthUser['Account']['fullname'])), 'id' => $AuthUser['Info']['id'])) ?>" title="">Profil</a>
        </li>

        <li>
            <i class="ti-comments"></i>
            <a href="<?php echo Router::url(array('controller' => 'messages', 'action' => 'index')) ?>" title="">Message</a>
        </li>

        <li>
            <i class="fa fa-money"></i>
            <a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'transactions', 'slug' => mb_strtolower(Inflector::slug($AuthUser['Account']['fullname'])), 'id' => $AuthUser['Info']['id'])) ?>" title="">E-Banking</a>
        </li>

        <!--li>
            <i class="fa fa-bar-chart-o"></i>
            <a href="insights.html" title="">Reporting</a>
        </li-->

        <li>
            <i class="fa fa-users"></i>
            <a href="#" title="">Groupes</a>
        </li>

        <!--li>
            <i class="fa fa-tags"></i>
            <a href="insights.html" title="">HashTag</a>
        </li-->


        <li>
            <i class="ti-settings"></i>
            <a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'edit', 'slug' => mb_strtolower(Inflector::slug($AuthUser['Account']['fullname'])), 'id' => $AuthUser['Info']['id'])) ?>" title="">Paramètre</a>
        </li>

        <li>
            <i class="fa fa-sitemap"></i>
            <a href="<?php echo Router::url(array('controller' => 'about', 'action' => 'cgv')) ?>" title="">Conditions Générales</a>
        </li>

    </ul>
</div><!-- Shortcuts -->