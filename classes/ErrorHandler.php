<?php

/**
 * ErrorHandler class
 * 
 * @package 	Validator
 * @author 		Ravi Kumar
 * @version 	0.1.0    
 * @copyright 	Copyright (c) 2014, Ravi Kumar
 * @license 	https://github.com/ravikumar8/PHP-Validator/blob/master/LICENSE MIT
 **/

class ErrorHandler	{
	
	/**
	 * $errors
	 *
	 * @var array holds all the errors
	 **/
	protected $errors = [];

	/**
	 * addError
	 *
	 * @param string error message
	 * @param string key to hold the error message
	 * @return void
	 **/
	public function addError( $error, $key = null )	{

		if( ! is_null( $key ) )	{
			$this->errors[$key][]	=	$error;
		}	else {
			$this->errors[]	=	$error;
		}
	}

	/**
	 * first
	 * 
	 * @param string key for the error
	 * @return string error message
	 **/
	public function first( $key )	{

		return isset( $this->all()[$key][0] ) ? $this->all()[$key][0] : '';
	}

	/**
	 * all
	 *
	 * @param string $key for the error
	 * @return mixed all error messga in array or single error message if $key is given
	 **/
	public function all( $key = null )	{

		return isset( $this->errors[$key] ) ? $this->errors[$key] : $this->errors;
	}

	/**
	 * hasErrors
	 *
	 * @return boolean true if error else false
	 **/
	public function hasErrors( $key = null )	{
		return is_null( $key ) ? count( $this->all( $key ) ) ? true : false : isset( $this->errors[$key] ) ? true : false;
	}
}  // END class ErrorHandler