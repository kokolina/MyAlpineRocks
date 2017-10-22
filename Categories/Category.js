
function loadCategories(){
	var response = ajaxPoziv_kat("load", true);
	var k = JSON.parse(response);
	if(k.err == "*1"){
		// neka greska
	}else if(k.err == "*2"){
		//neka greska sa bazom
		
	}else{
		var tab = document.getElementById("catTable");
		
		for(i = 0; i<k.Categories.length; i++){
			var red = document.createElement("tr");
			
			var c1 = document.createElement("td");
			var id = document.createTextNode(k.Categories[i].ID);
			c1.appendChild(id);
			red.appendChild(c1);
			
			var c2 = document.createElement("td");
			var name = document.createTextNode(k.Categories[i].Name);
			c2.appendChild(name);
			red.appendChild(c2);
			
			var c3 = document.createElement("td");
			var description = document.createTextNode(k.Categories[i].Description);
			c3.appendChild(description);
			red.appendChild(c3);
			
			var c4 = document.createElement("td");
			var pk = k.Categories[i].Parent_category;
			var parent = "";
			if(pk == "0"){
				parent = document.createTextNode("-");
			}else{
				var parentCatName = "";
				for(j=0; j<k.Categories.length; j++){
					if(k.Categories[j].ID == k.Categories[i].Parent_category) parentCatName = k.Categories[j].Name;
				}
				parent = document.createTextNode(k.Categories[i].Parent_category+" "+parentCatName);	
			}			
			c4.appendChild(parent);
			red.appendChild(c4);
			
			if(k.user != "R"){
				var c5 = document.createElement("td");
				x = k.Categories[i].ID;
				c5.addEventListener("click",editCategory);
				c5.id = x;
				var c51 = document.createElement("a");
				c51.href = '#editCategory';
				var c511 = document.createElement("img");
				c511.setAttribute("class", "Ikonica");
				c511.src = 'images/edit.png';
				c51.appendChild(c511);
				c5.appendChild(c51);
				red.appendChild(c5);
						
				var c6 = document.createElement("td");
				c6.addEventListener("click",deleteCategory);
				c6.id = (k.Categories[i].ID);
				var c61 = document.createElement("a");
				c61.href = '#kategorijeDIV';
				var c611 = document.createElement("img");
				c611.setAttribute("class", "Ikonica");    //class = "Ikonica";
				c611.src = 'images/delete.ico';
				c61.appendChild(c611);
				c6.appendChild(c61);
				red.appendChild(c6);
			}
			tab.appendChild(red);
		}
		
	}
	document.getElementById("editCategory").style.display = "none";
	document.getElementById("newCategory").style.display = "none";
}

function addCategory(){
	document.getElementById("editCategory").style.display = "none";
	document.getElementById("newCategory").style.display = "inline";
	
	var nadKtSelectTab = document.getElementById("parentCategory_new");
	var response = ajaxPoziv_kat("load", true);
	var katArray = JSON.parse(response).Categories;
	for(i = 0;i<katArray.length;i++){
		var opt = document.createElement("option");
		opt.setAttribute("value",katArray[i].ID);
		opt.innerHTML = katArray[i].ID+" "+katArray[i].Name;
		nadKtSelectTab.appendChild(opt);
	}
	newCategory = {
		name : false,
		description : false
	};
}

function submit_newCategory(){
	if(newCategory.name == true && newCategory.description == true){
		document.getElementById("newCategoryFRM").submit();
		return true;
	}else if(newCategory.name == false){
		document.getElementById("nameErr_new").innerHTML = "Insert name of the category.";
		return false;
	}else{
		document.getElementById("descErr_new").innerHTML = "Insert description of the category.";
		return false;
	}
}

function editCategory(){
	document.getElementById("editCategory").style.display = "inline";
	document.getElementById("newCategory").style.display = "none";
	document.getElementById("errName_edit").innerHTML = "";
	document.getElementById("errDescription_edit").innerHTML = "";
	
	kat = getCategory(this.id);
	
	document.getElementById("idCategory_edit").value = kat.ID;
	document.getElementById("categoryName_edit").value = kat.Name;
	document.getElementById("categoryDescription_edit").value = kat.Description;
	
	var nadKtSelectTab = document.getElementById("parentCategory_edit");
	nadKtSelectTab.innerHTML = "<option value='default' selected>No parent category...</option>";
	var response = ajaxPoziv_kat("load", true);
	var katArray = JSON.parse(response).Categories;
	for(i = 0;i<katArray.length;i++){
		if(katArray[i].ID != kat.ID){
			var opt = document.createElement("option");
			opt.setAttribute("value",katArray[i].ID);
			opt.innerHTML = katArray[i].ID+" "+katArray[i].Name;
			
			nadKtSelectTab.appendChild(opt);
		}
	}
	kat.Parent_category == "0" ? nadKtSelectTab.value = "default"  : nadKtSelectTab.value = kat.Parent_category;
	
}

function getCategory(id){
	response = ajaxPoziv_kat("id", id);
	if(response != "*1"){
		kat = JSON.parse(response);
		return kat;
	}else{
		return false;
	}
	
}

function deleteCategory(){
	if(confirm("Are you sure you want to delete the category?")){
		var response = ajaxPoziv_kat("delete", this.id);
		if(response == "*1"){
			alert("Category is deleted.");
			document.getElementById("catTable").innerHTML = "<tr><td>ID</td><td>Name</td><td>Description</td><td>Parent category</td><td>Edit</td><td>Delete</td></tr>";
			loadCategories();
		}else{
			alert("Error: "+response);
		}
	}
	
}

function ajaxPoziv_kat(requestKeyName, requestKeyValue){
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
		ajax.open("POST", "Categories/AJAXFrontEndControllerCategories.php?"+requestKeyName+"="+requestKeyValue, false);
		ajax.send();
		return response;
}

function check_categoryTitle(name){
	if(name == ""){
		document.getElementById("nameErr_new").innerHTML = "Insert name of the category.";
		document.getElementById("nameErr_new").style.color = "red";
		return;
	}else{
		document.getElementById("nameErr_new").innerHTML = "";
		document.getElementById("nameErr_new").style.color = "green";
		newCategory.name = true;
	}
	
}

function dodajKatPracenje(str){
	document.getElementById("err_cat").innerHTML = document.getElementById("err_cat").innerHTML+"\n"+str;
}


