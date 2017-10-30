<?php
	if(!isset($_SESSION)){
	    $s = session_start();
	}
	
	if(!isset($_SESSION['username'])){
				session_start();
				session_destroy();
				header("Location: /WebShopX/index.php");
   			exit;
	}	
	include_once "db/db_config.php";    
	include_once "db/DBController.php";
	include_once "Products/ProductsRepository.php";
	include_once "Categories/CategoryRepository.php";
	include_once "Categories/Category.php";
	include_once "Products/Product.php";
	include_once "Photo/Photo.php";
	include_once "Products/BackEndControllerProducts.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Online shop</title>
	<meta name="" content="">
	<link rel="icon" href="../images/sheep-icon-16-23819.png" type="image/x-icon"/>
	<link rel="stylesheet" href="design/WebShopKostaDesign.css"/>
	<script type="text/javascript" src="Products/BackEnd_Products.js"></script>
	<script type="text/javascript" src="Users/BackEnd.js"></script>	
</head>
<body onload="loadProducts()" >
	<?php
		include "headerPage.php";
	?>
	<div>		
	</div>
	<div id="newProduct" style="display: none;">
		<fieldset>
			<legend>NEW PRODUCT</legend>
				<form id = "newProductFRM" method="post" target="_self" action="main_products.php" enctype="multipart/form-data">
					Name:<br />
					<input type="text" class="MyTextfield" id="productName_new" name="productName_new" required><br />
					<p class="err" id = "errName_new"></p><br />
					Description:<br />
					<textarea id="productDescription_new" name="productDescription_new" rows="5" cols="50" required="true"></textarea><br />
					<p class="err" id = "errDescription_new"></p><br />
					Price:<br />
					<input type="number" min="0.01" step="0.01" class="MyTextfield" id="productPrice_new" name="productPrice_new"placeholder="00.00" onchange="priceCheck(this.value)"  required="true"/>
			
					<p class="err" id = "errPrice_new"></p><br />
					<p style="margin-bottom: 0px;">Product category:</p>
					<p style="font-size: 0.7em; font-style: italic; margin-top: 0px;">Selection of multiple categories allowed</p>
					<select name="categoryOfProduct_new[]" id="categoryOfProduct_new" style="border: black; border-style: solid;
						 border-width: 1px; margin: 10px; margin-left: 0px; margin-top: 0px;" size="5"  multiple required>
						 	
					</select><br />
					<p class="err" id = "errCategory_new"></p><br />
					<label for="productPhoto_new" class="MyButton">Upload photo</label>
					<input type="file" id="productPhoto_new" name="productPhoto_new[]" style="display: none;"  multiple="true" onchange="photoCheck_product(this.id)"/><br />
					<p class="err" id="err_productPhoto_new"></p><br />
					<input type="submit" name="submit_newProduct" id="submit_newProduct" class="MyButton" value="Save" />
				</form>
			
		</fieldset>
	</div>
	<div id="editProduct" style="display: none;">
		<fieldset>
			<legend>CHANGE PRODUCT DATA</legend>
				<form id="editProductFRM" method="post" target="_self" action="main_products.php" enctype="multipart/form-data">
				<input id="IDProduct_edit" name="IDProduct_edit" style="display: none;"/>
				Name:<br />
					<input type="text" class="MyTextfield" id="productName_edit" name="productName_edit" required><br />
					<p class="err" id = "errName_edit"></p><br />
					Description:<br />
					<textarea id="productDescription_edit" name="productDescription_edit" rows="5" cols="50"></textarea><br />
					<p class="err" id = "errDescription_edit"></p><br />
					Price:<br />
					<input type="number" min="0.01" step="0.01" class="MyTextfield" id="productPrice_edit" name="productPrice_edit" placeholder="00.00" onchange="priceCheck(this.value)"  required="true"/> EUR <br />
					<p class="err" id = "errPrice_edit"></p><br />
					Parent category:<br />
					<select size="4" name="categoryOfProduct_edit[]" id="categoryOfProduct_edit" style="border: black; border-style: solid;
						 border-width: 1px; margin: 10px; margin-left: 0px; margin-top: 0px;" multiple required>
		
					</select><br />
					<p class="err" id = "errCategory_edit"></p><br />
					<label for="productPhoto_edit" class="MyButton">Upload photo</label>
					<input type="file" id="productPhoto_edit" name="productPhoto_edit[]" style="display: none;"  multiple="true" onchange="photoCheck_product(this.id)"/><br />
					<p class="err" id="err_productPhoto_edit"></p><br />
					<input type="submit" name="submit_editProduct" id="submit_editProduct" class="MyButton" value="Save changes" />	
				</form>
		</fieldset>
	</div>
	<div>
		<div id="productsDIV" style="margin:auto; margin-left: 20px;padding: 10px;">
		<?php
		echo ($_SESSION['user_rights'] == "R") ? "" :
		'<span style="float: left;">
			<input type="button" id="NewBtt" class="MyButton" value="New product" onclick="addProduct()" style="display: inline;"/><br />
			<p id="err_cat"></p>
		</span>';
		?>
			<table id="productsTable" style="clear: both;">
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Description</th>
					<th>Price (RSD)</th>
					<th>Parent category</th>
					<th>Photos</th>
					<?php
					echo ($_SESSION['user_rights'] == "R") ? "" :
					'<th>Edit</th>
					<th>Delete</th>';
					?>
				</tr>
			</table>
			
			
		</div>
    </div>
    <p id="err_pro"></p>
</body>
</html>
