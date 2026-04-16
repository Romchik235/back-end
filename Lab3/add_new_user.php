<?php
mysqli_report(MYSQLI_REPORT_OFF);

$root = mysqli_connect("localhost", "admin", "admin", null, 3706);
if (!$root){
    die("connection error: " . mysqli_connect_error());
}

$newUser = "user3";
$newPass = "pass123";
$dbName  = "first_db";

$sql1 = "CREATE USER '$newUser'@'localhost' IDENTIFIED BY '$newPass'";
if (!mysqli_query($root, $sql1)){
    echo("user error: " . mysqli_error($root) . "<br>");
}

$sql2 = "GRANT SELECT, INSERT, UPDATE, DELETE ON `$dbName`.* TO '$newUser'@'localhost'";
if (!mysqli_query($root, $sql2)){
    echo("grant error: " . mysqli_error($root) . "<br>");
}
mysqli_query($root, "FLUSH PRIVILEGES");

echo "OK. Created MySQL user '$newUser' with access to DB '$dbName'.";
echo '<br><a href="index.php">На головну</a>';

mysqli_close($root);
?>
