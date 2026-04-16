<?php
mysqli_report(MYSQLI_REPORT_OFF);

$link = mysqli_connect("localhost","admin","admin","",3706);
if (!$link) {
    die("connection error: " . mysqli_connect_error());
}

$db = "first_db";
$query = "CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
$created = mysqli_query($link, $query);

if ($created) {
    echo "database created/exists: $db";
} else {
    echo "error: " . mysqli_error($link);
}
echo '<br><a href="index.php">На головну</a>';
?>
