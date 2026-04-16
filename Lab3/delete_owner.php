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
            $clean = array_values(array_unique($clean));
            $id_list = implode(",", $clean);

            mysqli_begin_transaction($link);
            $ok = true;

            $deletedCars = 0;
            $deletedOwners = 0;

            $q1 = "DELETE FROM cars WHERE Owner_ID IN ($id_list)";
            if (mysqli_query($link, $q1)) {
                $deletedCars = mysqli_affected_rows($link);
            } else {
                $ok = false;
            }

            $q2 = "DELETE FROM owners WHERE Owner_ID IN ($id_list)";
            if ($ok && mysqli_query($link, $q2)) {
                $deletedOwners = mysqli_affected_rows($link);
            } else {
                $ok = false;
            }

            if ($ok) {
                mysqli_commit($link);
                $msg = "OK. Видалено власників: " . h((string)$deletedOwners) .
                       ", машин: " . h((string)$deletedCars) . ".";
            } else {
                mysqli_rollback($link);
                $msg = "SQL error: " . h(mysqli_error($link));
            }
        }
    }
}

$res = mysqli_query($link, "SELECT * FROM owners ORDER BY Owner_ID ASC");
?>
<!doctype html>
<html lang="uk">
<head>
  <meta charset="utf-8">
  <title>Видалення власників</title>
</head>
<body style="font-family:Arial;">
  <h3>Видалення записів з owners</h3>
  <?php if ($msg !== "") { echo "<p style='color:blue'>" . h($msg) . "</p>"; } ?>

  <form method="post" action="">
    <table border="1" style="font-family:Arial;color:green;width:70%">
      <tr>
        <th>Delete</th>
        <th>Owner_ID</th>
        <th>Name</th>
        <th>Surname</th>
        <th>Cars</th>
      </tr>

      <?php if ($res): while ($r = mysqli_fetch_assoc($res)): ?>
        <tr>
          <td style="text-align:center">
            <input type="checkbox" name="ids[]" value="<?php echo (int)$r["Owner_ID"]; ?>">
          </td>
          <td><?php echo h($r["Owner_ID"]); ?></td>
          <td><?php echo h($r["Name"]); ?></td>
          <td><?php echo h($r["Surname"]); ?></td>
          <td>
            <a href="cars.php?owner=<?php echo (int)$r["Owner_ID"]; ?>">Показати</a>
          </td>
        </tr>
      <?php endwhile; endif; ?>
    </table>

    <br>
    <input type="submit" value="Delete">
  </form>

  <p>
    <a href="owner.php">Повернутися до owners</a> |
    <a href="index.php">На головну</a>
  </p>
</body>
</html>
