<?php 
require_once "pdo.php";

// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

$failure = false;
$success = false;

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {

	$make = htmlentities($_POST['make']);
	$year = htmlentities($_POST['year']);
	$mileage = htmlentities($_POST['mileage']);

	if (strlen($make) < 1 ) {
		$failure = "Make is required";
	}
	elseif (! is_numeric($year) || (! is_numeric($mileage)) || strlen($year) < 1 || strlen($mileage) < 1) {
		$failure = "Mileage and year must be numeric";
	}
	else {

		$stmt = $pdo->prepare('INSERT INTO autos
        (make, year, mileage) VALUES ( :mk, :yr, :mi)');
    	$stmt->execute(array(
        ':mk' => $make,
        ':yr' => $year,
        ':mi' => $mileage)
    );
    	$success = "Record inserted";

	}
}

$stmt = $pdo->query("SELECT make, year, mileage FROM autos ORDER BY mileage");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html>
<head>
<title>Uwem Uke's Automobile Tracker</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Tracking Autos for <?= htmlentities($_REQUEST['name']); ?></h1>

<?php
if ( $failure !== false ) {
    print '<p style="color:red">';
    print htmlentities($failure);
    print "</p>\n";
}
else {
	print '<p style="color:green">';
	print htmlentities($success);
	print "</p>\n";
}
?>

<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" value="Add">
<input type="submit" name="logout" value="Logout">
</form>

<h2>Automobiles</h2>
<p>
<?php
foreach ($rows as $row) {
	echo "<ul><li>";
	echo ($row['year']);
	echo " ";
	echo ($row['make']);
	echo " ";
	echo "/";
	echo " ";
	echo ($row['mileage']);
	echo "</li></ul>\n";
}

?>
</p>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/d07b1474/cloudflare-static/email-decode.min.js"></script></body>
</html>
