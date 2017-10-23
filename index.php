<?php
	if(!isset($_SESSION)){
	    $s = session_start();
	    $_SESSION['username'] = null;
	    }
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>My Alpine Rocks</title>
<meta name="" content="">
<link rel="icon" href= "images/sheep-icon-16-23819.png" type="image/x-icon"/>
<link rel="stylesheet" href= "design/WebShopKostaDesign.css"/>
<script src="Users/BackEnd.js"></script>
</head>
<body>
<h1><img id="logo" src="images/mountain-line-2.bmp"/></h1>
<hr />
<div class="Menu">
<form method="post" target="_self" id="logInForm"  action='main.php'>
	Email address:<br />
	<input type="text" name="email" class="MyTextfield" id="email" value="" maxlength="40" onchange="evaluateEmail(this.value)" required/><br />
	<p class="err" id="errMail"></p>
	<br />
	Password:<br />
	<input type="password" name="password" class="MyTextfield" id="password" maxlength="10" required/><br />
	<p class="err" id="passErr"></p>
	<br />
	<input type="submit" id="submitBtt" name="LoginFormBtt" onclick="return logIn()" class="MyButton" value="Login" /> 
</form>

</div>
</body>
</html>