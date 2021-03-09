<?php

namespace App\Models;

use PDO;

/**
 * User model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{
	/**
     * Class constructor
     *
	 * @param array $data Initial property values
	 *
     * @return void
     */
    public function __construct($data)
    {
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
    }
	
    /**
     * Save the user with the current property values
     *
     * @return void
     */
    public function save()
    {
		$password_hash = password_hash($this->password, PASSWORD_DEFAULT);
		
		$sql = 'INSERT INTO users (username, email, password) VALUES (:name, :email, :password)';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		$stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
		
		$stmt->execute();
    }
}
