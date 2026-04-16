<?php
require_once "db.php";

$owner_filter = get_int($_GET, "owner");
if ($owner_filter === null) { $owner_filter = get_int($_GET, "owner_id"); }

$where = "";
if ($owner_filter !== null && $owner_filter > 0) {
    $where = " WHERE Owner_ID=" . $owner_filter;
}

$sql = "SELECT * FROM cars" . $where . " ORDER BY Car_ID ASC";
$result = mysqli_query($link, $sql);
if (!$result) {
    die("SQL error: " . h(mysqli_error($link)));
}

echo '<p><a href="index.php">На головну</a> | <a href="create_car.php">Додати машину</a> | <a href="sort_cars.php">Сортування</a></p>';

if ($owner_filter !== null && $owner_filter > 0) {
    echo "<h3>Машини власника Owner_ID=" . h($owner_filter) . "</h3>";
} else {
    echo "<h3>Таблиця cars (всі записи)</h3>";
}

echo '<table border="1" style="font-family:Arial;color:green;width:85%">';
echo "<tr>
        <th>Car_ID</th>
        <th>Car_Number</th>
        <th>Brand</th>
        <th>Status</th>
        <th>Created_At</th>
        <th>Owner_ID (перехід до власника)</th>
        <th>Edit</th>
      </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    $car_id = (int)$row["Car_ID"];
    $owner_id = (int)$row["Owner_ID"];

    echo "<tr>";
    echo "<td>" . h($car_id) . "</td>";
    echo "<td>" . h($row["Car_Number"]) . "</td>";
    echo "<td>" . h($row["Brand"]) . "</td>";
    echo "<td>" . h($row["Status"]) . "</td>";
    echo "<td>" . h($row["Created_At"] ?? '') . "</td>";
    echo '<td><a href="owner.php?id=' . $owner_id . '">' . h($owner_id) . "</a></td>";
    echo '<td><a href="edit_car.php?id=' . $car_id . '">Edit</a></td>';
    echo "</tr>";
}
echo "</table>";

echo '<p><a href="delete_car.php">Видалення машин</a></p>';
?>
