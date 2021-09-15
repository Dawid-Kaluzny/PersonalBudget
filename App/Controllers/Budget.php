<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use App\Models\UsersExpense;
use App\Models\UsersIncome;

/**
 * Budget controller
 *
 * PHP version 7.0
 */
class Budget extends Authenticated
{
	/**
	 * Before filter - called before each action method
	 *
	 * @return void
	 */
	protected function before()
	{
		parent::before();
		
		$this->users_expense = new UsersExpense();
		$this->users_income = new UsersIncome();
	}
	
	/**
	 * Show the view balance
	 *
	 * @return void
	 */
	public function viewBalanceAction()
	{	
		View::renderTemplate('Budget/view_balance.html');
	}
	
	/**
	 * Show the incomes table
	 *
	 * @return void
	 */
	public function viewIncomesTableAction()
	{	
		$this->users_income->viewIncomesTable();
	}
	
	/**
	 * Show the expenses table
	 *
	 * @return void
	 */
	public function viewExpensesTableAction()
	{	
		$this->users_expense->viewExpensesTable();
	}
}
