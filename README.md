# PHP Validator #

Validator class to validate form post values in a simple way. 

### Usage ###

~~~

require_once 'classes/Database.php';
require_once 'classes/ErrorHandler.php';
require_once 'classes/Validator.php';

$db             =   new Database;
$errorHandler   =   new ErrorHandler;
$errosHtml      =   '';

if(!empty($_POST))  {

    $validator = new Validator($db, $errorHandler);

    $validation = $validator->check($_POST, [
        'username'  =>  [
            'required'  =>  true,
            'maxlength' =>  20,
            'minlength' =>  3,
            'alnum'     =>  true,
            'unique'    =>  'users'
        ],
        'email' =>  [
            'required'  =>  true,
            'maxlength' =>  255,
            'email'     =>  true,
            'unique'    =>  'users'
        ],
        'password'  =>  [
            'required'  =>  true,
            'minlength' =>  7
        ],
        'password_again'    =>  [
            'matches'   =>  'password'
        ]       
    ]);
    
    if( $validation->fails() )  {

        //echo '<pre>', print_r( $validation, 1 ), '</pre>';

        if( $validation->errors()->hasErrors('username') )  {
            $errosHtml  = '<li>' .  implode( '</li><li>' ,  $validation->errors()->all('username') ) . '</li>'; 
        }
        if( $validation->errors()->hasErrors('email') ) {
            $errosHtml  .= '<li>' .  implode( '</li><li>' ,  $validation->errors()->all('email') ) . '</li>';   
        }
        if( $validation->errors()->hasErrors('password') )  {       
            $errosHtml  .= '<li>' .  implode( '</li><li>' ,  $validation->errors()->all('password') ) . '</li>';    
        }
        if( $validation->errors()->hasErrors('password_again') )    {       
            $errosHtml  .= '<li>' .  implode( '</li><li>' ,  $validation->errors()->all('password_again') ) . '</li>';  
        }       
    }
}

<?php if( $errosHtml )  { ?>
    <ul class="alert error">
        <?php echo $errosHtml; ?>
    </ul>       
<?php } ?>
~~~

### Rules ###

 * __required__: Returns FALSE if the form element is empty. 
 * __minlength__: Returns FALSE if the form element is shorter then the parameter value. minlength=>6
 * __maxlength__: Returns FALSE if the form element is longer then the parameter value. maxlength=>10  
 * __email__: Returns FALSE if the form element does not contain a valid email address.
 * __activeemail__: Returns FALSE if the form element does not contain a valid and active email address. 
 * __url__: Returns FALSE if the form element does not contain a valid url address.
 * __activeurl__: Returns FALSE if the form element does not contain a valid and active url address.
 * __ip__: Returns FALSE if the supplied IP is not valid.
 * __alpha__: Returns FALSE if the form element contains anything other than alphabetical characters.
 * __alphaupper__: Returns FALSE if the form element contains anything other than upper alphabetical characters.
 * __alphalower__: Returns FALSE if the form element contains anything other than lower alphabetical characters.
 * __alphadash__: Returns FALSE if the form element contains anything other than alpha-numeric characters, underscores or dashes.
 * __alphanum__: Returns FALSE if the form element contains anything other than alpha-numeric characters.
 * __hexadecimal__: Returns FALSE if the form element contains anything other than hexadecimal characters.
 * __numeric__: Returns FALSE if the form element contains anything other than numeric characters.
 * __matches__: Returns FALSE if the form element does not match the one in the parameter. matches[form_item] 
 * __unique__: Returns FALSE if the form element is not unique to the table and field name in the parameter. unique[field]

Based on [Alex Garrett](https://twitter.com/alexjgarrett) work http://bit.ly/1oO8Yxn

### License ###

Released under the [MIT](http://www.opensource.org/licenses/mit-license.php) license<br>
Copyright (c) 2014 Ravi Kumar