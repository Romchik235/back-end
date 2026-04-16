<?php
require_once "db.php";

$owner_id = get_int($_GET, "id");
if ($owner_id === null) { $owner_id = get_int($_GET, "cars"); }

if ($owner_id !== null && $owner_id > 0) {
    $sql = "SELECT * FROM owners WHERE Owner_ID=" . $owner_id;
} else {
    $sql = "SELECT * FROM owners ORDER BY Owner_ID ASC";
}

$result = mysqli_query($link, $sql);
if (!$result) {
    die("SQL error: " . h(mysqli_error($link)));
}

echo '<p><a href="index.php">На головну</a> | <a href="create_owner.php">Додати власника</a> | <a href="sort_owners.php">Сортування</a></p>';

if ($owner_id !== null && $owner_id > 0) {
    echo "<h3>Власник Owner_ID=" . h($owner_id) . "</h3>";
} else {
    echo "<h3>Таблиця owners (всі записи)</h3>";
}

echo '<table border="1" style="font-family:Arial;color:green;width:75%">';
echo "<tr>
        <th>Owner_ID</th>
        <th>Name</th>
        <th>Surname</th>
        <th>Created_At</th>
        <th>Cars</th>
        <th>Edit</th>
      </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    $id = (int)$row["Owner_ID"];
    echo "<tr>";
    echo "<td>" . h($id) . "</td>";
    echo "<td>" . h($row["Name"]) . "</td>";
    echo "<td>" . h($row["Surname"]) . "</td>";
    echo "<td>" . h($row["Created_At"] ?? '') . "</td>";
    echo '<td><a href="cars.php?owner=' . $id . '">Показати машини</a></td>';
    echo '<td><a href="edit_owner.php?id=' . $id . '">Edit</a></td>';
    echo "</tr>";
}
echo "</table>";

echo '<p><a href="delete_owner.php">Видалення власників</a></p>';
?>
