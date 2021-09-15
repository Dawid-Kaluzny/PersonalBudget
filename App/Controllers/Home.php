<?php

namespace App\Controllers;

use \App\Auth;
use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{
    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
		$current_user = new Auth();
		
		if($current_user->getUser()) {		
			View::renderTemplate('Home/index.html');
		} else {
			View::renderTemplate('Login/new.html');
		}
    }
}
