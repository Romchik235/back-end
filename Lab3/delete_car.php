<?php
require_once "db.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ids = $_POST["ids"] ?? [];
    if (!is_array($ids) || count($ids) === 0) {
        $msg = "Не вибрано жодного запису для видалення.";
    } else {
        $clean = [];
        foreach ($ids as $v) {
            $iv = (int)$v;
            if ($iv > 0) $clean[] = $iv;
        }

        if (count($clean) === 0) {
            $msg = "Не вибрано жодного коректного id.";
        } else {
            $id_list = implode(",", $clean);
            $q = "DELETE FROM cars WHERE Car_ID IN ($id_list)";
            if (mysqli_query($link, $q)) {
                $msg = "OK. Видалено: " . h((string)mysqli_affected_rows($link)) . " запис(ів).";
            } else {
                $msg = "SQL error: " . h(mysqli_error($link));
            }
        }
    }
}

$res = mysqli_query($link, "SELECT * FROM cars ORDER BY Car_ID ASC");
?>
<!doctype html>
<html lang="uk">
<head><meta charset="utf-8"><title>Видалення машин</title></head>
<body style="font-family:Arial;">
  <h3>Видалення записів з cars</h3>
  <?php if ($msg !== "") { echo "<p style='color:blue'>" . h($msg) . "</p>"; } ?>

  <form method="post" action="">
    <table border="1" style="font-family:Arial;color:green;width:80%">
      <tr>
        <th>Delete</th>
        <th>Car_ID</th>
        <th>Car_Number</th>
        <th>Brand</th>
        <th>Status</th>
        <th>Owner_ID</th>
      </tr>
      <?php if ($res): while ($r = mysqli_fetch_assoc($res)): ?>
        <tr>
          <td style="text-align:center"><input type="checkbox" name="ids[]" value="<?php echo (int)$r["Car_ID"]; ?>"></td>
          <td><?php echo h($r["Car_ID"]); ?></td>
          <td><?php echo h($r["Car_Number"]); ?></td>
          <td><?php echo h($r["Brand"]); ?></td>
          <td><?php echo h($r["Status"]); ?></td>
          <td><a href="owner.php?id=<?php echo (int)$r["Owner_ID"]; ?>"><?php echo h($r["Owner_ID"]); ?></a></td>
        </tr>
      <?php endwhile; endif; ?>
    </table>
    <br>
    <input type="submit" value="Delete">
  </form>

  <p><a href="cars.php">Повернутися до cars</a> | <a href="index.php">На головну</a></p>
</body>
</html>
