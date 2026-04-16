<?php
require_once "db.php";

$allowed = ["Car_ID","Car_Number","Brand","Status","Owner_ID"];
$sort = get_sort("sort", $allowed, "Car_ID");
$order = get_order("ASC");

$owner_filter = get_int($_GET, "owner");
if ($owner_filter === null) { $owner_filter = get_int($_GET, "owner_id"); }
$where = "";
if ($owner_filter !== null && $owner_filter > 0) {
    $where = " WHERE Owner_ID=" . $owner_filter;
}

$sql = "SELECT * FROM cars" . $where . " ORDER BY $sort $order";
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
<head><meta charset="utf-8"><title>Сортування cars</title></head>
<body style="font-family:Arial;">
  <h3>Сортування таблиці cars</h3>
  <p><a href="index.php">На головну</a> | <a href="cars.php">Звичайний перегляд</a></p>

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

    <label>Owner_ID (необов'язково):</label>
    <input type="number" name="owner" value="<?php echo ($owner_filter!==null)?h($owner_filter):""; ?>" style="width:90px">

    <input type="submit" value="Показати">
  </form>

  <br>

  <table border="1" style="font-family:Arial;color:green;width:80%">
    <tr>
      <?php foreach ($allowed as $f): ?>
        <th>
          <a href="?sort=<?php echo h($f); ?>&order=<?php echo h($toggle); ?><?php echo ($owner_filter!==null && $owner_filter>0) ? "&owner=" . h($owner_filter) : ""; ?>">
            <?php echo h($f); ?>
          </a>
        </th>
      <?php endforeach; ?>
      <th>Owner</th>
    </tr>
    <?php while ($r = mysqli_fetch_assoc($res)): ?>
      <tr>
        <td><?php echo h($r["Car_ID"]); ?></td>
        <td><?php echo h($r["Car_Number"]); ?></td>
        <td><?php echo h($r["Brand"]); ?></td>
        <td><?php echo h($r["Status"]); ?></td>
        <td><?php echo h($r["Owner_ID"]); ?></td>
        <td><a href="owner.php?id=<?php echo (int)$r["Owner_ID"]; ?>">перехід</a></td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
