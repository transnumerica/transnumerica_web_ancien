<!DOCTYPE html>
<html>
    <head>
        <title>Uploader</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php 
            echo $this->Html->css('/Media/css/app.css');
            echo $this->fetch('css');
        ?>
    </head>
    <body>

	   <?php
            //echo $this->Session->flash('Auth');
            //echo $this->Session->flash();

            echo $this->fetch('content');

            echo $this->Html->script('/media/js/jquery-1.12.4.js');
            echo $this->Html->script('/media/js/jqui.js');
            echo $this->fetch('script');
        ?>
        
    </body>
</html>