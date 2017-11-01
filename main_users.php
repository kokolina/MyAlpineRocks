<?php
	if(!isset($_SESSION)){
	    $s = session_start();	    
	}
include_once "db/db_config.php";
	if(!isset($_SESSION['username']) || !isset($_REQUEST['token']) || $_REQUEST['token'] != $_SESSION['token']){
				session_start();
				session_destroy();
				header("Location: ".$GLOBALS['indexPage']);
   				exit;
	}	
	output_add_rewrite_var("token", $_SESSION['token']);

	include_once "db/DBController.php";
	include_once "Users/UserRepository.php";
	include_once "Users/User.php";	
	require_once "Photo/Photo.php";
	include_once "Users/BackEndFormController.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Online Shop</title>
	<meta name="" content=""/>
	<link rel="icon" href="images/sheep-icon-16-23819.png" type="image/x-icon"/>
	<link rel="stylesheet" href="design/WebShopKostaDesign.css"/>
</head>
<body onload="loadUsers()">
	<?php
		include "headerPage.php";
	?>
	<div id = "userAdministration" style="padding: 10px;margin-left: 20px;">
	
		<div id="createNewUser" style="display: none;">
			<fieldset>
			<legend>CREATE USER</legend>
				<form id="createNewUserFRM" method="post" target="_self" action="main_users.php" enctype="multipart/form-data">
					<span style="display: inline; float: left; margin-left: 20px;">
						Name:<br />
						<input type="text" name="name_new" id="name_new" class="MyTextfield" maxlength="20" onchange="nameCheck(this.value, 'errName_new')" required><br />
						<p class="err" id="errName_new"></p><br />
						Lastname<br />
						<input type="text" name="lastname_new" id="lastname_new" class="MyTextfield" maxlength="30" onchange="lastnameCheck(this.value, 'errLastname_new')" required><br />
						<p class="err" id="errLastname_new"></p><br />
						Email:<br />
						<input type="email" name="email_new" class="MyTextfield" id="email_new" onchange="emailCheck(this.value, 'email_new', 'errMail_new')" maxlength="40"  required/><br />
						<p class="err" id="errMail_new"></p><br />
						User type:<br />
						<select name="access_rights_new" id="access_rights_new" style="height: 25px; border: black; border-style: solid;
								 border-width: 1px; margin: 10px; margin-left: 0px;" onchange="accessRightsCheck(this.value, 'errAccessRights_new')">
				  			<option value="default" selected disabled>Access rights...</option>
				  			<option value="Administrator">Administrator</option>
				  			<option value="Writer" >Writer</option>				  			
				  			<option value="Reader">Reader</option>				 			
				  		</select><br />
				  		<p class="err" id="errAccessRights_new"></p><br />
			 </span>
			 <span style="display: inline-block; margin-left: 20px;">	
			 	Username:<br />
				<input type="text" name="username_new" id="username_new" class="MyTextfield" maxlength="20" onchange="usernameCheck(this.value,'username_new','errUsername_new')" required><br />						
				<p class="err" id="errUsername_new"></p><br />
				Password:<br />
				<input type="password" name="password_new_1" id="password_new_1" class="MyTextfield" maxlength="20" onchange="passwordCompexityCheck(this.value,'password_new_1','errPass1_new','errPass2_new')" required/><br />
				<p class="err" id="errPass1_new"></p><br />
				Type password again:<br />
				<input type="password" name="password_new_2" id="password_new_2" class="MyTextfield" maxlength="20" onchange="passwordRetypeCheck(this.value,'password_new_2','errPass2_new')" required/><br />
				<p class="err" id="errPass2_new"></p><br/>
				
				Upload your profile photo:<br />
				<input type="file" name="profilePhoto_new" id="profilePhoto_new" style="padding: 10px;" onchange="photoCheck(this.value,'profilePhoto_new','errUserImg_new')"/><br />
				<p class="err" id="errUserImg_new"></p><br />
								
				<input type="submit" name="submit_newBtt" id="submit_new" class="MyButton" value="Create user" onclick="return submit_newUser()" />
			</span>	
			</form>
		</fieldset>
		</div>

		<div id="editUserDIV" style="display: none;">
		<fieldset>
		<legend>EDIT USER DATA</legend>
			<form id = "editUserFRM" method="post" target="_self" action="main_users.php" enctype="multipart/form-data">
				<span style="display: inline; float: left; margin-left: 20px;">
				Name:<br />
				<input type="text" name="name_edit" id="name_edit" class="MyTextfield" maxlength="20" onchange="nameCheck(this.value,'errName_edit')" required><br />
				<p class="err" id="errName_edit"></p><br />
				Lastname<br />
				<input type="text" name="lastname_edit" id="lastname_edit" class="MyTextfield" maxlength="30" onchange="lastnameCheck(this.value, 'errLastname_edit')" required><br />
				<p class="err" id="errLastname_edit"></p><br />
				Email:<br />
				<input type="email" name="email_edit" class="MyTextfield" id="email_edit" onchange="emailCheck(this.value,'email_edit','errMail_edit')" maxlength="40"  required/><br /> 
				<p class="err" id="errMail_edit"></p><br />
							
				User type:<br />
				<select name="access_rights_edit" id="access_rights_edit" style="height: 25px; border: black; border-style: solid;
						 border-width: 1px; margin: 10px; margin-left: 0px;" onchange="accessRightsCheck(this.value,'errAccessRights_edit')" required>
					<option value="default" disabled>Prava pristupa...</option>
		  			<option value="Administrator">Administrator</option>
		 			 <option value="Writer">Writer</option>
		  			<option value="Reader" selected>Reader</option>
		  		</select><br />
		  		<p class="err" id="errAccessRights_edit"></p><br />
		  		<input type="radio" name="locked" id="lockedTrue" value="locked"/>Locked
		  		<input type="radio" name="locked" id="lockedFalse" value="unlocked"/>Unlocked
			 </span>
			 <span style="display: inline-block; margin-left: 20px;">
				Username:<br />
				<input type="text" name="username_edit" id="username_edit" class="MyTextfield" maxlength="20" onchange="usernameCheck(this.value,'username_edit','errUsername_edit')" required><br />
				<p class="err" id="errUsername_edit"></p><br />
				Password:<br />
				<input type="password" name="password_edit" id="password_edit" class="MyTextfield" maxlength="20" onchange="passwordCompexityCheck(this.value,'password_edit','errPassword1_edit','errPassword2_edit')" required/><br />
				<p class="err" id="errPassword1_edit"></p><br />
				Retype password:<br />
				<input type="password" name="password_edit_1" id="password_edit_1" class="MyTextfield" maxlength="20" onchange="passwordRetypeCheck(this.value,'password_edit_1','errPassword2_edit')" required/><br />		
		  		<p class="err" id="errPassword2_edit"></p><br />
		  		Upload your profile photo:<br />
				<input type="file" name="profilePhoto_edit" id="profilePhoto_edit" style="padding: 10px;" onchange="photoCheck(this.value,'profilePhoto_edit','errUserImg_edit')"/><br />
				<p class="err" id="errUserImg_edit"></p><br />
				<input  type="text" name="UserID_edit" id="UserID_edit" style="display: none"/>
		  	 <input type="submit" name="submit_editBtt" id="submit_edit" class="MyButton" value="Save changes" onclick="return submit_editUser()"/>
				</span>	
			</form>
		</fieldset>
		</div>		
	</div>

	<div>
        <div id="usersDIV" style="margin:auto; margin-left: 20px;padding: 10px;"></div>
        <div style="margin-left: 20px; margin-bottom: 10px;">
			<input type="button" id="NewBtt" class="MyButton" value="Create user" onclick="createUser()" 
					style="display: inline;"/>
        </div>
	</div>
	<hr style="clear: both;" />
	<p id="pracenje"></p>
	 <script type="text/javascript" src="Users/BackEnd.js"></script>
</body>
</html>
