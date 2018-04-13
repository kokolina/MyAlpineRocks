
function ajaxCall(ajaxController, requestKeyName, requestKeyValue){
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
		ajax.open("POST", ajaxController+"?"+requestKeyName+"="+requestKeyValue, false);
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
		var response = ajaxCall("Controller/main_menu.php", "email", input);
		
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

function login(){
	var input = document.getElementById("email").value;
	if(isEmailFormatOk(input, "email", "errMail")){
		if(isEmailRegistered(input, "email", "errMail")){
			document.getElementById("logInForm").submit();
			return true;
		}else{
		return false;	
		}		
	}else{
		return false;
	}
}

function logout(){
var response = ajaxCall("../Controller/main_users.php", "logout", true);
return response;
}

