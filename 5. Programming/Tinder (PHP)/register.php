<?php

session_start();

if (isset($_GET['logout'])) {
	$_SESSION = array();
}

function def($connect, $text) {
	return mysqli_real_escape_string($connect, htmlentities($text));
}

$connect = mysqli_connect('localhost','st2014','progress','st2014')or die(mysqli_error());  /*localhost pass */

if(isset($_POST["submit"])){
	$login =  def($connect, $_POST["login"]);
	$name = def($connect, $_POST["name"]);
	$password = def($connect, $_POST["password"]);
	$mail = def($connect, $_POST["mail"]);  
	$sex = def($connect, $_POST["sex"]);
	$result = mysqli_query($connect,"select ifnull(max(id) + 1, 1) from myvadimregister") or die(mysql_error());
	$row = mysqli_fetch_row($result);
	$next_id = $row[0];
	$query = mysqli_query($connect,"INSERT INTO myvadimregister (id, login, name, password, email, sex) VALUES ($next_id,'$login','$name','$password','$mail','$sex')") or die(mysql_error());
	
}

if (isset($_POST["enter"])){
	$loginn = $_POST['loginn'];
	$passwordd = $_POST['passwordd'];
	
	$query = mysqli_query($connect,"SELECT * FROM myvadimregister WHERE login='$loginn'");
	$user_data = mysqli_fetch_array($query);
	
	if ($user_data['password'] == $passwordd){
		$_SESSION['user'] = $user_data;
		header('Location: profile.php');
		exit;
	}
	else{
		echo "Wrong password or login!";
	}
	
}
?>

<form method="post" action="register.php">
   <input type="text" name="login" placeholder="Login" required/><br>
   <input type="text" name="name" placeholder="Name" required/><br>
   <input type="password" name="password" placeholder="Password" required/><br>
   <input type="text" name="mail" placeholder="Mail" required/><br>
   <select name="sex">
   <option value="man">Man</option> 
   <option value="woman" selected>Woman</option>
</select>
   <input type="submit" name="submit" value="Register Your Account"/><br>
</form>


<form method="post" action="register.php">
   <input type="text" name="loginn" placeholder="Login" required/><br>
   <input type="password" name="passwordd" placeholder="Password" required/><br>
   <input type="submit" name="enter" value="Login"/><br>
</form>

