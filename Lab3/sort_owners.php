<?php
require_once "db.php";

$allowed = ["Owner_ID","Name","Surname"];
$sort = get_sort("sort", $allowed, "Owner_ID");
$order = get_order("ASC");

$sql = "SELECT * FROM owners ORDER BY $sort $order";
$res = mysqli_query($link, $sql);
if (!$res) die("SQL error: " . h(mysqli_error($link)));

function next_order() {
    $o = isset($_GET["order"]) ? strtolower((string)$_GET["order"]) : "asc";
    return ($o === "asc") ? "desc" : "asc";
}
$toggle = next_order();
?>
<!doctype html>
<html lang="uk">
<head><meta charset="utf-8"><title>Сортування owners</title></head>
<body style="font-family:Arial;">
  <h3>Сортування таблиці owners</h3>
  <p><a href="index.php">На головну</a> | <a href="owner.php">Звичайний перегляд</a></p>

  <form method="get" action="">
    <label>Поле:</label>
    <select name="sort">
      <?php foreach ($allowed as $f): ?>
        <option value="<?php echo h($f); ?>" <?php echo ($f===$sort) ? "selected" : ""; ?>>
          <?php echo h($f); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Порядок:</label>
    <select name="order">
      <option value="asc" <?php echo ($order==="ASC") ? "selected" : ""; ?>>ASC</option>
      <option value="desc" <?php echo ($order==="DESC") ? "selected" : ""; ?>>DESC</option>
    </select>

    <input type="submit" value="Показати">
  </form>

  <br>

  <table border="1" style="font-family:Arial;color:green;width:70%">
    <tr>
      <?php foreach ($allowed as $f): ?>
        <th>
          <a href="?sort=<?php echo h($f); ?>&order=<?php echo h($toggle); ?>">
            <?php echo h($f); ?>
          </a>
        </th>
      <?php endforeach; ?>
      <th>Cars</th>
      <th>Edit</th>
    </tr>
    <?php while ($r = mysqli_fetch_assoc($res)): ?>
      <tr>
        <td><?php echo h($r["Owner_ID"]); ?></td>
        <td><?php echo h($r["Name"]); ?></td>
        <td><?php echo h($r["Surname"]); ?></td>
        <td><a href="cars.php?owner=<?php echo (int)$r["Owner_ID"]; ?>">показати</a></td>
        <td><a href="edit_owner.php?id=<?php echo (int)$r["Owner_ID"]; ?>">Edit</a></td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
