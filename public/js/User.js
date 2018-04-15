
function ajaxCall(requestKeyName, requestKeyValue){
	var ajax = null;
	var response = "";
	if(window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            ajax = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            ajax = new ActiveXObject("Microsoft.XMLHTTP");
        }      
        try{
		ajax.onreadystatechange = function(){
			if(ajax.readyState == 4 && ajax.status == 200){
				response = ajax.responseText;
				}		
			}
		}catch(e){
			return false;
		}
		ajax.open("POST", "main_users.php?"+requestKeyName+"="+requestKeyValue, false);
		ajax.send();
		return response;
}

function evaluateEmail(input){
	if(isEmailFormatOk(input, "email", "errMail")){	
		return isEmailRegistered(input,"email","errMail");
	}else{		
		return false;
		}
}


function isEmailFormatOk(email, txtField, errPTab){
	if(email.indexOf("@")>0 && email.indexOf("@") == email.lastIndexOf("@")
		&& email.lastIndexOf("@")+1 < email.length
		&& email.indexOf(".")>0 && email.lastIndexOf(".") > email.indexOf("@") 
		&& email.lastIndexOf(".")+1 < email.length){
			document.getElementById(errPTab).innerHTML = "";
	return true;
		}else{
			document.getElementById(errPTab).innerHTML = 
									"Enter email in format: <br><i>example@mydomain.com<i>";
			document.getElementById(txtField).focus();
			document.getElementById(txtField).value = "";
			
	return false;
		}
}

function isEmailRegistered(input, txtField, errPTab){
		var response = ajaxCall("email", input);
		
		if(response == "*2"){			//email na postoji u bazi
				document.getElementById(errPTab).innerHTML = "Email is not registered.";
				document.getElementById(txtField).focus();
				//document.getElementById("email").value = "";
				return false;
		}else if(response.substr(0,2) == "*1"){			//email postoji u bai
				document.getElementById(errPTab).innerHTML = "";
				return true;								
		}else {
				document.getElementById(errPTab).innerHTML = "Error: "+response;
				return false;
			}
}

function logout(){
var response = ajaxCall("logout", true);
return response;
}

function loadUsers(){
      var response = ajaxCall("loadUsers", true);
		document.getElementById("usersDIV").innerHTML = response;
	}

//Funkcija se pokrece na pritisak na olovku u tabeli, prikazuje formu za izmenu podataka o useru
//ako hocu da menjam mail usera, to je onda new usera
function editUserData(input){
	var response = ajaxCall("ID",input);
	if(response == "*2"){
		dodaj("Error! User doesn't exist in database.(User.js 92)");
	}else{
		document.getElementById("createNewUser").style.display = "none";
		document.getElementById("editUserDIV").style.display = "inline";
		
		//definisanje starog usera - userEdit	
		userEdit = JSON.parse(response);
		document.getElementById("UserID_edit").value = input;		//!!! nije iz baze nego input
		document.getElementById("name_edit").value = userEdit.name;
		document.getElementById("lastname_edit").value = userEdit.lastName;
		document.getElementById("email_edit").value = userEdit.email;
		document.getElementById("username_edit").value = userEdit.username;
		document.getElementById("password_edit").value = "no change";
		document.getElementById("password_edit_1").value = "no change";
		if(userEdit.accessRights == "A"){
			document.getElementById("access_rights_edit").value = "Administrator";
		}else if(userEdit.accessRights == "W"){
			document.getElementById("access_rights_edit").value = "Writer";
		}else if(userEdit.accessRights == "R"){
			document.getElementById("access_rights_edit").value = "Reader";
		}
		if(userEdit.locked == "0"){
			document.getElementById('lockedTrue').checked = true;
		}	else{
			document.getElementById('lockedFalse').checked = true;
		}
		//definisanje novog(izmenjenog usera) newUser , kao i kod unosa
		newUser = {
		name: true,
		lastname :true,
		email: true,
		accessRights: true,
		username: true,
		pass1: true,
		pass2:true,
		photo: true,
		locked: true,
	};
		
	}
}
//funkcija prihvata ID usera i brise ga iz baze zajedno sa profilnom slikom; ako admin zeli da obrise sam sebe to ne dozvoljava
function deleteUser(input){
	var response = ajaxCall("ID", input);
	var user  = JSON.parse(response);
	if(response != "*2"){
		if(confirm("Do you really want to erase "+user.username+" from registry?")){
				var odg = ajaxCall("DEL",input);
				if(odg == "1"){
					//obrisi sliku iz foldera
					odg = ajaxCall("PHOTO_DEL", input);
					alert("User is deleted.\n"+odg);
					loadUsers();
				}else if(odg == "2"){
					alert("You can not erase yourself from user registry");
				}else{
					alert("ERROR: Deleting user failed.");
					loadUsers();
				}
		}
		
	}else{
		dodaj("User is not registered in data base.");
	}
}



//					**************
// 				ADMINISTRACIJA KORISNIKA 
// 					*************

//na pritisak dugmeta new user prikazuje formu za unos podataka o novom useru
function createUser(){
	document.getElementById("editUserDIV").style.display = "none";
	document.getElementById("createNewUser").style.display = "inline";
	document.getElementById("createNewUserFRM").reset();
	newUser = {
		name: false,
		lastname :false,
		email: false,
		accessRights: false,
		username: false,
		pass1: false,
		pass2:false,
		photo:false,
	};
	
}

function nameCheck(input, errP){
	if(input != ""){	newUser.name = input; document.getElementById(errP).innerHTML= "";
	} else {	newUser.name = false; document.getElementById(errP).innerHTML= "Insert name.";}
	
}

function lastnameCheck(input, errP){
	if(input != ""){	newUser.lastname = input; document.getElementById(errP).innerHTML= "";
	} else {	newUser.lastname = false; document.getElementById(errP).innerHTML= "Insert lastname.";}
}

function emailCheck(inputMail, txtField, errP){
	if(typeof userEdit != "undefined" && txtField == 'email_edit'){
		if(inputMail == userEdit.email){
		document.getElementById(errP).innerHTML = "";
		newUser.email = inputMail;
		return true;
		}
	}else{
		if(isEmailFormatOk(inputMail,txtField,errP)){
		var response = ajaxCall("email", inputMail);		
		if(response == "*2"){			//email na postoji u bazi
				document.getElementById(errP).innerHTML = "";
				newUser.email = inputMail;
				return true;				
		}else if(response.substr(0,2) == "*1"){			//email postoji u bazi
				
				document.getElementById(errP).innerHTML = "Email is already registered.";
				document.getElementById(txtField).focus();
				newUser.email = false;
				return false;
		}else {
				document.getElementById(errP).innerHTML = "ERROR: "+response;
				newUser.email = false;
				return false;
			}	
	}else{
		newUser.email = false;
		return false;
	}
	}
}

function accessRightsCheck(input, errP){
	if(input == "default"){
		newUser.accessRights = false;
		document.getElementById(errP).innerHTML = "Chose access rights";
	}else{
		newUser.accessRights = input;
		document.getElementById(errP).innerHTML = "";
	}	
}
//provera username-a kod unosa novog usera ili izmene: da li je zauzet. Objekat userEdit cuva stare vrednosti koje su ucitane iz baze
//Objekat userNovi se upisuje u bazu
function usernameCheck(input, txtField, errp){
	if(typeof userEdit != "undefined" && txtField == 'username_edit'){
		if(input == userEdit.username){
				document.getElementById(errp).innerHTML = "";
				newUser.username = input;
				return true;
				}	
	}else if(input == ""){
				document.getElementById(errp).innerHTML = "Insert username.";
				document.getElementById(errp).style.color = "red";	
				document.getElementById(txtField).value = "";	
				document.getElementById(txtField).focus();	
				newUser.username = false;
				return false;		
	}else{
		var response = ajaxCall("username", input);	
		if(response == "*2"){			//email na postoji u bazi
				document.getElementById(errp).innerHTML = "Username is available.";
				document.getElementById(errp).style.color = "green";
				newUser.username = input;
				return true;
		}else if(response.substr(0,2) == "*1"){			//email postoji u bai
				document.getElementById(errp).innerHTML = "Username is already taken.";
				document.getElementById(errp).style.color = "red";	
				document.getElementById(txtField).value = "";	
				document.getElementById(txtField).focus();	
				newUser.username = false;
				return false;				
		}else {
				document.getElementById(errp).innerHTML = "ERROR: "+response;
				newUser.username = false;
				return false;
			}
	}
	
}

function passwordCompexityCheck(input, txtField, errP, errRetypedPassword){
	br = ch = 0;
	if(input.length == 0){
		document.getElementById(errP).innerHTML = "Insert password";
		newUser.pass1 = false;
		return false;
	}
	if(input.length >= 6){
		for(i = 0; i<input.length; i++){
			if(isNaN(input.charAt(i))){
				ch++;
			}else{
				br++;
			}
		}
		if(ch > 0 && br > 0){
			document.getElementById(errP).innerHTML = "";
			newUser.pass1 = input;
                        if(newUser.pass2 !== false){
                            if(newUser.pass1 == newUser.pass2 || newUser.pass2 === true){
                                    document.getElementById(errRetypedPassword).innerHTML = "";
                                    return true;
                            }else{
                                    newUser.pass2 = false;
                                    document.getElementById(errRetypedPassword).innerHTML = "Passwords are not same. Try again";
                                    return false;
                            }
                        }
		}else{
			
			document.getElementById(errP).innerHTML = "Password must contain numbers and characters";
			document.getElementById(txtField).focus();
			document.getElementById(txtField).value = "";
			newUser.pass1 = false;
			return false;
		}
	}else{
		document.getElementById(errP).innerHTML = "Password has to be at least 6 characters long.";
		document.getElementById(txtField).focus();
		document.getElementById(txtField).value = "";
		newUser.pass1 = false;
		return false;
	}
}

function passwordRetypeCheck(input, txtField, errP){
	
	if(input.length == 0){
		document.getElementById(errP).innerHTML = "Insert password";
		newUser.pass2 = false;
		return false;
	}
	if(newUser.pass1 != false){
		if (input == newUser.pass1) {
			document.getElementById(errP).innerHTML  = "";
			newUser.pass2 = input;
			return true;
		}else{
			document.getElementById(errP).innerHTML = "Wrong password. Try again.";
			newUser.pass2 = false;
			return false;
		}
	}else{
			document.getElementById(errP).innerHTML  = "Please, fiel";
			newUser.pass2 = input;
	}
	
}

function photoCheck(inputValue, fileSelect, errP){
	var photo = document.getElementById(fileSelect).files;
	//provera da li je izabran file
	if(photo.length > 0){
		//provera da li je file photo [".jpg", ".png", ".jpeg", ".gif"]
		document.getElementById(errP).innerHTML = "FAJL JE IZABRAN";
		var photoFormats = [".jpg", ".png", ".jpeg", ".gif"];
		var sgn = false;
		for(var i = 0; i<photoFormats.length; i++){
			frm = photoFormats[i];
			if(inputValue.substr(inputValue.length - frm.length, frm.length).toLowerCase()== frm){
				sgn = true;
			}
		}
		if(sgn){				
				//provera velicine filea
				if(photo[0].size>5000000){
				document.getElementById(errP).innerHTML = "Izabrana photo je veca od dozvoljenih 5MB.";
				newUser.photo = false;
				return false;
						}else{							
							newUser.photo = true;
							document.getElementById(errP).innerHTML = "";
							return true;
						}
		}else{
			document.getElementById(errP).innerHTML = "Izabrani fajl nije photo ('.jpg', '.png', 'jpeg', '.gif')";
			newUser.photo = false;
			return false;
		}		
	}else{
		newUser.photo = false;
		document.getElementById(errP).innerHTML = "Izaberite profilnu sliku.";
		return false;
	}
	
}

//svi ovi parametri objekta newUser treba da se popune inputnim podacima dok user popunjava formu (onchange eventi)
//i onda ja na kraju proverim da li su svi razliciti. Objekat User se pravi kada kliknemo btt "Novi user", i svi atributi su mu false
function submit_newUser(){	
	var a = newUser.name; var b = newUser.lastname; var c = newUser.email; var d = newUser.username; 
	var e = newUser.accessRights; var f = newUser.pass1; var g = newUser.pass2; var h = newUser.photo;
	if(a != false && b != false && c != false && d != false && e != false && f != false && g != false && h!= false){
		
		document.getElementById("createNewUserFRM").submit();
		return true;
		
	}else if(a == false){
		document.getElementById("errName_new").innerHTML = "Unesite name.";
		return false;
	}else if(b == false){
		document.getElementById("errLastname_new").innerHTML = "Unesite lastname.";
		return false;
	}else if(c == false){
		document.getElementById("errMail_new").innerHTML = "Unesite e-mail.";
		return false;
	}else if(e == false){
		document.getElementById("errAccessRights_new").innerHTML = "Izaberite prava pristupa.";
		return false;
	}else if(d == false){
		document.getElementById("errUsername_new").innerHTML = "Unesite username.";
		return false;
	}else if(f == false){
		document.getElementById("errPass1_new").innerHTML = "Unesite lozinku.";
		return false;
	}else if(g == false){
		document.getElementById("errPass2_new").innerHTML = "Ponewte lozinku.";
		return false;
	}else if(h == false){
		document.getElementById("errUserImg_new").innerHTML = "Izaberite profilnu sliku.";
		return false;
	}else{
		dodaj("Neka greska<br>"+newUser.name+" <br>"+newUser.lastname+"<br> "
		+newUser.email+" <br>"+newUser.accessRights+"<br>"+newUser.username+" <br>"+newUser.pass1+"<br>"+newUser.pass2);
	return false;
	}
}

function submit_editUser(){
	var a = newUser.name; var b = newUser.lastname; var c = newUser.email; var d = newUser.username; 
	var e = newUser.accessRights; var f = newUser.pass1; var g = newUser.pass2; var h = newUser.photo;
	if(a != false && b != false && c != false && d != false && e != false && f != false && g != false && h!= false){
		
		document.getElementById("editUserFRM").submit();
		return true;
		
	}else if(a == false){
		document.getElementById("errIme_edit").innerHTML = "Unesite name.";
		return false;
	}else if(b == false){
		document.getElementById("errPrezname_edit").innerHTML = "Unesite lastname.";
		return false;
	}else if(c == false){
		document.getElementById("errMail_edit").innerHTML = "Unesite e-mail.";
		return false;
	}else if(e == false){
		document.getElementById("errAccessRights_edit").innerHTML = "Izaberite prava pristupa.";
		return false;
	}else if(d == false){
		document.getElementById("errUsername_edit").innerHTML = "Unesite username.";
		return false;
	}else if(f == false){
		document.getElementById("errPass1_edit").innerHTML = "Unesite lozinku.";
		return false;
	}else if(g == false){
		document.getElementById("errPass2_edit").innerHTML = "Ponewte lozinku.";
		return false;
	}else if(h == false){
		document.getElementById("errUserImg_edit").innerHTML = "Izaberite profilnu sliku.";
		return false;
	}else{
		dodaj("Neka greska<br>"+newUser.name+" <br>"+newUser.lastname+"<br> "
		+newUser.email+" <br>"+newUser.accessRights+"<br>"+newUser.username+" <br>"+newUser.pass1+"<br>"+newUser.pass2);
	return false;
	}
}

function getAPI(){
	var psw_prompt = document.createElement("div");
	psw_prompt.className = "pw_prompt";
	
	var msg_prompt = document.createElement("label");
	msg_prompt.textContent = "Insert password";
	psw_prompt.appendChild(msg_prompt);
	
	var txtField_prompt = document.createElement("input");
	txtField_prompt.setAttribute("type", "password");
	psw_prompt.appendChild(txtField_prompt);
	
	var errMsg = document.createElement("label");
	psw_prompt.appendChild(errMsg);
	
	var apiMsg = document.createElement("label");	
	var apiKey = document.createElement("label");
	
	var submit = function(){
		if (txtField_prompt.value != "") {	
			a = ajaxCall("apigen", txtField_prompt.value)	
			response = JSON.parse(a);
				psw_prompt.removeChild(msg_prompt);
				psw_prompt.removeChild(txtField_prompt);
				psw_prompt.removeChild(errMsg);
				psw_prompt.removeChild(button);
				
				apiMsg.textContent = response.msg;
				apiKey.textContent = response.key;
				psw_prompt.appendChild(apiMsg);
				psw_prompt.appendChild(apiKey);
				psw_prompt.appendChild(OKbutton);
				psw_prompt.setAttribute("style","left: 35%");			
				psw_prompt.setAttribute("style","width: 600px");
				
		}else {
			errMsg.textContent = "Insert your password";		
		}		
	};
	
	var quit = function () {
		document.body.removeChild(psw_prompt);
	};
	var button = document.createElement("button");
    button.textContent = "Get API";
    button.addEventListener("click", submit, false);
    button.setAttribute("class", "MyButton");
    psw_prompt.appendChild(button);	
	
	
	var OKbutton = document.createElement("button");
    OKbutton.textContent = "OK";
    OKbutton.addEventListener("click", quit, false);
    OKbutton.setAttribute("class", "MyButton");
    
	document.body.appendChild(psw_prompt);
	
	
}


//koristim je za pracenje izvrsenja programa. treba je se otarasiti!
function dodaj(x){
	document.getElementById("pracenje").innerHTML = document.getElementById("pracenje").innerHTML+"<br>"+x;
	//document.getElementById("createNewUserFRM").submit();
}