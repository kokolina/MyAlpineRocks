			
			//					**** P R O D U C T S ****
			
function loadProducts(){
	var response = ajaxCall_prod("load", true, "AJAXFrontEndControllerProducts.php");
	var k = JSON.parse(response)
	if(k.Products == "*1"){
		//tabela je prazna
	}else if(k.Products == "*2"){
		//neka greska sa bazom
	}else{
		var tab = document.getElementById("productsTable");
		for(i = 0; i<k.Products.length; i++){
			var red = document.createElement("tr");
			
			var c1 = document.createElement("td");
			var id = document.createTextNode(k.Products[i].ID);
			c1.appendChild(id);
			red.appendChild(c1);
			
			var c2 = document.createElement("td");
			var name = document.createTextNode(k.Products[i].Name);
			c2.appendChild(name);
			red.appendChild(c2);
			
			var c3 = document.createElement("td");
			var description = document.createTextNode(k.Products[i].Description);
			c3.appendChild(description);
			red.appendChild(c3);
			
			var c4 = document.createElement("td");
			var price = document.createTextNode(k.Products[i].Price);
			c4.appendChild(price);
			red.appendChild(c4);
			
			var c8 = document.createElement("td");
			var katNiz = k.Products[i].Categories;
			for(j = 0;j<katNiz.length;j++){
				var categories = document.createElement("P"); 
				var categoriesTxt = document.createTextNode(katNiz[j].ID_category+" "+katNiz[j].Name);
				categories.appendChild(categoriesTxt);
				c8.appendChild(categories);
			}			
			red.appendChild(c8);
			
			var c7 = document.createElement("td");
			c7.setAttribute("class", "tableFieldPhotos");
			var sl = k.Products[i].photos; 	//niz slika	
			if(sl[0] != null){					
				for(j = 0;j<sl.length;j++){					
					var photo = document.createElement("img");
					photo.src = sl[j];
					photo.setAttribute("class","productPhotoSmall");
					var deleteIco = document.createElement("img");
					deleteIco.src = "../images/delete.ico";
					deleteIco.setAttribute("class", "deleteIco");
					deleteIco.setAttribute("id", (i+1)+"_/"+(j+1)+"_ico");	
					photo.setAttribute("id", (i+1)+"_/"+(j+1));
					photo.onmouseover = function(){
						showDeleteIco(true, this.id);
					}
					photo.onmouseout = function(){
						showDeleteIco(false, this.id);
					}
					photo.onclick = function(){
						deletePhoto(this.id, this.src);
					}
								
					c7.appendChild(photo);
					c7.appendChild(deleteIco);
				}
			}
			red.appendChild(c7);
			if(k.user != "R"){
				var c5 = document.createElement("td");
				c5.setAttribute("id",k.Products[i].ID);
				c5.addEventListener("click", function(){editProduct(this.id);});
				var c51 = document.createElement("a");
				c51.href = '#editProduct';
				var c511 = document.createElement("img");
				c511.setAttribute("class", "Ikonica");
				c511.src = '../images/edit.png';
				c51.appendChild(c511);
				c5.appendChild(c51);
				red.appendChild(c5);		
			
				var c6 = document.createElement("td");
				c6.setAttribute("id", k.Products[i].ID);
				c6.addEventListener("click",function(){deleteProduct(this.id)});
				var c61 = document.createElement("a");
				c61.href = '#proizvodiDIV';
				var c611 = document.createElement("img");
				c611.setAttribute("class", "Ikonica");    //class = "Ikonica";
				c611.src = '../images/delete.ico';
				c61.appendChild(c611);
				c6.appendChild(c61);
				red.appendChild(c6);
			}
			tab.appendChild(red);
		}
	}
}

function ajaxCall_prod(requestKeyName, requestKeyValue, PHPfile){
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
		ajax.open("POST", PHPfile+"?"+requestKeyName+"="+requestKeyValue, false);
		ajax.send();
		return response;
}

function addProduct(){
	var response = ajaxCall_prod("load",true,"../Categories/AJAXFrontEndControllerCategories.php");
	var katArray = JSON.parse(response).Categories;
	nadKtSelectTab = document.getElementById("categoryOfProduct_new");
	for(i = 0;i<katArray.length;i++){
		var opt = document.createElement("option");
		opt.setAttribute("value",katArray[i].ID);
		opt.innerHTML = katArray[i].ID+" "+katArray[i].Name;
		nadKtSelectTab.appendChild(opt);
	}
	document.getElementById("productPhoto_new").value = "";
	
	document.getElementById("newProduct").style.display = "inline";
	document.getElementById("editProduct").style.display = "none";
	
}
//fills the form for editing product data
function editProduct(id){
	document.getElementById("newProduct").style.display = "none";
	document.getElementById("editProduct").style.display = "inline";
	
	
	var response = ajaxCall_prod("editProduct", id, "AJAXFrontEndControllerProducts.php");
	var pr = JSON.parse(response);  //returns product data, without data about categroies of product
	
	document.getElementById("IDProduct_edit").value = pr.id;
	document.getElementById("productName_edit").value = pr.name;
	document.getElementById("productDescription_edit").value = pr.description;
	document.getElementById("productPrice_edit").value = pr.price;
	
	var response = ajaxCall_prod("load",true,"../Categories/AJAXFrontEndControllerCategories.php");
	katArray = JSON.parse(response).Categories;   
	
	nadKtSelectTab = document.getElementById("categoryOfProduct_edit");
	nadKtSelectTab.innerHTML = "";
	for(i = 0;i<katArray.length;i++){
		var opt = document.createElement("option");
		opt.setAttribute("value",katArray[i].ID);
		opt.innerHTML = katArray[i].ID+" "+katArray[i].Name;
		for(j = 0; j<pr.categories.length; j++){
			if(katArray[i].ID == pr.categories[j].ID){
				opt.setAttribute("selected",true);
			}
		}
		nadKtSelectTab.appendChild(opt);
	}
}

function deleteProduct(productID){
	if(confirm("Are you sure you want to delete the prodict?")){	
		var response = ajaxCall_prod("deleteProduct", productID, "AJAXFrontEndControllerProducts.php");
		if(response == "1"){
			alert("Product is deleted.");
			document.getElementById("productsTable").innerHTML = "<tr><td>ID</td><td>Name</td><td>Description</td><td>Price</td><td>Parent category</td><td>Photos</td><td>Edit</td><td>Delete</td></tr>";
			loadProducts();
		}else{
			alert("Product is NOT deleted.");
		}	
	}
	
}

function priceCheck(c){
	//proveri da li je broj
	//proveri da li je na dve decimale
	if(c!=""){	
		if(isNaN(c)){
		document.getElementById("errPrice_new").innerHTML = "Price has to be in format 00.00. "+c;
		}else{
			document.getElementById("productPrice_new").value = Math.round(c*100)/100;
			document.getElementById("errPrice_new").innerHTML = "";		
		}
	}else{
		document.getElementById("errPrice_new").innerHTML = "Insert product price (00.00). "+c;
	}
}

function dodajProPracenje(str){
	document.getElementById("err_pro").innerHTML = document.getElementById("err_pro").innerHTML+"\n"+str;
}
//funkcija uzima id input file taga
function photoCheck_product(idFile){
	var photos = document.getElementById(idFile).files;
	var brF = 0; brS = 0;
	if(photos.length > 0){
		//za svaku sliku, proveri format, velicinu
		for(i=0; i<photos.length; i++){
			
			var photoFormats = [".jpg", ".png", ".jpeg", ".gif"];
			for(j=0; j<photoFormats.length; j++){
				var sFormat = photos[i].name.substr(photos[i].name.length - photoFormats[j].length, photos[i].name.length);
					if(sFormat.toLowerCase() == photoFormats[j]){
						brF++;
					}
			}
			
			if(photos[i].size <5000000){
					brS++;
					
			}		
		}
		if(brF == photos.length){
			document.getElementById("err_"+idFile).innerHTML = "";
			}else{
				document.getElementById("err_"+idFile).innerHTML = "Slike nisu odgovarajuceg formata.";
				document.getElementById("err_"+idFile).style.color = "red";
				document.getElementById(idFile).value = "";
			}
		if(brS == photos.length){
			document.getElementById("err_"+idFile).innerHTML = "";
			}else{
				document.getElementById("err_"+idFile).innerHTML = "Slike nisu odgovarajuce velicine.";
				document.getElementById("err_"+idFile).style.color = "red";
				document.getElementById(idFile).value = "";
			}
		if(brF == photos.length && brS == photos.length){
			document.getElementById("err_"+idFile).innerHTML = photos.length+" photos selected.";
			document.getElementById("err_"+idFile).style.color = "green";
		}
	}
	
}

function deletePhoto(id, path){
	if(confirm("Da li zelite da obrisete sliku?")){		
		var filename = path.substring(path.lastIndexOf("/"));
		var folderPath = path.substring(0,path.lastIndexOf("/"));
		var foldername = folderPath.substring(folderPath.lastIndexOf("/"));
		var filePath = foldername+filename;
		
		var response = ajaxCall_prod("deletePhoto","../Products/imagesProducts"+filePath, "AJAXFrontEndControllerProducts.php");
		if(response == "1"){
			document.getElementById(id).style.display = "none";
			document.getElementById(id+"_ico").style.display = "none";
		}else{
			alert("Nije obrisana slika");
		}
	}
}

function showDeleteIco(sgn, id){
	document.getElementById(id+"_ico").style.visibility = sgn ? "visible" : "hidden";
}
