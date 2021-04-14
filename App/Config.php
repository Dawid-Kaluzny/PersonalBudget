<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{
	/**
	 * Database host
	 * @var string
	 */
	const DB_HOST = '';
	
	/**
	 * Database name
	 * @var string
	 */
	const DB_NAME = '';
	
	/**
	 * Database user
	 * @var string
	 */
	const DB_USER = '';
	
	/**
	 * Database password
	 * @var string
	 */
	const DB_PASSWORD = '';
	
	/**
	 * Show or hide error messages on screen
	 * @var boolean
	 */
	const SHOW_ERRORS = true;
	
	/**
	 * Secret key for hashing
	 * @var string
	 */
	const SECRET_KEY = '';
	
	/**
	 * the SMTP host server to send emails
	 * @var string
	 */
	const SMTP_HOST = '';
	
	/**
	 * SMTP username
	 * @var string
	 */
	const SMTP_USER = '';
	
	/**
	 * SMTP password
	 * @var string
	 */
	const SMTP_PASSWORD = '';
	
	/**
	 * secret recaptcha key
	 * @var string
	 */
	const RECAPTCHA_KEY = '';
}
