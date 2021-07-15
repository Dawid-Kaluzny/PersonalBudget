<?php

namespace App\Models;

use \App\Auth;
use PDO;

/**
 * User's income model
 *
 * PHP version 7.0
 */
class UsersIncome extends \Core\Model
{
	/**
     * Class constructor
	 *
     * @return void
     */
    public function __construct()
    {
		$user = Auth::getUser();
		$this->user_id = $user->id;
    }
	
	/**
	 * Get incomes categories assigned to user
	 *
	 * @return array Users incomes categories
	 */
	public function getIncomesCategories()
	{
		$sql = 'SELECT name FROM incomes_category_assigned_to_users WHERE user_id = :user_id';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
		$stmt->execute();
		
		return $stmt->fetchAll();
	}
	
	/**
     * add income to specified user
     *
     * @param array $data Data from the add income form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function addIncome($data)
    {
		if (isset($data['amount'])) {
			$this->amount = $data['amount'];
			$this->date_of_income = $data['date_of_income'];
			$this->income_category = $data['income_category'];
			$this->comment = $data['comment'];
			
			$this->category_id = $this->findIdByCategoryName();
			
			$sql = 'INSERT INTO incomes VALUES (NULL, :user_id, :income_category_id, :amount, :date_of_income, :comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

			$stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
			$stmt->bindValue(':income_category_id', $this->category_id, PDO::PARAM_INT);
			$stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
			$stmt->bindValue(':date_of_income', $this->date_of_income, PDO::PARAM_STR);
			$stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }
	
	/**
     * Find id category by its name
     *
     * @return integer Id income category assigned to user
     */
    protected function findIdByCategoryName()
    {
		$sql = 'SELECT id FROM incomes_category_assigned_to_users WHERE user_id = :user_id AND name = :income_category';
		
		$db = static::getDB();
        $stmt = $db->prepare($sql);
		
		$stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
		$stmt->bindValue(':income_category', $this->income_category, PDO::PARAM_STR);
		$stmt->execute();
		
		$categoryId = $stmt->fetch();
		
		return $categoryId['id'];
	}
	
	/**
	 * View incomes table assigned to user
	 *
	 * @return void
	 */
	public function viewIncomesTable()
	{
		$earliestDate = $_POST['earliestDate'];
		$lastestDate = $_POST['lastestDate'];
			
		$sql = 'SELECT ic.name, SUM(i.amount) AS ia FROM users AS u, incomes AS i, incomes_category_assigned_to_users AS ic
				WHERE i.user_id = :user_id AND i.date_of_income >= :earliestDate AND i.date_of_income <= :lastestDate AND u.id = ic.user_id AND u.id = i.user_id AND ic.id = i.income_category_assigned_to_user_id GROUP BY ic.name ORDER BY ia DESC';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
		$stmt->bindValue(':earliestDate', $earliestDate, PDO::PARAM_STR);
		$stmt->bindValue(':lastestDate', $lastestDate, PDO::PARAM_STR);
		$stmt->execute();
		
		$incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$data = Array();
		
		foreach($incomes as $row) {
			$data[] = Array(
				'name'   => $row["name"],
				'ia'  => floatval($row["ia"])
			);
		}
		
		echo json_encode($data);
	}
}
