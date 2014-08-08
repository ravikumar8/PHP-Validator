<?php

/**
 * Validator class
 * 
 * @package 	Validator
 * @author 		Ravi Kumar
 * @version 	0.1.0    
 * @copyright 	Copyright (c) 2014, Ravi Kumar
 * @license 	https://github.com/ravikumar8/validator/blob/master/LICENSE MIT
 **/

class Validator	{

	/**
	 * Reference to ErrorHandler Class
	 *
	 * @var ErrorHandler
	 **/
	protected $errorHandler;

	/**
	 * Reference to Database Class
	 *
	 * @var Database
	 **/
	protected $db;

	/**
	 * holds $_POST data
	 *
	 * @var array
	 **/
	protected $items;

	/**
	 * Rules for the validator class
	 *
	 * @var array
	 **/
	protected $rules 	= 	[ 'required', 'minlength', 'maxlength', 'email', 'activeemail', 'url', 'activeurl', 'ip', 'alpha', 'alphaupper', 'alphalower', 'alphadash', 'alphanum', 'hexadecimal', 'numeric', 'matches', 'unique' ];

	/**
	 * messages for the rules
	 *
	 * @var array
	 **/
	public $messages	=	[
		'required'		=>	'The :field field is required',
		'minlength'		=>	'The :field field must be a minimum of :satisfied length',
		'maxlength'		=>	'The :field field must be a maximum of :satisfied length',
		'email'			=>	'That is not a valid email address',
		'activeemail'	=>	'The :field field must be active email address',
		'url'			=>	'The :field field must be url',
		'activeurl'		=>	'The :field field must be activeurl',
		'ip'			=>	'The :field field must be valid ip',
		'alpha'			=>	'The :field field must be alphabetic',
		'alphaupper'	=>	'The :field field must be upper alpha',
		'alphalower'	=>	'The :field field must be lower alpha',
		'alphadash'		=>	'The :field field must be alpha with dash',
		'alphanum'		=>	'The :field field must be alphanumeirc',
		'hexadecimal'	=>	'The :field field must be hexadecimal',
		'numeric'		=>	'The :field field must be numeric',	
		'matches'		=>	'The :field field must matches the :satisfied field',
		'unique'		=>	'That :field already taken'		
	];

	/**
	 * Constructor
	 *
	 * @param Database
	 * @param ErrorHandler
	 **/
	public function __construct( Database $db, ErrorHandler $errorHandler )	{

		$this->db 			=	$db;
		$this->errorHandler = 	$errorHandler;
	}

	/**
	 * check
	 *
	 * @param array $_POST
	 * @param array rules to check
	 * @return Validator
	 **/
	public function check($items, $rules)	{

		$this->items = $items;
		foreach ($items as $key => $value) {
			
			if( in_array( $key, array_keys($rules) ) )	{

				$this->validate([
					'field'	=>	$key,
					'value'	=>	$value,
					'rules'	=>	$rules[$key]
				]);
			}
		}

		return $this;
	}

	/**
	 * fails
	 *
	 * @return boolean true if errors else false
	 **/
	public function fails()	{

		return $this->errorHandler->hasErrors();
	}

	/**
	 * errors
	 *
	 * @return ErrorHandler
	 **/
	public function errors()	{

		return $this->errorHandler;
	}

	/**
	 * validate
	 *
	 * @param mixed 
	 **/
	protected function validate($item)	{

		$field = $item['field'];

		foreach ($item['rules'] as $rule => $satisfied) {
			
			if( in_array($rule, $this->rules)	)	{
			
				if( !call_user_func_array( [$this, $rule], [$field, $item['value'], $satisfied] ) )	{
					
					$this->errorHandler->addError(
						str_replace( [':field', ':satisfied'], [$field, $satisfied], $this->messages[$rule] ), 
						$field
					);
				}	
			}
		}
	}

	/**
	 * required
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function required($field, $value, $satisfied)	{
		return !empty(trim($value));
	}

	/**
	 * minlength
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function minlength($field, $value, $satisfied)	{
		return mb_strlen($value) >= $satisfied;
	}

	/**
	 * maxlength
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/	
	protected function maxlength($field, $value, $satisfied)	{
		return mb_strlen($value) <= $satisfied;
	}

	/**
	 * email
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function email($field, $value, $satisfied)	{
		return filter_var($value, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * active_email
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function active_email($field, $value, $satisfied)	{

		if( $this->email($field, $value, $satisfied) )	{

			if(checkdnsrr(array_pop(explode("@",$value)),"MX"))	{
				return true;
			} else {
				return false;
			}

		} else {

			return false;

		}    
	}

	/**
	 * url
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function url($field, $value, $satisfied)	{
		return filter_var($value, FILTER_VALIDATE_URL);
	}

	/**
	 * active_url
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function active_url($field, $value, $satisfied)	{

		if( $this->email($field, $value, $satisfied) )	{

			if( checkdnsrr("www.goofdfsdfsgle.com", "ANY"))	{
				return true;
			} else {
				return false;
			}

		} else {

			return false;

		}    
	}

	/**
	 * ip
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function ip($field, $value, $satisfied)	{
		return filter_var($value, FILTER_VALIDATE_IP);
	}	

	/**
	 * alpha
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function alpha($field, $value, $satisfied)	{
		return ctype_alpha($value);
	}

	/**
	 * alphaupper
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function alphaupper($field, $value, $satisfied)	{
		return ctype_upper($value);
	}

	/**
	 * alphalower
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function alphalower($field, $value, $satisfied)	{
		return ctype_lower($value);
	}

	/**
	 * alphadash
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function alphadash($field, $value, $satisfied)	{
		return preg_match('^[A-Za-z-]+$', $value);
	}

	/**
	 * alphanum
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function alphanum($field, $value, $satisfied)	{
		return ctype_alnum($value);
	}	

	/**
	 * hexadecimal
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function hexadecimal($field, $value, $satisfied)	{
		return ctype_xdigit($value);
	}

	/**
	 * numeric
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function numeric($field, $value, $satisfied)	{
		return ctype_digit($value);
	}

	/**
	 * matches
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function matches($field, $value, $satisfied)	{
		return ( strcmp( $value, $this->items[$satisfied] ) == 0 ) ? true : false;
	}

	/**
	 * unique
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 **/
	protected function unique($field, $value, $satisfied)	{
		return ! $this->db->table($satisfied)->exists([$field => $value]);
	}

}  // END class Validator