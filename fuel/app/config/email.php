<?php
/*
 * E-pasta konfigurācija sistēmai IS "Pilsētas ūdens"
 */
return array(

	/**
	 * Default settings
	 */
	'defaults' => array(

		/**
		 * Mail useragent string
		 */
		'useragent'	=> 'IS PILSETAS UDENS',
		/**
		 * Mail driver (mail, smtp, sendmail, noop)
		 */
		'driver'		=> 'smtp',

		/**
		 * Whether to send as html, set to null for autodetection.
		 */
		'is_html'		=> null,

		/**
		 * Email charset
		 */
		'charset'		=> 'utf-8',

		/**
		 * Wether to encode subject and recipient names.
		 * Requires the mbstring extension: http://www.php.net/manual/en/ref.mbstring.php
		 */
		'encode_headers' => true,

		/**
		 * Ecoding (8bit, base64 or quoted-printable)
		 */
		'encoding'		=> '8bit',

		/**
		 * Email priority
		 */
		'priority'		=> \Email::P_NORMAL,

		/**
		 * Default sender details
		 */
		'from'		=> array(
			'email'		=> false,
			'name'		=> 'IS PILSETAS UDENS',
		),

		/**
		 * Default return path
		 */
		'return_path'   => false,

		/**
		 * Whether to validate email addresses
		 */
		'validate'	=> true,

		/**
		 * Auto attach inline files
		 */
		'auto_attach' => true,

		/**
		 * Auto generate alt body from html body
		 */
		'generate_alt' => true,

		/**
		 * Forces content type multipart/related to be set as multipart/mixed.
		 */
		'force_mixed'   => false,

		/**
		 * Wordwrap size, set to null, 0 or false to disable wordwrapping
		 */
		'wordwrap'	=> 76,

		/**
		 * Path to sendmail
		 */
		'sendmail_path' => '/usr/sbin/sendmail',

		/**
		 * SMTP settings
		 */
		'smtp'	=> array(
			'host'		=> 'ssl://smtp.gmail.com',
			'port'		=> 465,
                        'auth'          => true,
			'username'	=> 'gusevs.aleksandrs@gmail.com',
			'password'	=> 'Complex1ty',
                        'timeout'       => 5
		),

		/**
		 * Newline
		 */
		'newline'	=> "\r\n",

		/**
		 * Attachment paths
		 */
		'attach_paths' => array(
			// absolute path
			'',
			// relative to docroot.
			DOCROOT,
		),
	),

	/**
	 * Default setup group
	 */
	'default_setup' => 'default',

	/**
	 * Setup groups
	 */
	'setups' => array(
		'default' => array(),
	),

);
