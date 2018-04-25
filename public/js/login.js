
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
			a = ajaxCall("main_users.php","apigen", txtField_prompt.value)	
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

