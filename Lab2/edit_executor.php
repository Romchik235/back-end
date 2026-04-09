<?php
require_once "db.php";

$id = $_GET["id"] ?? 0;

$res = mysqli_query($link, "SELECT * FROM executors WHERE Executor_ID=$id");

if (!$res || mysqli_num_rows($res) == 0) {
    die("Запис не знайдено");
}

$row = mysqli_fetch_assoc($res);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["Name"];
    $surname = $_POST["Surname"];

    mysqli_query($link, "UPDATE executors SET Name='$name', Surname='$surname' WHERE Executor_ID=$id");

    header("Location: executors.php");
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

<h2>Редагувати виконавця</h2>

<form method="post">

<input type="text" name="Name" value="<?= $row["Name"] ?>"><br><br>
<input type="text" name="Surname" value="<?= $row["Surname"] ?>"><br><br>

<input type="submit" value="Змінити">

</form>

<br><br>
<a class="btn" href="executors.php">Назад</a>
<a class="btn" href="index.php">На головну</a>

</div>

</body>
</html>