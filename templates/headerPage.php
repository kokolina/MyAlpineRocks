<div >
		<h1><img id="logo" src="../public/images/mountain-line-2.bmp"/></h1>
		<span style="display: inline; float: left;">
			<ul id = "Home" class="HorizontalMeny" >
				<li>
				<a href= "main_categories.php">Categories</a>
				</li>
				<li>
				<a href="main_products.php">Products</a>
				</li>
				<?php	echo ($_SESSION['user_rights'] != "A") ? "" :
					'<li>
					<a href="main_users.php">Users</a>
					</li>';?>
			</ul>
		</span>	
		<span class="User">
			<span><p style="display: inline; margin-right: 5px;"><?php echo $_SESSION['username']; ?></p>
			<img class="UserProfilePicture" src= "<?php echo $_SESSION['imgPath'];?>"/>	
		</span>
		</span>	
		<hr style="clear: both; margin-bottom: 0px;" />
		<span class="User">
			<p style="font-size: 0.75em; font-style: normal; color: red; margin: 0px;" class="hover_pointer" onclick="getAPI()">Get your API <img class="micro" src="../public/images/Very Basic Key.ico"/></p>
			<a style="text-decoration: none"  href="../index.php">
			<p style="font-size: 0.75em; font-style: italic; color: black; margin: 0px;" onclick="logout()">Log Out</p>			
			</a>
			<p id="token" style="display:none"><?php echo $_SESSION['token'] ?></p>
		</span>
		<br style="clear: both"/>
</div>

