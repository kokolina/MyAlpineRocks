<?php
namespace Myalpinerocks;

use \ArrayObject;

class UsersFrontEndController extends BaseController
{
    public static function loginUser() : User
    {
        if (!empty($_POST['email'])) {
            if (filter_var($_POST["email"], FILTER_SANITIZE_EMAIL)== true) {
                $email = UsersFrontEndController::test_input($_POST['email']);
            } else {
                $_SESSION['msg'] = "Email is not valid.";
                header("Location: ".$GLOBALS['indexPage']);
                exit;
            }
        }        
        if (!empty($_POST['password'])) {
            $pass = UsersFrontEndController::test_input($_POST['password']);
        } else {
            $_SESSION['msg'] = "Login credentials required.";
            header("Location: ".$GLOBALS['indexPage']);
            exit;
        }
        $user = new User($email);
        $user->setPassword(hash("sha256", $pass, $raw_output = false));
        $user->logIn();
        return $user;
    }

    public static function createNewUser()
    {
        $user = new User(); 
        $user = self::parseUserForm('name_new', 'lastname_new', 'email_new', 'access_rights_new', 'username_new', 'password_new_1', 'password_new_2', $user);
                
        if ($user->newUser()) {
        	   $msg = "";
            $msg = Photo::photoUpload("profilePhoto_new", "../public/images/", $user->getId(), $msg, "single") ? "" : $msg;
            echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => $msg]);	
            return;
        } else {
            echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Error. User not saved.".$user->getErrMsg()]);	
            return;
        }
    }
    
    public static function editUserData()
    {
        $user = new User();
        
        $user = self::parseUserForm('name_edit', 'lastname_edit', 'email_edit', 'access_rights_edit', 'username_edit', 'password_edit', 'password_edit_1', $user);        
        
        if (!empty($_POST['locked'])) {
            $sgn = UsersFrontEndController::test_input($_POST['locked']);
            if ($sgn == "locked") {
                $locked = 0;
            } else {
                $locked = 3;
            }
        } else {
            echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Invalid request. Field 'locked' not defined."]);	
            return;	
        }
        if (!empty($_POST["UserID_edit"])) {
            $ID = UsersFrontEndController::test_input($_POST["UserID_edit"]);
        } else {
            echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Invalid request. Requesting form is not identified."]);	
            return;	
        }            
        $user->setID($ID);
        $user->setLocked($locked);
        
        $msg = "";
        if ($user->editUser() || $_FILES['profilePhoto_edit']['name']) {
            if ($_FILES['profilePhoto_edit']['name'] != "") {
                $msg = Photo::photoUpload("profilePhoto_edit", "../public/images/", $ID, $msg, "single") ? "" : $msg;
            }
        } else {
            echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Error. User not saved.".$user->getErrMsg()]);	
            return;
        }
        echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => $msg]);	
        return;
    }
    
    public static function parseUserForm(string $nameField, string $lastnameField, string $emailField, string $accessRightsField, 
    										string $usernameField, string $passwordField_1, string $passwordField_2, User $user)
    {
        if (!empty($_POST[$nameField])) {
            $name = UsersFrontEndController::test_input($_POST[$nameField]);
        } else {
            echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Invalid request. Name field is missing."]);	
            return;
        }
        if (!empty($_POST[$lastnameField])) {
            $lastname = UsersFrontEndController::test_input($_POST[$lastnameField]);
        } else {
            echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Invalid request. Lastname field is missing."]);	
            return;
        }
        if (!empty($_POST[$emailField])) {
            if (filter_var($_POST[$emailField], FILTER_SANITIZE_EMAIL)) {
                $email = UsersFrontEndController::test_input($_POST[$emailField]);
            } else {
                echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Invalid request. Email not in valid form."]);	
                return;
            }
        } else {
            echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Invalid request. Email field is missing."]);	
            return;
        }
        if (!empty($_POST[$accessRightsField])) {
            $accessRights = UsersFrontEndController::test_input($_POST[$accessRightsField]);
        } else {
            echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Invalid request. User rights field is missing."]);	
            return;
        }
        if (!empty($_POST[$usernameField])) {
            $username = UsersFrontEndController::test_input($_POST[$usernameField]);
        } else {
            echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Invalid request. Username field is missing."]);	
            return;
        }
        
        if (!empty($_POST[$passwordField_1])) {
            $password = UsersFrontEndController::test_input($_POST[$passwordField_1]);
            if ($password != "no change") {
                if (!preg_match('/([A-Z]|[a-z])+[0-9]+/', $password)) {
                    echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Invalid request. Password complexity is weak."]);	
                    return;
                }
            }
        } else {
            echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Invalid request. Password field is missing."]);	
            return;
        }
        if (!empty($_POST[$passwordField_2])) {
            $password_2 = UsersFrontEndController::test_input($_POST[$passwordField_2]);
            if ($password_2 != $password) {
                echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Invalid request. Passwords do not match."]);	
                return;	
            }
        } else {
            echo self::renderTemplate("../templates/users_template.php", ["errorMessage" => "Invalid request. Password_repeated field is missing."]);	
            return;	
        }
       
        $user->setName($name);
        $user->setLastName($lastname);
        $user->setEmail($email);
        $user->setAccessRights($accessRights);
        $user->setUsername($username);
        $user->setPassword($password);
        
        return $user;
    }

    public static function isEmailRegistered($email)
    {
        $testUser = new User($email);
        echo $testUser->getUser($testUser, ["Email" => $email]) ? "*1" : "*2";
    }
    
    public static function deletePhoto($targetFolder, $fileName) : bool
    {
        //all profile photos are .jpg because it is forced that way when uploading
        $targetFileName = $targetFolder.$fileName.".jpg";
        if (file_exists($targetFileName)) {
            //DELETE FILE
            if (unlink($targetFileName)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function loadUsers()
    {
        $testUser = new User($_SESSION['email']);
        $userArray = new ArrayObject();
        $sgn = $testUser->getUsers($userArray);
        if (count($userArray)>0 && $sgn) {
            $str = '{"user":"'.$_SESSION['user_rights'].'","Users":'.json_encode($userArray, 110).'}';
            echo $str;
        } else {
            $str = '{"user":"'.$_SESSION['user_rights'].'","Users":'.json_encode([0 => "error",1 => $userArray[0]]).'}';
            echo $str;
        }
    }

    public static function loadUser($userID)
    {
        $testUser = new User($_SESSION['email']);
        if ($testUser->getUser($testUser, ["ID" => $userID])) {
            echo json_encode($testUser);
        } else {
            echo "*2"; //no email in database
        }
    }

    public static function deleteUser($userID)
    {
        if ($userID == $_SESSION['user_ID']) {
            echo "2";
        } else {
            $admin = new User($_SESSION['email']); //admin user is used just for making an User object. getUser() function will write real user's data into the object
            $admin->getUser($admin, ["ID" => $userID]);
            echo $admin->deleteUser($admin) ? "1" : "0";
        }
        $_REQUEST['DEL'] = null;
    }

    public static function isUsernameAvailable($username)
    {
        $testUser = new User($_SESSION['email']);
        echo $testUser->getUser($testUser, ["Username" => $username]) ? "*1" : "*2";
    }

    public static function askForAPI($passwordAPI)
    {
        $user = new User();
        $user->getUser($user, ["Email" => $_SESSION['email']]);
        $q = $user->validatePassword($passwordAPI);
        if ($q) {
            if ($user->generateAPIKey()) {
                echo '{"code": "OK", "msg":"Your new API key is:", "key":"'.$user->getAPIKey().'"}';
            } else {
                echo '{"code": "err", "msg":"Error while generating API key in DB!", "key":"'.null.'"}';
            }
        } else {
            echo '{"code": "err", "msg":"Wrong password!", "key":"'.null.'"}';
        }
    }
}