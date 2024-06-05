<?php 

	/* ==========================  Define variables ========================== */

	#Your e-mail address
	define('__TO__', "a.bublaku@gmail.com");

	#Message subject
	define('__SUBJECT__', "");

	#Success message
	define('__SUCCESS_MESSAGE__', "Thanks for your message, we will answer as fast as possible!");

	#Error message 
	define('__ERROR_MESSAGE__', "An error has occurred, your message was not sent");

	#Messege when one or more fields are empty
	define('__MESSAGE_EMPTY_FILDS__', "Please fill in all fields");

	/* ========================  End Define variables ======================== */

	//Send mail function
	function send_mail($to,$subject,$message,$headers){
		if(@mail($to,$subject,$message,$headers)){
			echo json_encode(array('info' => 'success', 'msg' => __SUCCESS_MESSAGE__));
		} else {
			echo json_encode(array('info' => 'error', 'msg' => __ERROR_MESSAGE__));
		}
	}

	// Check if email is true, this one checks for @, . , and domain (dk, com, de and so on)
        function check_email($mail){ 
            if(is_array($mail) || is_numeric($mail) || is_bool($mail) || is_float($mail) || is_file($mail) || is_dir($mail) || is_int($mail))
                return false;
            else{ $mail=trim(strtolower($mail)); 
                if(filter_var($mail, FILTER_VALIDATE_EMAIL)===true) return $mail;
            else {
                $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
                return (preg_match($pattern, $mail) === 1) ? $mail : false;
                }
            }
        }     

	//Get post data
	if(isset($_POST['name']) and isset($_POST['mail']) and isset($_POST['subject']) and isset($_POST['comment'])){
		$name 	 = $_POST['name'];
		$mail 	 = $_POST['mail'];
		$sub  = $_POST['subject'];
		$comment = $_POST['comment'];

		if($name == '') {
			echo json_encode(array('info' => 'error', 'msg' => "Please inter your name."));
			exit();
		} else if($mail == '' or check_email($mail) == false){
			echo json_encode(array('info' => 'error', 'msg' => "Please inter your e-mail."));
			exit();
		} else if($sub == ''){
			echo json_encode(array('info' => 'error', 'msg' => "Please inter a subject."));
			exit();
		} else if($comment == ''){
			echo json_encode(array('info' => 'error', 'msg' => "Please inter a message."));
			exit();
		} else {
			//Send Mail
			$to = __TO__;
			$subject = __SUBJECT__ . ' ' . $name;
			$message = '
			<html>
			<head>
			  <title>Mail from '. $name .'</title>
			</head>
			<body>
			  <table class="table">
				<tr>
				  <th align="right">Name:</th>
				  <td align="left">'. $name .'</td>
				</tr>
				<tr>
				  <th align="right">Email:</th>
				  <td align="left">'. $mail .'</td>
				</tr>
				<tr>
				  <th align="right">Subject:</th>
				  <td align="left">'. $sub .'</td>
				</tr>
				<tr>
				  <th align="right">Comment:</th>
				  <td align="left">'. $comment .'</td>
				</tr>
			  </table>
			</body>
			</html>
			';

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: ' . $mail . "\r\n";

			send_mail($to,$subject,$message,$headers);
		}
	} else {
		echo json_encode(array('info' => 'error', 'msg' => __MESSAGE_EMPTY_FILDS__));
	}
 ?>