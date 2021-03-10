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
	 * Error messages
	 *
	 * @var array
	 */
	public $errors = [];
	
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
     * Save the user model with the current property values
     *
     * @return boolean True if the user was saved, false otherwise
     */
    public function save()
    {
		$this->validate();
		
		if (empty($this->errors)) {
			
			$password_hash = password_hash($this->password, PASSWORD_DEFAULT);
			
			$sql = 'INSERT INTO users (username, email, password) VALUES (:name, :email, :password)';
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
			$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
			$stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
			
			return $stmt->execute();
		}
		
		return false;
    }
	
	/**
	 * Validate current property values, adding validation error messages to the errors
	 *
	 * @return void
	 */
	public function validate()
	{
		// Name
		if ($this->name == '') {
			$this->errors[] = 'Imię jest wymagane';
		}
		
		// Email address
		if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
			$this->errors[] = 'Niepoprawny e-mail';
		}
		
		if ($this->emailExists($this->email)) {
			$this->errors[] = 'E-mail jest już zajęty';
		}
		
		// Paassword
		if ($this->password != $this->password_confirmation) {
			$this->errors[] = 'Hasło musi być zgodne z potwierdzeniem hasła';
		}
		
		if (strlen($this->password) < 8) {
			$this->errors[] = 'Wprowadź co najmniej 8 znaków jako hasło';
		}
		
		if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
			$this->errors[] = 'Hasło musi mieć co najmniej jedną literę';
		}
		
		if (preg_match('/.*\d+.*/i', $this->password) == 0) {
			$this->errors[] = 'Hasło musi mieć co najmniej jedną cyfrę';
		}
	}
	
	/**
	 * See if user record already exists with the specified email
	 *
	 * @param string $email email address to search for
	 *
	 * @return boolean True if a record already exists with the specified email, false otherwise
	 */
	protected function emailExists($email)
	{
		$sql = 'SELECT * FROM users WHERE email = :email';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':email', $email, PDO::PARAM_STR);
		
		$stmt->execute();
		
		return $stmt->fetch() !== false;
	}
}
