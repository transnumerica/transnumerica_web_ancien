<?php
echo 'Une requête pour réinitialiser votre mot de passe a été envoyé. Pour changer votre mot de passe, cliquez sur le lien ci-dessous.';
echo "\n";
echo Router::url(array('controller' => 'compte', 'action' => 'reset_password', $token), true);
