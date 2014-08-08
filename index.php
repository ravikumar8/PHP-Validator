<?php

/**
 * This file is only used for demo purpose.
 * 
 * @package 	Validator
 * @author 		Ravi Kumar
 * @version 	0.1.0    
 * @copyright 	Copyright (c) 2014, Ravi Kumar
 * @license 	https://github.com/ravikumar8/validator/blob/master/LICENSE MIT
 **/

require_once 'classes/Database.php';
require_once 'classes/ErrorHandler.php';
require_once 'classes/Validator.php';

$db 			=	new Database;
$errorHandler 	=	new ErrorHandler;
$errosHtml		=	'';

if(!empty($_POST))	{

	$validator = new Validator($db, $errorHandler);

	$validation = $validator->check($_POST, [
		'username'	=>	[
			'required'	=>	true,
			'maxlength'	=>	20,
			'minlength'	=>	3,
			'alnum'		=>	true,
			'unique'	=>	'users'
		],
		'email'	=>	[
			'required'	=>	true,
			'maxlength'	=>	255,
			'email'		=>	true,
			'unique'	=>	'users'
		],
		'password'	=>	[
			'required'	=>	true,
			'minlength'	=>	7
		],
		'password_again'	=>	[
			'matches'	=>	'password'
		]		
	]);
	
	if( $validation->fails() )	{

		//echo '<pre>', print_r( $validation, 1 ), '</pre>';

		if( $validation->errors()->hasErrors('username') )	{
			$errosHtml	= '<li>' .  implode( '</li><li>' ,  $validation->errors()->all('username') ) . '</li>';	
		}
		if( $validation->errors()->hasErrors('email') )	{
			$errosHtml	.= '<li>' .  implode( '</li><li>' ,  $validation->errors()->all('email') ) . '</li>';	
		}
		if( $validation->errors()->hasErrors('password') )	{		
			$errosHtml	.= '<li>' .  implode( '</li><li>' ,  $validation->errors()->all('password') ) . '</li>';	
		}
		if( $validation->errors()->hasErrors('password_again') )	{		
			$errosHtml	.= '<li>' .  implode( '</li><li>' ,  $validation->errors()->all('password_again') ) . '</li>';	
		}		
	}
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Validation</title>
	<link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

	<div class="container">
		<?php if( $errosHtml )	{ ?>
			<ul class="alert error">
				<?php echo $errosHtml; ?>
			</ul>		
		<?php } ?>
		<form action="index.php" method="post" auto-fill="off">
			<ul>
			<li>Username:<input type="text" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>"></li>
			<li>Email:<input type="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"></li>
			<li>Password:<input type="password" name="password"></li>
			<li>Password again:<input type="password" name="password_again"></li>
			<li><input type="submit" class="btn"></li>
			</ul>
		</form>
	</div>
</body>
</html>