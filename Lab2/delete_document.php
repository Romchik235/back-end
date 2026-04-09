<?php
require_once "db.php";

if (!isset($_GET["id"])) {
    die("Немає ID");
}

$id = (int)$_GET["id"];

mysqli_query($link, "DELETE FROM documents WHERE Document_ID=$id");

header("Location: documents.php");
exit;
?>