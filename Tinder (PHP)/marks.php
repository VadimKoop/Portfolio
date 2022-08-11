<?php

include('auth.php');

if(isset($_POST['like'])) {
	$query = mysqli_query($connect, "SELECT * FROM mylikevadim WHERE id1 = ".$_SESSION['user']['id']." AND id2 = ".$_GET['id']);
	if(mysqli_num_rows($query) == 0) {
		$query = mysqli_query($connect, "INSERT INTO mylikevadim (id1, id2, meeldib) values (".$_SESSION['user']['id'].", ".$_GET['id'].", 'yes')");
	}
}

if(isset($_POST['dislike'])) {
	$query = mysqli_query($connect, "SELECT * FROM mylikevadim WHERE id1 = ".$_SESSION['user']['id']." AND id2 = ".$_GET['id']);
	if(mysqli_num_rows($query) == 0) {
		$query = mysqli_query($connect, "INSERT INTO mylikevadim (id1, id2, meeldib) values (".$_SESSION['user']['id'].", ".$_GET['id'].", 'no')");
	}
}

$prev_id = 0;

if (!empty($_GET['id'])) { // ? = get
	$prev_id = $_GET['id'];
}

$query = mysqli_query($connect,"SELECT * FROM myvadimregister WHERE id != " . $_SESSION['user']['id'] . " AND id > " . $prev_id . " AND pic IS NOT NULL AND sex != '".$_SESSION['user']['sex']."' AND id NOT IN (SELECT id2 FROM mylikevadim WHERE id1 = ".$_SESSION["user"]["id"].") ORDER BY id");

if (mysqli_num_rows($query) == 0) {
	$query = mysqli_query($connect,"SELECT * FROM myvadimregister WHERE id != " . $_SESSION['user']['id'] . " AND pic IS NOT NULL AND sex != '".$_SESSION['user']['sex']."' AND id NOT IN (SELECT id2 FROM mylikevadim WHERE id1 = ".$_SESSION["user"]["id"].") ORDER BY id");
}

if (mysqli_num_rows($query) == 0) {
	echo "No accounts.";
	echo '<a href="http://dijkstra.cs.ttu.ee/~Vadim.Zlobin/prax4/profile.php" >Profile</a>';
	exit;
}

$user_data = mysqli_fetch_array($query);


$description = $user_data['description'];
?>

<img src="<?php echo 'pictures' . DIRECTORY_SEPARATOR . $user_data['pic'] ?>"><br>
<?php 
echo "<P>".$description."</P>";
?>
<form method="post" action="marks.php?id=<?php echo $user_data['id'] ?>">
   <input type="submit" name="like" value="Like"/>
   <input type="submit" name="dislike" value="Dislike"/>
   <a href="http://dijkstra.cs.ttu.ee/~Vadim.Zlobin/prax4/profile.php" id="red">Profile Here</a><p>
</form>

<a href="register.php?logout=1">Logout</a>