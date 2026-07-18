<?php
echo 'Une requête pour réinitialiser votre mot de passe a été envoyée. Pour changer votre mot de passe, cliquez sur le lien ci-dessous.';
echo "<br>";
echo '<a href='.Router::url(array('controller' => 'users', 'action' => 'reset_password', $token), true).'>'.Router::url(array('controller' => 'users', 'action' => 'reset_password', $token), true).'</a>';
