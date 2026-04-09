<?php
require_once "db.php";

$id = $_GET["id"] ?? 0;

$res = mysqli_query($link, "SELECT * FROM documents WHERE Document_ID=$id");

if (!$res || mysqli_num_rows($res) == 0) {
    die("Документ не знайдено");
}

$row = mysqli_fetch_assoc($res);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["Title"];
    $transfer = $_POST["Transfer_Date"];
    $return = $_POST["Return_Date"];

    mysqli_query($link, "UPDATE documents SET 
        Title='$title',
        Transfer_Date='$transfer',
        Return_Date='$return'
        WHERE Document_ID=$id");

    header("Location: documents.php");
    exit;
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2>Редагувати документ</h2>

<form method="post">

<input type="text" name="Title" value="<?= $row["Title"] ?>"><br><br>

<input type="date" name="Transfer_Date" value="<?= $row["Transfer_Date"] ?>"><br><br>

<input type="date" name="Return_Date" value="<?= $row["Return_Date"] ?>"><br><br>

<input type="submit" value="Змінити">

</form>

<br><br>
<a class="btn" href="documents.php">Назад</a>
<a class="btn" href="index.php">На головну</a>

</div>

</body>
</html>