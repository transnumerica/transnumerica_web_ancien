<?php

/**
 * This is email configuration file.
 *
 * Use it to configure email transports of CakePHP.
 *
 * Email configuration class.
 * You can specify multiple configurations for production, development and testing.
 *
 * transport => The name of a supported transport; valid options are as follows:
 *  Mail - Send using PHP mail function
 *  Smtp - Send using SMTP
 *  Debug - Do not send the email, just return the result
 *
 * You can add custom transports (or override existing transports) by adding the
 * appropriate file to app/Network/Email. Transports should be named 'YourTransport.php',
 * where 'Your' is the name of the transport.
 *
 * from =>
 * The origin email. See CakeEmail::from() about the valid values
 *
 */
class EmailConfig {

	public $default = array(
		'transport' => 'Mail',
		'from' => 'you@localhost',
		//'charset' => 'utf-8',
		//'headerCharset' => 'utf-8',
	);

	public $gmail = array(
        'host' => 'ssl://smtp.gmail.com',
        'port' => 465,
        'transport' => 'Smtp',
        'tls' => false	,
        //'charset' => 'utf-8',
		//'headerCharset' => 'utf-8',
	);


	public $interrdc = array(
        'host' => 'host17.registrar-servers.com',
        'port' => 25,
        'transport' => 'Smtp',
        'tls' => false	,
        'charset' => 'utf-8',
		'headerCharset' => 'utf-8',
	);


	public $cervo = array(
        'host' => 'ssl://web49.lws-hosting.com',
        'port' => 465,
        'transport' => 'Smtp',
        'tls' => false	,
        'charset' => 'utf-8',
		'headerCharset' => 'utf-8',
	);

	public $transnumerica = array(
        'host' => 'ssl://server350.web-hosting.com',
        'port' => 465,
        'transport' => 'Smtp',
        'tls' => false	,
        'charset' => 'utf-8',
		'headerCharset' => 'utf-8',
	);

	public $hostgator = array(
        'host' => 'gator4102.hostgator.com',
        'port' => 25,
        'transport' => 'Smtp',
        'tls' => false	,
        //'charset' => 'utf-8',
		//'headerCharset' => 'utf-8',
	);

	public $mylivehostbox = array(
        'host' => 'mail.heyanobooking.com',
        'port' => 25,
        'transport' => 'Smtp',
        'tls' => false	,
        'charset' => 'utf-8',
		'headerCharset' => 'utf-8',
		'client' => null,
		'log' => false,
	);
	
	public $test = array(
		'transport' => 'debug',
		'from' => array('site@localhost' => 'My Site'),
		'host' => 'localhost',
		'port' => 25,
		'timeout' => 30,
		'username' => 'user',
		'password' => 'secret',
		'client' => null,
		'log' => false,
		//'charset' => 'utf-8',
		//'headerCharset' => 'utf-8',
	);

	public $smtp = array(
		'transport' => 'Smtp',
		'from' => array('site@localhost' => 'My Site'),
		'host' => 'localhost',
		'port' => 25,
		'timeout' => 30,
		'username' => 'user',
		'password' => 'secret',
		'client' => null,
		'log' => false,
		//'charset' => 'utf-8',
		//'headerCharset' => 'utf-8',
	);

	public $fast = array(
		'from' => 'you@localhost',
		'sender' => null,
		'to' => null,
		'cc' => null,
		'bcc' => null,
		'replyTo' => null,
		'readReceipt' => null,
		'returnPath' => null,
		'messageId' => true,
		'subject' => null,
		'message' => null,
		'headers' => null,
		'viewRender' => null,
		'template' => false,
		'layout' => false,
		'viewVars' => null,
		'attachments' => null,
		'emailFormat' => null,
		'transport' => 'Smtp',
		'host' => 'localhost',
		'port' => 25,
		'timeout' => 30,
		'username' => 'user',
		'password' => 'secret',
		'client' => null,
		'log' => true,
		//'charset' => 'utf-8',
		//'headerCharset' => 'utf-8',
	);

    public function __construct() {

		// On ajoute des emails qui sont dans emails business dans la clé Config "Mail" 
		$mail_config = Configure::read('Mail');
		$membres = array_keys($mail_config);
		foreach ($membres as $membre) {
			$config = $mail_config[$membre]['Business']['config'];
			$datas = $mail_config[$membre]['Business'];

			$membre = mb_strtolower($membre);
			$this->{$membre} = $this->{$config};
			foreach ($datas as $key => $val) {
				if (in_array($key, array('username', 'password'))) {
				}elseif (in_array($key, array('email'))) {
					$key = 'from';
				}else{
					continue;
				}
				$this->{$membre}[$key] = $val;
			}
		}

    }

}