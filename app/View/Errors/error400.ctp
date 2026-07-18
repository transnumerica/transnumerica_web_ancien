<?php 

echo $this->set('title_for_layout', __d('translate', '404 Introuvable'));

?>




<div class="afx ajh">
  <div id="aen" class="ain">
    <div class="agh">
      <h2 class="agl">404</h2>
    </div>
    <nav class="aex adv">
      <ul>
        <li><a href="https://www.os-templates.com/premium-website-templates" title="Accueil">Accueil</a></li>
        <li><a href="#" title="404">404</a></li>
      </ul>
    </nav>
  </div>
</div>
<div class="afx ake">
  <div role="main" class="aem">
    <div id="akt" class="ain">
      <h1>404</h1>
      <h2><?php echo  __d($message,'Erreur'); ?></h2>
      <p class="ahz"><strong><?php echo __d('translate', 'Erreur'); ?>: </strong><?php printf(
          __d('cake', 'The requested address %s was not found on this server.'),
          "<strong>'{$url}'</strong>"
        ); ?></p>
    </div>
    <div class="ain"></div>
  </div>
</div>






<?php
if (Configure::read('debug') > 0):
	echo $this->element('exception_stack_trace');
endif;
?>
