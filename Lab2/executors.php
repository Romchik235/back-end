<?php
require_once "db.php";

$search = $_GET["search"] ?? "";

if ($search != "") {
    $res = mysqli_query($link, "SELECT * FROM executors WHERE Name LIKE '%$search%'");
} else {
    $res = mysqli_query($link, "SELECT * FROM executors");
}

if (isset($_POST["delete_selected"])) {
    if (!empty($_POST["ids"])) {
        foreach ($_POST["ids"] as $id) {

            // спочатку видаляємо документи
            mysqli_query($link, "DELETE FROM documents WHERE Executor_ID=$id");

            // потім виконавця
            mysqli_query($link, "DELETE FROM executors WHERE Executor_ID=$id");
        }
    }
}

if (isset($_POST["delete_all"])) {
    mysqli_query($link, "DELETE FROM documents");
    mysqli_query($link, "DELETE FROM executors");
}
?>

<link rel="stylesheet" href="style.css">

<div class="container">

<h2>Виконавці</h2>

<div class="top-panel">

<a class="btn" href="add_executor.php">Додати</a>
<a class="btn" href="index.php">На головну</a>

<form method="get" style="display:inline;">
<input type="text" name="search" placeholder="Пошук по імені">
<input type="submit" value="Знайти">
</form>

</div>

<form method="post">

<table>
<tr>
<th></th>
<th>ID</th>
<th>Ім'я</th>
<th>Прізвище</th>
<th>Дії</th>
</tr>

<?php while($r = mysqli_fetch_assoc($res)): ?>
<tr>
<td><input type="checkbox" name="ids[]" value="<?= $r["Executor_ID"] ?>"></td>
<td><?= $r["Executor_ID"] ?></td>
<td><?= $r["Name"] ?></td>
<td><?= $r["Surname"] ?></td>
<td>
<a href="edit_executor.php?id=<?= $r["Executor_ID"] ?>">✏️</a>
</td>
</tr>
<?php endwhile; ?>

</table>

<br>

<input type="submit" name="delete_selected" value="Видалити вибраних">
<input type="submit" name="delete_all" value="Видалити всіх">

</form>

</div>