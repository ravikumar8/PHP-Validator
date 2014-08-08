<?php

/**
 * Database Class
 * This file is only used for demo purpose.
 * 
 * @package 	Validator
 * @author 		Ravi Kumar
 * @version 	0.1.0    
 * @copyright 	Copyright (c) 2014, Ravi Kumar
 * @license 	https://github.com/ravikumar8/validator/blob/master/LICENSE MIT
 **/

class Database	{

	protected $host 	= 	'localhost';

	protected $db 		=	'test';

	protected $username	=	'root';

	protected $password	=	'';

	protected $stmt;

	protected $table;

	public $pdo;

	/**
	 * Constructor
	 *	 
	 **/
	public function __construct()	{
		$this->pdo = new PDO("mysql:host={$this->host};dbname={$this->db}", $this->username, $this->password);	
	}

	/**
	 * change table in the query
	 *
	 * @return classObject
	 **/
	public function table($table)	{
		$this->table = $table;
		return $this;
	}

	/**
	 * this is used to check data exists or not in database
	 *
	 * @param mixed array which contains field and value
	 * @return boolean true if data found, else false
	 **/
	public function exists($data)	{

		$field = array_keys($data)[0];

		return $this->where( $field, '=', $data[$field])->count() ? true : false;
	}

	/**
	 * executes where query 
	 *
	 * @param string column of the database
	 * @param string operators for ex. > < >= etc
	 * @param mixed value to check in given column
	 * @return classObject
	 **/
	public function where($field, $operator, $value)	{

		$sql = "SELECT * FROM {$this->table} WHERE {$field} {$operator} ?";

		$this->stmt = $this->pdo->prepare($sql);

		$this->stmt->execute([$value]);

		return $this;
	}	

	/**
	 * gives total number of rows
	 *
	 * @return integer rowcount found from last query
	 **/
	public function count()	{

		return $this->stmt->rowCount();
	}
}  // END class Database