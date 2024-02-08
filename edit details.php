<?php

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST['updateform'])){
			$editname = $_POST['editname'];
			$editprice = $_POST['editprice'];
			$editdescription = $_POST['editdescription'];
			$getCurrID = $_POST['idd'];
	
			$conn = new mysqli("localhost", "root", "", "uas_kel13");
			$sql = "UPDATE msmenu set name = '$editname', price = '$editprice', description = '$editdescription' where id = '$getCurrID'";
			if ($conn->query($sql) === TRUE) {
				echo "<script type='text/javascript'>alert('Food menu updated!');window.location.href='edit details.php';</script>";
			}
		}

		if(isset($_POST['deleteform'])){
			$getCurrID = $_POST['idd'];
	
			$conn = new mysqli("localhost", "root", "", "uas_kel13");
			$sql = "DELETE FROM msmenu where id = '$getCurrID'";
			if ($conn->query($sql) === TRUE) {
				echo "<script type='text/javascript'>alert('Food menu Deleted!');window.location.href='edit details.php';</script>";
			}
		}

		if(isset($_POST['addnewform'])){
			$name = $_POST['addname'];
			$price = $_POST['addprice'];
			$description = $_POST['adddescription'];

			$conn = new mysqli("localhost","root","","uas_kel13");
			
			$sql = "INSERT INTO `msmenu` (
				`name`, `price`, `description`
			) VALUES (
				'$name', '$price', '$description'
			)";
	
			if ($conn->query($sql) === TRUE) {
				$last_id = $conn->insert_id;

				$link = "image/";

				// mkdir($link, 0700);
			
				if (($_FILES['filedocument']['name']!="")){
					// Where the file is going to be stored
					$file = $_FILES['filedocument']['name'];
					$path = pathinfo($file);
					$filename = $path['filename'];
					$ext = $path['extension'];
					$temp_name = $_FILES['filedocument']['tmp_name'];
					$path_filename_ext = $link.$filename.".".$ext;
					// $fulldirectory = $directory.$file;

					// Check if file already exists
					if (file_exists($path_filename_ext)) {
						$sql = "DELETE FROM `msmenu` WHERE id = '$last_id'";
						if ($conn->query($sql) === TRUE) {
						}
						echo "<script type='text/javascript'>alert('Sudah ada file dengan identitas yang sama! Tolong re-name file!');window.location.href='edit details.php';</script>";
					}
					else{
						$sql = "UPDATE `msmenu` SET folder = '".$path_filename_ext."' WHERE id = '$last_id'";
						if ($conn->query($sql) === TRUE) {
						}
						move_uploaded_file($temp_name,$path_filename_ext);
					}
				}
	
			}
			
		}
	}
	
?>
<!DOCTYPE HTML>	
<html>
	<head>
		<title>DVP RESTAURANT</title>
		<link rel="icon" href="image/food.png" />
		<link rel="stylesheet" href="css/menu2.css">
		<link href="bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
		
			<header>
				<h1><img src="image/food.png" width="30" height="30"/>&nbsp;DVP restaurant (Edit Details)</h1>
			</header>
			
			<div class="menu">
				<ul>
					<li><a href="DVP.php"> Home</a></li>
					<li><a href="reservation.php"> Reservation</a></li>
					<li><a href="contact us.php"> Contact Us</a></li>
					<li><a href="menu.php"> Menu</a></li>
					<li><a href="promo.php"> Promo</a></li>
					<li><a href="edit details.php">Details</a></li>
				</ul>
			</div>
			<br>
			<h2><a type="button" data-bs-toggle="modal" data-bs-target="#b"><img src="image/add.ico" alt="" width="30" height="30">&nbsp;Add Menu</h2></a>
			<br>
			<?php
				$conn = new mysqli("localhost", "root", "", "uas_kel13");
				$sql = "SELECT * FROM msmenu";
				$result = $conn->query($sql);
            	if ($result->num_rows > 0){		
			?>
				

				<table class="table table-hover" style="width:100%;">
                    <thead>
                        <tr>
                            <!-- <th class="col-md-1" style="width:2%;">No.</th> -->
                            <th class="col-md-1" style="width:5%;">Name</th>
                            <th class="col-md-1" style="width:5%;">Price</th>
                            <th class="col-md-1" style="width:13%;">Description</th>
                            <th class="col-md-1" style="width:2%;">Image</th>
                            <th class="col-md-1" style="width:9%;">Actions</th>
                            
                        </tr>
                    </thead>

			<?php
				while($row = $result->fetch_assoc()) {
					$idd = $row['id'];
					$name = $row['name'];
					$price = $row['price'];
					$description = $row['description'];
					$folder = $row['folder'];
				?>
				
				<tr style="text-align: left;">
					<!-- <td style="vertical-align:middle;"><?php echo $idd;?></td> -->
					<td style="vertical-align:middle;"><?php echo $name;?></td>
					<td style="vertical-align:middle;"><?php echo $price;?></td>
					<td style="vertical-align:middle;"><?php echo $description;?></td>
					<td style="vertical-align:middle;"><img src="<?php echo $folder;?>" alt="" width="150" 
     				height="150"></td>

					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
						<td style="vertical-align:middle;">

							<a type="button"  style="color: White;" data-bs-toggle="modal" data-bs-target="#a<?php echo $idd;?>">
							<img src="image/edit.ico" alt="" width="20" height="20">
							</a>

							<button onclick="return confirm('Delete?');" name="deleteform">
							<img src="image/delete.ico" alt="" width="20" height="20">
							</button>

							<input type="hidden" name="idd" value="<?php echo $idd?>">

						</td>
					</form>
					
					<div id="a<?php echo $idd;?>" class="modal fade">
						<div class="modal-dialog" style="max-width:465px;">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title"><strong>Food Details</strong></h4>
									<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
								</div>
								
								<div class="modal-body">
									<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
										
										<input type="hidden" name="idd" value="<?php echo $idd?>">
										<label>Name:</label>
										<input  class="form-control" style="width:420px;" name="editname" value="<?php echo $name; ?>">
										<br>

										<label>Price:</label>
										<input  class="form-control" name="editprice" style="width:420px" value="<?php echo $price; ?>">
										<br>

										<label>Description:</label>
										<input  class="form-control" name="editdescription" style="width:420px" value="<?php echo $description; ?>">
										<br>

										<button class="btn btn-primary" onclick="return confirm('Update?');" name="updateform">Update</button>

										
										<br>

									</form>
								</div>
							</div>
						</div>
					</div>

					<div id="b" class="modal fade">
						<div class="modal-dialog" style="max-width:465px;">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title"><strong>Food Details</strong></h4>
									<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
								</div>
								
								<div class="modal-body">
									<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
										
										<input type="hidden" name="idd" value="<?php echo $idd?>">
										<label>Name:</label>
										<input  class="form-control" style="width:420px;" name="addname" required>
										<br>

										<label>Price:</label>
										<input  class="form-control" name="addprice" style="width:420px" required>
										<br>

										<label>Description:</label>
										<input  class="form-control" name="adddescription" style="width:420px" required>
										<br>

										<label for="formFile" class="form-label">Upload Dokumen</label>
										<input style="width:420px" class="form-control" type="file" name="filedocument" required>
										<br>

										<button class="btn btn-primary" onclick="return confirm('Add New Food Item?');" name="addnewform">Add</button>

										
										<br>

									</form>
								</div>
							</div>
						</div>
					</div>
					
					
				</tr>
				
				
				<?php }

				?>

			

			</table>
			<?php
			} ?>


			 
			<br>
			<footer>&copy Kelompok 13 - 4PHP52</footer>
		</div>
	
	</body>
</html>