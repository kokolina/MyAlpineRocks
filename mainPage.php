<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Online Shop</title>
	<meta name="" content="">
	<link rel="icon" href="images/sheep-icon-16-23819.png" type="image/x-icon"/>
	<link rel="stylesheet" href="design/WebShopKostaDesign.css"/>
	<script src="Users/BackEnd.js"></script>
</head>
<body >
	<?php include "headerPage.php";?>
	<br style="clear: both"/>	
	<div id="MenuDIV">
		<div class="Menu">
			<a href="main_categories.php" class="Menu" id="BackEnd_Categories" >Categories</a>
		</div>
		<div class="Menu">
			<a href="main_products.php" class="Menu" id="BackEnd_Products">Products</a>
		</div>
		<div class="Menu" id="BackEnd_Users">
			<?php 
				if($_SESSION['user_rights'] == 'A'){
					echo "<script>
						var users = document.getElementById('BackEnd_Users');
						var link = document.createElement('a');
						link.innerHTML = 'Users';
						var att = document.createAttribute('href');
						att.value = 'main_users.php';
						link.setAttributeNode(att);
						var attCls = document.createAttribute('class');
						attCls.value = 'Menu';
						link.setAttributeNode(attCls);
						users.appendChild(link);
					</script>";
				}			
			?>		
		</div>
	</div>
</body>
</html>
