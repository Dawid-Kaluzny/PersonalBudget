<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use App\Models\UsersIncome;

/**
 * Income controller
 *
 * PHP version 7.0
 */
class Income extends Authenticated
{
	/**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before()
    {
        parent::before();
		
		$this->users_income = new UsersIncome();
    }
	
    /**
     * Show the add income
     *
     * @return void
     */
    public function addAction()
    {		
		$income_categories = $this->users_income->getIncomesCategories();
		
        View::renderTemplate('Income/add.html', [
			'income_categories' => $income_categories
		]);
    }
	
	/**
     * Add income to user
     *
     * @return void
     */
    public function addIncomeAction()
    {
        if ($this->users_income->addIncome($_POST)) {

            Flash::addMessage('Przychód został dodany!');

        } 
		
		$this->redirect('/income/add');
    }
}
