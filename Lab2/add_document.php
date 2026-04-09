<?php
require_once "db.php";

// отримуємо список виконавців
$exec = mysqli_query($link, "SELECT * FROM executors");

// якщо форма відправлена
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST["Title"] ?? "";
    $transfer = $_POST["Transfer_Date"] ?? "";
    $return = $_POST["Return_Date"] ?? "";
    $executor = $_POST["Executor_ID"] ?? "";

    // перевірка
    if ($title != "" && $transfer != "" && $executor != "") {

        mysqli_query($link, "INSERT INTO documents 
        (Title, Transfer_Date, Return_Date, Executor_ID)
        VALUES 
        ('$title','$transfer','$return',$executor)");

        // після додавання повертаємось
        header("Location: documents.php");
        exit;
    } else {
        $error = "Заповни всі обов'язкові поля!";
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Додати документ</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2>Додати документ</h2>

<?php if (!empty($error)): ?>
<p style="color:red"><?= $error ?></p>
<?php endif; ?>

<form method="post">

<input type="text" name="Title" placeholder="Назва документа"><br><br>

<input type="date" name="Transfer_Date"><br><br>

<input type="date" name="Return_Date"><br><br>

<select name="Executor_ID">
<option value="">-- Обрати виконавця --</option>

<?php while($e = mysqli_fetch_assoc($exec)): ?>
<option value="<?= $e["Executor_ID"] ?>">
<?= $e["Name"] ?> <?= $e["Surname"] ?>
</option>
<?php endwhile; ?>

</select><br><br>

<input type="submit" value="Додати">

</form>

<br><br>

<a class="btn" href="documents.php">Назад</a>
<a class="btn" href="index.php">На головну</a>

</div>

</body>
</html>