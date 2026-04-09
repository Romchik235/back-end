<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["Name"];
    $surname = $_POST["Surname"];

    if ($name != "" && $surname != "") {
        mysqli_query($link, "INSERT INTO executors (Name, Surname) VALUES ('$name','$surname')");
    }
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

<h2>Додати виконавця</h2>

<form method="post">
<input type="text" name="Name" placeholder="Ім'я"><br><br>
<input type="text" name="Surname" placeholder="Прізвище"><br><br>

<input type="submit" value="Додати">
</form>

<br>
<a href="executors.php">Назад</a>

</div>

</body>
</html>