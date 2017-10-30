<?php
if(!isset($_SESSION)){
	    $s = session_start();
	    }
	
	include_once "../db/db_config.php";    
	include_once "../db/DBController.php";
	include_once "UserRepository.php";
	include_once "User.php";	
	require_once "../Photo/Photo.php";
	

class AJAXDBController 
{	

public function isEmailRegistered($email){
	$testUser = new User($email);
	echo $testUser->getUser($testUser, "Email", $email) ? "*1" : "*2";
}

public function loadUsers(){
	$testUser = new User($_SESSION['email']);
	$userArray = $testUser->getUsers();
	if(count($userArray)>0){
			$output = "<table><tr><th>ID</th><th>Name</th><th>Lastname</th><th>Username</th><th>Email</th><th>Access rights</th>
			<th>Locked</th><th>Edit</th><th>Delete</th></tr>";
			$editImg = "<a href = '#izmenaKorisnika'><img class = 'Ikonica' src = 'images/edit.png'/></a>";
			$deleteImg = "<a href = '#brisanjeKorisnika'><img class = 'Ikonica' src = 'images/delete.ico'/></a>";
			for($i = 0; $i<count($userArray); $i++){				
				$output = $output."<tr><td>".$userArray[$i]->getID()."</td><td>".$userArray[$i]->getName()."</td><td>".$userArray[$i]->getLastName()."</td><td>".$userArray[$i]->getUsername()."</td><td>".$userArray[$i]->getEmail()."</td><td>".$userArray[$i]->getAccessRights()."</td><td>".$userArray[$i]->getLocked().
				"</td><td onclick = editUserData('".$userArray[$i]->getID()."')>".$editImg."</td><td onclick = deleteUser('".$userArray[$i]->getID()."')>".$deleteImg."</td></tr>";
				
			}
			echo $output."</table>";
		}else{	
			echo "There are no active users in database.";			
		}
}

public function loadUser($userID){
	$testUser = new User($_SESSION['email']);
	if($testUser->getUser($testUser, "ID", $userID)){
		echo json_encode($testUser);	
		}else{	
			echo "*2";			//email ne postoji u bazi
		}
}

public function deleteUser($userID){
	if($userID == $_SESSION['user_ID']){
		echo "2";
	}else{
			$admin = new User($_SESSION['email']); //koristim admin-a samo da bih napravila obj. korisnik kog treba napuniti iz baze i pregaziti adminov mail
			$admin->getUser($admin, "ID", $userID);
			echo $admin->deleteUser($admin) ? "1" : "0";			
	}	
	$_REQUEST['DEL'] = NULL;
}

public function isUsernameAvailable($username){
	$testUser = new User($_SESSION['email']);
	echo $testUser->getUser($testUser, "Username", $username) ? "*1" : "*2";
}
public function test_input_KAT($data) {
 		 $data = trim($data);  
  		 $data = stripslashes($data); 
  		 $data = htmlspecialchars($data);
  		 $data = addslashes($data);
  	return $data;
}

public function askForAPI($passwordAPI) {
	$user = new User($_SESSION['email']);
	$q = $user->validatePassword($passwordAPI);
	if($q){
		if($user->generateAPIKey()) {	
				echo '{"code": "OK", "msg":"Your new API key is:", "key":"'.$user->getAPIKey().'"}';
		}else {
				echo '{"code": "err", "msg":"Error while generating API key in DB!", "key":"'.null.'"}';
		}			
	}else{
		echo '{"code": "err", "msg":"Wrong password!", "key":"'.null.'"}';
	}
	$code = $q ? "OK" : "err";
	
	
}
}


$KB = new AJAXDBController();

if(isset($_REQUEST['email'])){			//JS provera da li je mail registrovan prilikom logovanja
	$inputValue = $KB->test_input_KAT($_REQUEST['email']);
	$KB->isEmailRegistered($inputValue);
}
elseif(isset($_REQUEST['loadUsers'])){			//ucitavanje korisnika u tabelu 
	$KB->loadUsers();
}
elseif(isset($_REQUEST['ID'])){		//vraca korisnika po ID-u u obliku JSON za usecase izmena korisnika
	$inputValue = $KB->test_input_KAT($_REQUEST['ID']);
	$KB->loadUser($inputValue);
}
elseif(isset($_REQUEST['DEL'])){
	$userID = $KB->test_input_KAT($_REQUEST['DEL']);
	$KB->deleteUser($userID);	
}
elseif(isset($_REQUEST['PHOTO_DEL'])){
	$photoName = $_REQUEST['PHOTO_DEL'];
$sgn = Photo::deletePhotoP("../images/".$photoName.".jpg");
	if($sgn == TRUE){
		echo "Profile photo deleted";
	}else{
		echo "ERROR: Profile photo is NOT deleted.";
	}	
}
elseif(isset($_REQUEST['username'])){
	$username = $KB->test_input_KAT($_REQUEST['username']);
	$KB->isUsernameAvailable($username);
}
elseif(isset($_REQUEST['apigen'])){
	$KB->askForAPI($_REQUEST['apigen']);
}elseif(isset($_REQUEST['logout'])) {
	session_start();
	session_unset();
	session_destroy();
	return true;
	
}


// http://php.net/manual/en/language.oop5.php 



