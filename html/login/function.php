<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require $_SERVER['DOCUMENT_ROOT'].'/libraries/PHPMailer-master/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'].'/libraries/PHPMailer-master/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'].'/libraries/PHPMailer-master/src/SMTP.php';

function is_login($conn)
{
	if(isset($_SESSION['user_id']))
	{
		$stmt = $conn->prepare("select * from user where user_id = ? limit 1");
		$stmt -> bind_param('s', $_SESSION['user_id']) ;
		$stmt -> execute();

		$result = $stmt->get_result();
		if($result && mysqli_num_rows($result) > 0)
		{
			$user = $result->fetch_assoc();
			return $user;
		}
	}
	return null;
}

function is_username_avail($conn, $username){
	$stmt = $conn->prepare("select * from user where username = ?");
	$stmt -> bind_param('s', $username) ;
	$stmt -> execute();

	$result = $stmt->get_result();
	if($result && mysqli_num_rows($result) > 0)
	{
		return false;
	}
	return true;
}

function is_email_avail($conn, $email){
	$stmt = $conn->prepare("select * from user where email = ?");
	$stmt -> bind_param('s', $email);
	$stmt -> execute();

	$result = $stmt->get_result();
	if($result && mysqli_num_rows($result) > 0)
	{
		return false;
	}
	return true;
}

function register_user($conn, $email, $username, $password){
	$hashed_password = password_hash($password, PASSWORD_BCRYPT);
	$stmt = $conn->prepare("INSERT INTO user (email, username, password) VALUES (?,?,?)");
	$stmt -> execute(array($email, $username, $hashed_password));
}

function get_user($conn, $email){
	$stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
	$stmt -> bind_param('s', $email) ;
	$stmt -> execute();
	$result = $stmt->get_result();
	if($result && mysqli_num_rows($result) > 0)
		{
			$user = $result->fetch_assoc();
			return $user;
		}
	return null;
}
function get_username_from_id($conn, $user_id){
	$stmt = $conn->prepare("SELECT * FROM user WHERE user_id = ?");
	$stmt -> bind_param('s', $user_id) ;
	$stmt -> execute();
	$result = $stmt->get_result();
	if($result && mysqli_num_rows($result) > 0)
		{
			$user = $result->fetch_assoc();
			return $user['username'];
		}
	return null;
}

function set_OTP($conn, $email, $otp){
	$stmt = $conn->prepare("UPDATE user SET otp = ? WHERE email = ?");
	$stmt -> execute(array($otp, $email));
}


function send_OTP($conn, $email, $otp){
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Mailer = "smtp";
	$mail->SMTPAuth   = true;
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
	$mail->Port       = 465;
	$mail->Host       = "smtp.gmail.com";
	$mail->Username   = "whiteboard3335@gmail.com";
	$mail->Password   = "sbtozwxryqreuicq";
	$mail->IsHTML(true);
	$mail->AddAddress("$email");
	$mail->SetFrom("no-reply@whiteboard.com");
	$mail->Subject = "Whiteboard Forum - OTP";
	$message_body = "One Time Password for Whiteboard login:<br/><br/>" . $otp;
	$mail->MsgHTML($message_body);
	$result = $mail->Send();
	if($result){
		return true;
	}else{
		return false;
	}

}

function debug_to_console($data) {
	$output = $data;
	if (is_array($output))
		$output = implode(',', $output);

	echo "<script>console.log('Debugging: " . $output . "' );</script>";
}

function check_admin($conn, $email){
	$stmt = $conn->prepare("SELECT user_id FROM user WHERE email = ? AND is_admin = 'YES'");
	$stmt -> bind_param('s', $email) ;
	$stmt -> execute();
	$result = $stmt->get_result();
	if($result && mysqli_num_rows($result) > 0)
		{
			return "YES";
		}
	return null;
}