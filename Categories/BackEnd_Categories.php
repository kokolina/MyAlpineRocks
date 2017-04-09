<?php
if(!isset($_SESSION)){
	    $s = session_start();
	    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Online shop</title>
	<meta name="" content="">
	<link rel="icon" href="../images/sheep-icon-16-23819.png" type="image/x-icon"/>
	<link rel="stylesheet" href="../WebShopKostaDesign.css"/>
	<script type="text/javascript" src="Category.js"></script>
	
</head>
<body onload="loadCategories()">
	<div >
		<h1><img id="logo" src="../images/mountain-line-2.bmp"/></h1>
		<span style="display: inline; float: left;">
			<ul id = "Home" class="HorizontalMeny" >
				<li>
				<a href="BackEnd_Categories.php">Categories</a>
				</li>
				<li>
				<a href="../Products/BackEnd_Products.php">Products</a>
				</li>
				<?php
					echo ($_SESSION['user_rights'] != "A") ? "" :
					'<li>
					<a href="../BackEnd_Users.php">Users</a>
					</li>';
				?>
			</ul>
		</span>	
		<span class="User" >
			<span><p style="display: inline; margin-right: 5px;"><?php echo $_SESSION['username']; ?></p>
			<img class="UserProfilePicture" src= "<?php echo '../'.$_SESSION['imgPath'];?>"/>
			</span>
		</span>
		<hr style="clear: both; margin-bottom: 0px;" />
		<span class="User">
			<a style="text-decoration: none"  href="../BackEnd.html">
			<p style="font-size: 0.75em; font-style: italic; color: black; margin: 0px;">Log Out</p>
			</a>
		</span>
		<br style="clear: both"/>
	</div>
	<div id="newCategory" style="display: none;">
		<fieldset>
			<legend>NEW CATEGORY DATA</legend>
				<form id = "newCategoryFRM" method="post" target="_self" action="BackEndFormController_Cat.php" enctype="multipart/form-data">
					Category name:<br />
					<input type="text" class="MyTextfield" id="categoryName_new" name="categoryName_new" onchange="return check_categoryTitle(this.value)" required><br />
					<p id="nameErr_new" class="err"></p><br />
					Descriptione:<br />
					<textarea id="categoryDescription_new" name="categoryDescription_new" rows="5" cols="50" required></textarea><br />
					<p id="descErr_new" class="err"></p><br />
					Parent category:<br />
					<select name="parentCategory_new" id="parentCategory_new" style="height: 25px; border: black; border-style: solid;
						 border-width: 1px; margin: 10px; margin-left: 0px;" required>
						 <option value="default">No parent category</option>
					</select>
					<input type="submit" name="submit_newCategory" id="submit_newCategory" class="MyButton" value="Unesi podatke" onclick="return submit_newCategory()"/>
				</form>
		</fieldset>
	</div>
	<div id="editCategory" style="display: none;">
		<fieldset>
			<legend>EDIT CATEGORY</legend>
				<form id="editCategoryFRM" method="post" target="_self" action="BackEndFormController_Cat.php" enctype="multipart/form-data">
				<input type="text" class="MyTextfield" id="idCategory_edit" name="idCategory_edit" style="display: none;" required >
				Category name:<br />
					<input type="text" class="MyTextfield" id="categoryName_edit" name="categoryName_edit"  required><br />
					<p id="errName_edit" class="err"></p><br />
					Description:<br />
					<textarea id="categoryDescription_edit" name="categoryDescription_edit" rows="5" cols="50" required></textarea><br />
					<p id="errDescription_edit" class="err"></p><br />
					Parent category:<br />
					<select name="parentCategory_edit" id="parentCategory_edit" style="height: 25px; border: black; border-style: solid;
						 border-width: 1px; margin: 10px; margin-left: 0px;" onchange="" required>
						 <option value="def">Ne postoji...</option>
					</select>
					<input type="submit" name="submit_editCategory" id="submit_editCategory" class="MyButton" value="Save changes" />	
				</form>
		</fieldset>
	</div>
	<div>
		<div id="categoryDIV" style="margin:auto; margin-left: 20px;padding: 10px;">
			<table id="catTable">
				<tr>
					<th>ID</th>
					<th>Category title</th>
					<th>Description</th>
					<th>Parent category</th>
					<?php
					echo ($_SESSION['user_rights'] == "R") ? "" :
					'<th>Edit</th>
					<th>Delete</th>';
					?>
				</tr>
			</table>
			
			
		</div>
		<?php
		echo ($_SESSION['user_rights'] == "R") ? "" :
		'<div style="margin-left: 20px; margin-bottom: 10px;">
				<input type="button" id="NewBtt" class="MyButton" value="New category" onclick="addCategory()" style="display: inline;"/><br />
				<p id="err_cat"></p>
	    </div>';
	?>
    </div>
</body>
</html>
