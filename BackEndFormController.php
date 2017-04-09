
<?php

if(!isset($_SESSION)){
	    $s = session_start();
	    }


require_once "User.php";

if(isset($_POST['LoginFormBtt'])){
	$user = BackEndFormController::loginUser();	
	
	if($user->getLocked()==0 || $user->getLocked() === 0){
		include "BackEnd.html";
		echo "<script>document.getElementById('errMail').innerHTML = 'Korisnicki nalog je zakljucan'</script>";
	}elseif($user->getErrKod()=="n"){
		include "BackEnd.html";
		echo "<script>document.getElementById('errMail').innerHTML = 'Korisnicki nalog ne postoji u bazi'</script>";
	}elseif($user->getErrKod()=="pass"){
		include "BackEnd.html";
		$brPokusaja = $user->getLocked();
		echo "<script>document.getElementById('errMail').innerHTML = 'Pogresna lozinka. Imate jos $brPokusaja pokusaj/a.'</script>";
	}else{	
		
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['user_rights'] = $user->getAccessRights();
        $_SESSION['user_ID'] = $user->getID();
		if(file_exists("images/".$user->getID().".jpg")){
			$_SESSION['imgPath'] = "images/".$user->getID().".jpg";	
		}else{
			$_SESSION['imgPath'] = "images/noPhoto.jpg";
		}
		include "BackEndMenu.html";
		if($_SESSION['user_rights'] == 'A'){
			echo "<script>
			var users = document.getElementById('BackEnd_Users');
			var link = document.createElement('a');
			link.innerHTML = 'Users';
			var att = document.createAttribute('href');
			att.value = 'BackEnd_Users.php';
			link.setAttributeNode(att);
			var attCls = document.createAttribute('class');
			attCls.value = 'Menu';
			link.setAttributeNode(attCls);
			users.appendChild(link);
			</script>";
			//   <a href='BackEnd_Users.php' class='Menu'>Users</a>
		}
	}
}elseif(isset($_POST['submit_newBtt'])){
	include "BackEnd_Users.php";
	BackEndFormController::createNewUser();
}elseif(isset($_POST['submit_editBtt'])){
	include "BackEnd_Users.php";
	BackEndFormController::editUserData();
}

//echo "<script>window.close();</script>";

class BackEndFormController{
	
public static function loginUser(){
	$email = $pass = "";
	
	if(!empty($_POST['email'])){		
		if(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL)== TRUE){
			$email = BackEndFormController::test_input($_POST['email']);
			//echo "EMAIL: ".$email;
			//$email = $_POST["email"];
			}else{
			echo "Email nije validan<br>";
			die;			
			}
		}
		
	if(!empty($_POST['password'])){
			$pass = BackEndFormController::test_input($_POST['password']);
			//$pass = $_POST['password'];
		}else{
			echo "Unesite password<br>";
			return;
			}	
		
	$user = new User($email);
	$user->setPassword($pass);
	$user->logIn();	
	return $user;
	}
	
public static function test_input($data) {
 		 $data = trim($data);  
  		 $data = stripslashes($data); 
  		 $data = htmlspecialchars($data);
  	return $data;
}

//funkcija koja validira ulazne podatke kod unosa novog korisnika i poziva dalje klasu Korisnik da unese novog korisnika
public static function createNewUser(){
	$name = $lastname = $email = $accessRights = $username = $password = $password_2 = "";
	//validiraj unete podatke ponovo
	//napravi korisnika
	//proveri da li postoji u bazi po mail-u i username-u 
	//insertuj u bazu
	if(!empty($_POST['name_new'])){
			$name = BackEndFormController::test_input($_POST['name_new']);
		}else{
			//potencijalno includujem stranicu, prikazem div za unos, popunim polja i prikazem poruku...suvise koda :(
			echo "<script>document.getElementById('errIme_new').innerHTML = 'Insert name';
			document.getElementById('createNewUser').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['lastname_new'])){
			$lastname = BackEndFormController::test_input($_POST['lastname_new']);
		}else{
			echo "<script>document.getElementById('errPrezime_new').innerHTML = 'Insert lastname.';
			document.getElementById('createNewUser').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['email_new'])){
		if(filter_var($_POST['email_new'], FILTER_SANITIZE_EMAIL)){
			$email = BackEndFormController::test_input($_POST['email_new']);
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
			$accessRights = BackEndFormController::test_input($_POST['access_rights_new']);
		}else{
			echo "<script>document.getElementById('errAccessRights_new').innerHTML = 'Please chose user access rights';
			document.getElementById('createNewUser').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['username_new'])){
			$username = BackEndFormController::test_input($_POST['username_new']);
		}else{
			echo "<script>document.getElementById('errUsername_new').innerHTML = 'Please insert username';
			document.getElementById('createNewUser').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['password_new_1'])){
			$password = BackEndFormController::test_input($_POST['password_new_1']);
		}else{
			echo "<script>document.getElementById('errPass1_new').innerHTML = 'Unesite lozinku';
			document.getElementById('createNewUser').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['password_new_2'])){
			$password_2 = BackEndFormController::test_input($_POST['password_new_2']);
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
		$msg = new MyMSG('');
		BackEndFormController::photoUpload("images/","submit_newBtt", $user->getID(), "profilePhoto_new", $msg);
		echo '<script>alert("'.$msg->printOut().'");</script>';			
	}else{
		echo "<script>alert('Greska kod unosa korisnicke slike. ".$user->getErrPoruka()."');</script>";		
	}
}

//funkcija koja vrsi upload fajlova, ulaz joj je folder u koji se smesta fajl
//submitBtt, photoName, fileInputName,
//"submit_new", 'username_new', 'profilePhoto_new'
public static function photoUpload($targetFolder, $submitBtt, $photoName, $fileInputName, $msgOut){
		if(isset($_POST[$submitBtt])){
			$sgn = TRUE;
			$targetFileName = $targetFolder.$photoName.".jpg";
			$tmpFilePath = $_FILES[$fileInputName]['tmp_name'];
			
			//proveri velicinu fajla
			if($_FILES[$fileInputName]['size'] > 5000000){
				$msgOut->dodaj("Velicina slike premasuje dozvoljenih 5MB.");
				$sgn = FALSE;
				return FALSE;
			}
			
			//provera formata
			if(isset($_FILES)){
				try{
					$formatCheck = getimagesize($tmpFilePath);
					if($formatCheck !== false){
						$msgOut->dodaj("Fajl je odgovarajuceg formata.<br>");
					}else{
						$msgOut->dodaj("Fajl nije slika. Pokusajte upload drugog fajla.");
						$sgn = FALSE;
						return FALSE;
					}
				}catch(Exception $e){
					echo "Greska: ".$e->getMessage();
					$sgn = FALSE;
					return FALSE;
				}					
			}else{
				$msgOut->dodaj("Fajl nije uploadovan iz nekog razloga. Verovatno velicina. ");
				$sgn = FALSE;
				return FALSE;
			}
						
			//provera da li fajl vec postoji kod unosa novog; brisanje prethodne profilne slike kod izmene
			if($submitBtt == "submit_new"){
				if(file_exists($targetFileName)){
					$msgOut->dodaj("Fajl je vec uploadovan.<br>");
					$sgn = false;
					return FALSE;
				}	
			}elseif($submitBtt == "submit_editBtt"){
				if(file_exists($targetFileName)){
					//OBRISI FILE
					if(!unlink($targetFileName)){
						$msgOut->dodaj("Prethodna profilna slika nije izbrisana.");
					}
				}
			}
				
			
			//provera da li je slika odgovarajuceg formata: JPG, JPEG, PNG, GIF
			$fileType = pathinfo($targetFileName, PATHINFO_EXTENSION);
			if($fileType != "jpg" && $fileType != "jpeg" && $fileType != "png" && $fileType != "gif"){
				$msgOut->dodaj("Slika nije odgovarajuceg formata: JPG, JPEG, PNG, GIF.");
				$sgn = FALSE;
				return FALSE;
			}
			
			//			UPLOADUJ ...
			if($sgn){
				if(move_uploaded_file($tmpFilePath, $targetFileName)){
$sgn = chmod($targetFileName, 0766);
					$msgOut->dodaj("Fajl je uspesno uploadovan. ".$sgn);
					return TRUE;
				}else{
					$msgOut->dodaj("Greska 1: File nije uploadovan.");
					return FALSE;
				}
			}else{
				$msgOut->dodaj("Greska 2: Fajl nije upload-ovan.");
				return FALSE;
			}
		}
}

public static function deletePhoto($targetFolder, $fileName){
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

public static function editUserData(){
	$ID = $name = $lastname = $email = $accessRights = $username = $password = $password_2  = $locked = "";
	//validiraj unete podatke ponovo
	//napravi korisnika
	//proveri da li postoji u bazi po mail-u i username-u 
	//insertuj u bazu
	if(!empty($_POST['name_edit'])){
			$name = BackEndFormController::test_input($_POST['name_edit']);
		}else{
			//potencijalno includujem stranicu, prikazem div za unos, popunim polja i prikazem poruku...suvise koda :(
			echo "<script>document.getElementById('errName_edit').innerHTML = 'Please insert name.';
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['lastname_edit'])){
			$lastname = BackEndFormController::test_input($_POST['lastname_edit']);
		}else{
			echo "<script>document.getElementById('errLastname_edit').innerHTML = 'Please insert lastname.';
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['email_edit'])){
		if(filter_var($_POST['email_edit'], FILTER_SANITIZE_EMAIL)){
			$email = BackEndFormController::test_input($_POST['email_edit']);
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
			$accessRights = BackEndFormController::test_input($_POST['access_rights_edit']);
		}else{
			echo "<script>document.getElementById('errAccessRights_edit').innerHTML = 'Please chose user type.';
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['username_edit'])){
			$username = BackEndFormController::test_input($_POST['username_edit']);
		}else{
			echo "<script>document.getElementById('errUsername_edit').innerHTML = 'Please insert username';
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['password_edit'])){
			$password = BackEndFormController::test_input($_POST['password_edit']);
		}else{
			echo "<script>document.getElementById('errPassword1_edit').innerHTML = 'Insert password';
			document.getElementById('editUserDIV').style.display = 'inline';</script>";
			return FALSE;
			}
	if(!empty($_POST['password_edit_1'])){
			$password_2 = BackEndFormController::test_input($_POST['password_edit_1']);
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
		$sgn = BackEndFormController::test_input($_POST['locked']);
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
		$ID = BackEndFormController::test_input($_POST["UserID_edit"]);
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
		$msg = new MyMSG("");
		echo BackEndFormController::photoUpload("images/","submit_editBtt", $ID, "profilePhoto_edit", $msg) ? "" : "PORUKA BEFC :: ".$msg->printOut();
		}
	}else{
		echo "PORUKA BEFC::40 ".$user->getErrMsg();
	}
}

/**
public static function loadUsers(){
    $resultOut = "<table><tr><th>ID</th><th>Ime</th><th>Prezime</th><th>Username</th><th>E-mail</th><th>Prava_pristupa</th><th>Locked</th><th>Izmeni</th><th>Obrisi</th></tr>";
    $editImg = "<a href = '#izmenaKorisnika'><img class = 'Ikonica' src = 'images/edit.png'/></a>";
    $deleteImg = "<a href = '#brisanjeKorisnika'><img class = 'Ikonica' src = 'images/delete.ico'/></a>";
    
    $user = new User($_SESSION['email']);
    $userArray = $user->getUsers();
    for($i = 0;$i<count($userArray);$i++){
        $row = $userArray[$i];	
        echo $row->getID()."<br>";			
	$resultOut = $resultOut."<tr><td>".$row->getID()."</td><td>".$row->getName()."</td><td>".$row->getLastname()."</td><td>".$row->getUsername()."</td><td>".$row->getEmail()."</td><td>".$row->getAccessRights()."</td><td>".$row->getLocked().
				"</td><td onclick = editUserData('".$row->getID()."')>".$editImg."</td><td onclick = deleteUser('".$row->getID()."')>".$deleteImg."</td></tr>";
				
    }    
    return $resultOut;
}
*/
}

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
