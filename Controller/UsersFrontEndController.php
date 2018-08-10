<?php
namespace Myalpinerocks;

class UsersFrontEndController
{
	
public static function loginUser()
{
	$email = $pass = "";
	
	if(!empty($_POST['email'])){		
		if(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL)== TRUE){
			$email = UsersFrontEndController::test_input($_POST['email']);
			//echo "EMAIL: ".$email;
			//$email = $_POST["email"];
			}else{
			echo "Email nije validan<br>";
			die;			
			}
		}
		
	if(!empty($_POST['password'])){
			$pass = UsersFrontEndController::test_input($_POST['password']);
		}else{
			echo "Insert password<br>";
			return;
			}	
		
	$user = new User($email);
	$user->setPassword(hash("sha256", $pass, $raw_output = false));
	$user->logIn();
	return $user;
}
	
public static function test_input($data) 
{
 	$data = trim($data);  
  	$data = stripslashes($data); 
  	$data = htmlspecialchars($data);
  	$data = addslashes($data);
  	return $data;
}

//funkcija koja validira ulazne podatke kod unosa novog korisnika i poziva dalje klasu Korisnik da unese novog korisnika
public static function createNewUser()
{
	$name = $lastname = $email = $accessRights = $username = $password = $password_2 = "";
	//validiraj unete podatke ponovo
	//napravi korisnika
	//proveri da li postoji u bazi po mail-u i username-u 
	//insertuj u bazu
	if(!empty($_POST['name_new'])){
			$name = UsersFrontEndController::test_input($_POST['name_new']);
		}else{
			//potencijalno includujem stranicu, prikazem div za unos, popunim polja i prikazem poruku...suvise koda :(
			echo "<script>document.getElementById('errIme_new').innerHTML = 'Insert name';
			document.getElementById('createNewUser').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['lastname_new'])){
			$lastname = UsersFrontEndController::test_input($_POST['lastname_new']);
		}else{
			echo "<script>document.getElementById('errPrezime_new').innerHTML = 'Insert lastname.';
			document.getElementById('createNewUser').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['email_new'])){
		if(filter_var($_POST['email_new'], FILTER_SANITIZE_EMAIL)){
			$email = UsersFrontEndController::test_input($_POST['email_new']);
			}else{
				echo "<script>document.getElementById('errEmail_new').innerHTML = 'Email nije validan';
				document.getElementById('createNewUser').style.display = 'inline';</script>";
				return FALSE;
			}			
		}else{
			echo "<script>document.getElementById('errEmail_new').innerHTML = 'Please insert email'; 
			document.getElementById('createNewUser').style.display = 'inline';</script>";
			return FALSE;
	}	
	if(!empty($_POST['access_rights_new'])){
			$accessRights = UsersFrontEndController::test_input($_POST['access_rights_new']);
		}else{
			echo "<script>document.getElementById('errAccessRights_new').innerHTML = 'Please chose user access rights';
			document.getElementById('createNewUser').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['username_new'])){
			$username = UsersFrontEndController::test_input($_POST['username_new']);
		}else{
			echo "<script>document.getElementById('errUsername_new').innerHTML = 'Please insert username';
			document.getElementById('createNewUser').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['password_new_1'])){
			$password = UsersFrontEndController::test_input($_POST['password_new_1']);
		}else{
			echo "<script>document.getElementById('errPass1_new').innerHTML = 'Unesite lozinku';
			document.getElementById('createNewUser').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['password_new_2'])){
			$password_2 = UsersFrontEndController::test_input($_POST['password_new_2']);
			if($password_2 != $password){
				echo "<script>document.getElementById('errPass2_new').innerHTML = 'Try again! Entered passwords are not same.'</script>";
				return FALSE;
			}
		}
		else{
			echo "<script>document.getElementById('errPass2_new').innerHTML = 'Enter password'</script>";
			return FALSE;
			}
	$user = new User($email);
	$user->setName($name);
	$user->setLastName($lastname);
	$user->setAccessRights($accessRights);
	$user->setUsername($username);
	$user->setPassword($password);
		
	if($user->newUser()){
		$msg = "";
		Photo::photoUpload("profilePhoto_new", "../public/images/", $user->getId(),$msg, "single" );
		echo $msg;
	}else{
		echo "<script>alert('Greska kod unosa korisnicke slike. ".$msg."');</script>";		
	}
}


public static function deletePhoto($targetFolder, $fileName)
{
	//sve profilne slike su mi jpg format jer ih tako namestim kod uploada
	$targetFileName = $targetFolder.$fileName.".jpg";
	if(file_exists($targetFileName)){
					//OBRISI FILE
					if(unlink($targetFileName)){
						return TRUE;
					}else{
						return FALSE;
					}
				}else{
					return FALSE;
				}
}			

public static function editUserData()
{
	$ID = $name = $lastname = $email = $accessRights = $username = $password = $password_2  = $locked = "";
	//validiraj unete podatke ponovo
	//napravi korisnika
	//proveri da li postoji u bazi po mail-u i username-u 
	//insertuj u bazu
	if(!empty($_POST['name_edit'])){
			$name = UsersFrontEndController::test_input($_POST['name_edit']);
		}else{
			//potencijalno includujem stranicu, prikazem div za unos, popunim polja i prikazem poruku...suvise koda :(
			echo "<script>document.getElementById('errName_edit').innerHTML = 'Please insert name.';
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['lastname_edit'])){
			$lastname = UsersFrontEndController::test_input($_POST['lastname_edit']);
		}else{
			echo "<script>document.getElementById('errLastname_edit').innerHTML = 'Please insert lastname.';
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['email_edit'])){
		if(filter_var($_POST['email_edit'], FILTER_SANITIZE_EMAIL)){
			$email = UsersFrontEndController::test_input($_POST['email_edit']);
			}else{
				echo "<script>document.getElementById('errEmail_edit').innerHTML = 'Email format is not valid.';
				document.getElementById('editUserDIV').style.display = 'inline';</script>";
				return FALSE;
			}			
		}else{
			echo "<script>document.getElementById('errEmail_new').innerHTML = 'Insert user email'; 
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
			return FALSE;
	}	
	if(!empty($_POST['access_rights_edit'])){
			$accessRights = UsersFrontEndController::test_input($_POST['access_rights_edit']);
		}else{
			echo "<script>document.getElementById('errAccessRights_edit').innerHTML = 'Please chose user type.';
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['username_edit'])){
			$username = UsersFrontEndController::test_input($_POST['username_edit']);
		}else{
			echo "<script>document.getElementById('errUsername_edit').innerHTML = 'Please insert username';
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['password_edit'])){
			$password = UsersFrontEndController::test_input($_POST['password_edit']);
			if($password != "no change"){
				if (!preg_match('/([A-Z]|[a-z])+[0-9]+/', $password)){
    				echo 'Password is not secure enough.';
    				return FALSE;
				}
			}
		}else{
			echo "<script>document.getElementById('errPassword1_edit').innerHTML = 'Insert password';
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['password_edit_1'])){
			$password_2 = UsersFrontEndController::test_input($_POST['password_edit_1']);
			if($password_2 != $password){
				echo "<script>document.getElementById('errPassword2_edit').innerHTML = 'Entered passwords are not identical';
				document.getElementById('editUserDIV').style.display = 'inline';</script>";
				return FALSE;
			}
	}else{
			echo "<script>document.getElementById('errPassword2_edit').innerHTML = 'Insert password';
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['locked'])){
		$sgn = UsersFrontEndController::test_input($_POST['locked']);
		if($sgn == "locked"){
			$locked = 0;
		}else{
			$locked = 3;
		}
	}else{
		echo "<script>document.getElementById('errLocked').innerHTML = 'Please choose one of options bellow.';
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
			return FALSE;
	}
	if(!empty($_POST["UserID_edit"])){
		$ID = UsersFrontEndController::test_input($_POST["UserID_edit"]);
	}else{
		echo "<script>document.getElementById('errUserImg_edit').innerHTML = 'Greska sa ID korisnika.';
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
		return FALSE;
	}
	
	$user = new User($email);
	$user->setID($ID);
	$user->setName($name);
	$user->setLastName($lastname);
	$user->setAccessRights($accessRights);
	$user->setUsername($username);
	$user->setPassword($password);
	$user->setLocked($locked);
	
	echo $user->getErrMsg();
	
	if($user->editUser() || $_FILES['profilePhoto_edit']['name']){
		if($_FILES['profilePhoto_edit']['name'] != "" ){
		$msg = "";
		echo Photo::photoUpload("profilePhoto_edit","../public/images/",$ID,$msg,"single") ? "" : "ERROR MSG BEFC :: ".$msg;
		//die("123");
		}
	}else{
		echo "ERROR MSG BEFC::40 ".$user->getErrMsg();
	}
}

public static function isEmailRegistered($email)
{
	$testUser = new User($email);
	echo $testUser->getUser($testUser, "Email", $email) ? "*1" : "*2";
}

public static function loadUsers()
{
	$testUser = new User($_SESSION['email']);
	$userArray = $testUser->getUsers();
	if(count($userArray)>0){
			$output = "<table><tr><th>ID</th><th>Name</th><th>Lastname</th><th>Username</th><th>Email</th><th>Access rights</th>
			<th>Locked</th><th>Edit</th><th>Delete</th></tr>";
			$editImg = "<a href = '#izmenaKorisnika'><img class = 'Ikonica' src = '../public/images/edit.png'/></a>";
			$deleteImg = "<a href = '#brisanjeKorisnika'><img class = 'Ikonica' src = '../public/images/delete.ico'/></a>";
			for($i = 0; $i<count($userArray); $i++){				
				$output = $output."<tr><td>".$userArray[$i]->getID()."</td><td>".$userArray[$i]->getName()."</td><td>".$userArray[$i]->getLastName()."</td><td>".$userArray[$i]->getUsername()."</td><td>".$userArray[$i]->getEmail()."</td><td>".$userArray[$i]->getAccessRights()."</td><td>".$userArray[$i]->getLocked().
				"</td><td onclick = editUserData('".$userArray[$i]->getID()."')>".$editImg."</td><td onclick = deleteUser('".$userArray[$i]->getID()."')>".$deleteImg."</td></tr>";
				
			}
			echo $output."</table>";
		}else{	
			echo "There are no active users in database.";			
		}
}

public static function loadUser($userID)
{
	$testUser = new User($_SESSION['email']);
	if($testUser->getUser($testUser, "ID", $userID)){
		echo json_encode($testUser);	
		}else{	
			echo "*2";			//email ne postoji u bazi
		}
}

public static function deleteUser($userID)
{
	if($userID == $_SESSION['user_ID']){
		echo "2";
	}else{
			$admin = new User($_SESSION['email']); //koristim admin-a samo da bih napravila obj. korisnik kog treba napuniti iz baze i pregaziti adminov mail
			$admin->getUser($admin, "ID", $userID);
			echo $admin->deleteUser($admin) ? "1" : "0";			
	}	
	$_REQUEST['DEL'] = NULL;
}

public static function isUsernameAvailable($username)
{
	$testUser = new User($_SESSION['email']);
	echo $testUser->getUser($testUser, "Username", $username) ? "*1" : "*2";
}

public static function askForAPI($passwordAPI) 
{
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

// http://php.net/manual/en/language.oop5.php 


class MyMSG{
	private $string = "";
	
	function __construct($str){
		$this->string = $str;
	}	
	function dodaj($str){
		$this->string = $this->string."\n".$str;
	}
	function printOut(){
		return $this->string;		
	}
}

?>
