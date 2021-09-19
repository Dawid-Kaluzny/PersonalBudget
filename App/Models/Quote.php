<?php

namespace App\Models;

use PDO;

/**
 * Quote model
 *
 * PHP version 7.0
 */
class Quote extends \Core\Model
{	
	/**
	 * Get specific number of quotes
	 *
	 * @param int $number Number of quotes
	 *
	 * @return array Quotes
	 */
	public function getQuotes($number)
	{
		$sql = 'SELECT name, author FROM quotes ORDER BY rand() LIMIT :number';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':number', $number, PDO::PARAM_INT);
		$stmt->execute();
		
		return $stmt->fetchAll();
	}
}
