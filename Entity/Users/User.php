<?php
namespace Myalpinerocks;

use \ArrayObject;

class User
{
    public $ID;
    
    public $name;
    
    public $lastName;
    
    public $username;
    
    public $email;
    
    public $password;
    
    public $locked;
    
    public $accessRights;
    
    public $status;
    
    public $APIKey;
    private $uRepository;
    public $err;
    
    public function __construct(string $e = "")
    {
        $this->uRepository = new UserRepository();
        $this->email = $e;
        $this->err = [];
        $this->ID = 0;
        $this->name = "";
        $this->lastName = "";
        $this->username = "";
        $this->password = "";
        $this->locked = 0;
        $this->status = 0;
        $this->accessRights = "R";        
        $this->APIKey = "";
    }
    
    public function getUser(User $user, array $columnValuePairs) : bool
    {
        $this->uRepository->openDataBaseConnection();
        if ($this->uRepository->getUser($user, $columnValuePairs)) {
            $this->uRepository->closeDataBaseConnection();
            return true;
        } else {
            $this->uRepository->closeDataBaseConnection();
            return false;
        }
    }
    
    public function logIn() : bool
    {
        $testUser = new User($this->email);
        
        $this->uRepository->openDataBaseConnection();
        
        if ($this->uRepository->getUser($testUser, ["Email" => $testUser->getEmail()])) {
            $sgn = false;
            if ($testUser->getLocked() != 0) {
                if ($testUser->getPassword() === $this->password) {
                    $this->uRepository->unlockUser($this);
                    $this->ID = $testUser->getID();
                    $this->name = $testUser->getName();
                    $this->lastName = $testUser->getLastName();
                    $this->username = $testUser->getUsername();
                    $this->locked = $testUser->getLocked();
                    $this->accessRights = $testUser->getAccessRights();
                    $this->err = $testUser->getERRStatus();
                    $this->uRepository->logLogin($this->getID());
                    $sgn = true;
                } else {
                    $this->setERRStatus("pass", "Wrong password presented");
                    $this->setLocked($testUser->getLocked());
                    $this->lockUser();
                    $sgn = false;
                }
            } else {
                $this->setERRStatus("zak", "ERR:User account is locked");
                $sgn = false;
            }
        } else {
            $this->err = $testUser->getERRStatus();
            $sgn = false;
        }
        $this->uRepository->closeDataBaseConnection();
        return $sgn;
    }
     
    public function lockUser() : bool
    {
        return $this->uRepository->lockUser($this);
    }
    
    public function unlockUser() : bool
    {
        return $this->uRepository->unlockUser($this);
    }
    
    public function newUser() : bool
    {
        $testEmail = $testUsername = false;
        
        $testUser = new User($this->email);
        
        $this->uRepository->openDataBaseConnection();
        
        $this->uRepository->getUser($testUser, ["Email" => $testUser->getEmail()]);
        if ($testUser->getErrKod()=="n") {
            $testEmail = true;
        } elseif ($testUser->getErrKod()== "ok") {
            $this->setERRStatus("errMail", "User email is already registered.");
            $testEmail = false;
        } else {
            $this->setERRStatus("err", "Problem when checking email");
            $testEmail = false;
        }
        
        $this->uRepository->getUser($testUser, ["Username" => $this->getUsername()]);
        
        if ($testUser->getErrKod()=="n") {
            $testUsername = true;
        } elseif ($testUser->getErrKod()== "ok") {
            $this->setERRStatus("errMail", "Username already registered.");
            $testUsername = false;
        } else {
            $testUsername = false;
            $this->setERRStatus("error", "Msg 1.");
        }
        if ($testEmail == true && $testUsername == true) {
            if ($this->uRepository->insertUser($this)) {
                $this->setERRStatus("ok", "New user saved.");
                $this->uRepository->closeDataBaseConnection();
                return true;
            }
        } else {
            $this->setERRStatus("not ok", "Something is wrong 2.");
            $this->uRepository->closeDataBaseConnection();
            return false;
        }
    }
    
    public function editUser() : bool
    {
        $this->uRepository->openDataBaseConnection();
        $testUser = new User($this->getEmail());
        if ($this->uRepository->getUser($testUser, ["Email" => $testUser->getEmail()])) {
            if ($this->getLocked() == 3 && $testUser->getLocked() != 0) {
                $this->setLocked($testUser->getLocked());
            }
            if ($this->getPassword() == "no change") {
                $this->setPassword($testUser->getPassword());
            }
            if ($this->uRepository->editUser($this, $testUser)) {
                $this->uRepository->closeDataBaseConnection();
                return true;
            } else {
                return false;
            }
        } else {
            $this->setERRStatus("baza", "User doesn't exist in database");
            return false;
        }
    }
    
    public function getUsers(ArrayObject $userArray) : bool
    {
        $this->uRepository->openDataBaseConnection();
        $sgn = $this->uRepository->getUsers($userArray);
        $this->uRepository->closeDataBaseConnection();
        return $sgn;
    }
    
    public function deleteUser(User $user) : bool
    {
        $this->uRepository->openDataBaseConnection();
        if ($this->uRepository->deleteUser($user)) {
            $this->uRepository->closeDataBaseConnection();
            return true;
        } else {
            $this->uRepository->closeDataBaseConnection();
            return false;
        }
    }
    
    public function validatePassword(string $password) : bool
    {
        $this->getUser($this, ["Email" => $this->getEmail()]);
        
        if ($this->getPassword() === hash("sha256", $password, $raw_output = false)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function generateAPIKey() : bool
    {
        if ($this->getUser($this, ["Email" => $this->getEmail()])) {
            $this->uRepository->openDataBaseConnection();
            $sgn = $this->uRepository->generateAPIKey($this);
            $this->uRepository->closeDataBaseConnection();
            return $sgn;
        } else {
            return false;
        }
    }
    
    //    setters
    public function setID(int $i)
    {
        $this->ID = $i;
    }
    public function setName(string $i)
    {
        $this->name = $i;
    }
    public function setLastName(string $i)
    {
        $this->lastName = $i;
    }
    
    public function setUsername(string $i)
    {
        $this->username = $i;
    }
        
    public function setPassword(string $i)
    {
        $this->password = $i;
    }
    public function setLocked(int $i)
    {
        $this->locked = $i;
    }
    public function setEmail(string $i)
    {
        $this->email = $i;
    }
    public function setAccessRights(string $i)
    {
        $this->accessRights = $i;
    }
    public function setAPIKey(string $ak)
    {
        $this->APIKey = $ak;
    }
    public function setERRStatus(string $i, string $p)
    {
        foreach ($this->err as $kod => $msg) {
            $p.=" >> ".$msg;
        }
        $this->err = [$i => $p];
    }
    public function setStatus(int $i)
    {
        $this->status = $i;
    }
    
    //    getters
    public function getID() : int
    {
        return $this->ID;
    }
    public function getName() : string
    {
        return $this->name;
    }
    public function getLastName() : string
    {
        return $this->lastName;
    }
    public function getUsername() : string
    {
        return $this->username;
    }
    public function getPassword() : string
    {
        return $this->password;
    }
    public function getLocked() : int
    {
        return $this->locked;
    }
    public function getEmail() : string
    {
        return $this->email;
    }
    public function getAccessRights() : string
    {
        return $this->accessRights;
    }
    public function getAPIKey() : string
    {
        return $this->APIKey;
    }
    public function getERRStatus() : array
    {
        return $this->err;
    }
    public function getErrKod() : string
    {
        foreach ($this->err as $i => $msg) {
            return $i;
        }
    }
    public function getErrMsg() : string
    {
        foreach ($this->err as $i => $msg) {
            return $msg;
        }
    }
    public function getStatus() : int
    {
        return $this->status;
    }
}

