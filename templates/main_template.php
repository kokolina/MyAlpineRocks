<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Online Shop</title>
	<meta name="" content="">
	<link rel="icon" href="../public/images/sheep-icon-16-23819.png" type="image/x-icon"/>
	<link rel="stylesheet" href="../design/WebShopKostaDesign.css"/>
</head>
<body >
	<?php include "../templates/headerPage.php";?>
	<br style="clear: both"/>	
	<div id="MenuDIV">
		<div class="Menu">
			<a href= "main_categories.php" class="Menu">Categories</a>
		</div>
		<div class="Menu">
			<a href= "main_products.php" class="Menu">Products</a>
		</div>
		<div class="Menu" id="BackEnd_Users">
			<?php
                if ($_SESSION['user_rights'] == 'A') {
                    echo "<a href= 'main_users.php' class='Menu'>Users</a>";
                }
            ?>		
		</div>
	</div>
	<script type="text/javascript" src="../public/js/User.js"></script>
</body>
</html>
