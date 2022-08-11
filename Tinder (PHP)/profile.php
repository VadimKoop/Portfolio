<!DOCTYPE html>
<html>
<body>

<?php

include('auth.php');

function upload_my_file($connect, $fileid) {
  //echo "starting";
  $target_dir = "pictures" . DIRECTORY_SEPARATOR; //windows \\
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  
  //echo "<p>$target_file " . $target_file;
  $uploadOk = 1;
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
  //echo "<p>$imageFileType " . $imageFileType;
  $saved_file = $target_dir . $fileid . "." . $imageFileType;
  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if($check !== false) {
          //echo "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
      } else {
          echo "File is not an image.";
          $uploadOk = 0;
      }
  }
  //echo "all is fine before checks";
  // Check if file already exists
  if (file_exists($target_file)) {
      echo "Sorry, file already exists.";
      $uploadOk = 0;
  }
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 5000000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
  }
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
  }
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $saved_file)) {
		  $filename = $fileid . "." . $imageFileType;
		  mysqli_query($connect,"update myvadimregister set pic = '$filename' where id = " . $_SESSION['user']['id']) or die(mysql_error());
		  $_SESSION['user']['pic'] = $filename;
          echo "<p>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br><br>";
      } else {
          echo "<p>Sorry, there was an error uploading your file.";
      }
  }
}
function def($connect, $text) {
	return mysqli_real_escape_string($connect, htmlentities($text));
}

if (isset($_POST["submit"]) && !empty($_FILES["fileToUpload"]["name"])){ //upload error
	upload_my_file($connect, $_SESSION['user']['id']);
	echo "Chose you picture";
}

if (!empty($_SESSION['user']['pic'])) {
	echo '<img src="' . "pictures" . DIRECTORY_SEPARATOR . $_SESSION['user']['pic'] . '"><br>';
}

if(isset($_POST["ok"])){
	$description = def($connect, $_POST["description"]);
	mysqli_query($connect,"update myvadimregister set description = '$description' where id = " . $_SESSION['user']['id']) or die(mysqli_error($connect));
	echo '<P>'.htmlentities($_POST["description"]).'</P>';
	
	
}

?>

Upload form:

<form action="profile.php" method="post" 
    enctype="multipart/form-data">
    Select photo upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
<form method="post" action="profile.php">
   <textarea name="description" placeholder="Write somthing about you here!"></textarea><br>
   
   <input type="submit" name="ok" value="Save"/><br
</form>
   
<br>

<a href="marks.php">marks</a>
<a href="register.php?logout=1">Logout</a> <!-- ? prisvaivaem and $-->
</body>
</html>
