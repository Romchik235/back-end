<?php
$link = mysqli_connect("localhost", "root", "", "documents_db");

if (!$link) {
    die("Помилка: " . mysqli_connect_error());
}
?>