<?php

namespace App\Controllers;

use \App\Auth;
use \Core\View;
use App\Models\Quote;

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
			$quote = new Quote();
			$quotes = $quote->getQuotes(3);
			
			View::renderTemplate('Home/index.html',[
				'quotes' => $quotes
			]);
		} else {
			View::renderTemplate('Login/new.html');
		}
    }
}
