<?php
require_once "db.php";

$sort = $_GET["sort"] ?? "date_asc";

if ($sort == "date_desc") {
    $order = "Transfer_Date DESC";
} elseif ($sort == "id_asc") {
    $order = "Document_ID ASC";
} elseif ($sort == "id_desc") {
    $order = "Document_ID DESC";
} else {
    $order = "Transfer_Date ASC";
}

$res = mysqli_query($link, "
SELECT d.*, e.Name, e.Surname
FROM documents d
JOIN executors e ON d.Executor_ID = e.Executor_ID
ORDER BY $order
");
?>

<link rel="stylesheet" href="style.css">

<div class="container">

<h2>Документи</h2>
<a class="btn" href="index.php">На головну</a>

<a class="btn" href="add_document.php">Додати</a>

<br><br>

<b>Сортування:</b><br>
<a href="?sort=date_asc">Дата ↑</a> |
<a href="?sort=date_desc">Дата ↓</a> |
<a href="?sort=id_asc">ID ↑</a> |
<a href="?sort=id_desc">ID ↓</a>

<br><br>

<table>
<tr>
<th>ID</th>
<th>Назва</th>
<th>Передано</th>
<th>Повернено</th>
<th>Виконавець</th>
<th>Дії</th>
</tr>

<?php while($r = mysqli_fetch_assoc($res)): ?>
<tr>
<td><?= $r["Document_ID"] ?></td>
<td><?= $r["Title"] ?></td>
<td><?= $r["Transfer_Date"] ?></td>
<td><?= $r["Return_Date"] ?></td>
<td><?= $r["Name"] ?> <?= $r["Surname"] ?></td>

<td>
<a class="btn" href="edit_document.php?id=<?= $r["Document_ID"] ?>">Редагувати</a>
<a class="btn" href="delete_document.php?id=<?= $r["Document_ID"] ?>">Видалити</a>
</td>

</tr>
<?php endwhile; ?>

</table>

</div>