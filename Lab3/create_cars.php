<?php
require_once "db.php";

$msg = "";

$owners_res = mysqli_query($link, "SELECT Owner_ID, Name, Surname FROM owners ORDER BY Owner_ID ASC");
$owners = [];
if ($owners_res) {
    while ($o = mysqli_fetch_assoc($owners_res)) {
        $owners[] = $o;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $car_number = trim((string)($_POST["Car_Number"] ?? ""));
    $brand = trim((string)($_POST["Brand"] ?? ""));
    $status = trim((string)($_POST["Status"] ?? ""));
    $owner_id = (int)($_POST["Owner_ID"] ?? 0);
    $created_at = trim((string)($_POST["Created_At"] ?? ""));

    if ($car_number === "" || $brand === "" || $status === "" || $owner_id <= 0 || $created_at === "") {
        $msg = "Помилка: усі поля мають бути заповнені.";
    } else {
        $car_number_esc = mysqli_real_escape_string($link, $car_number);
        $brand_esc = mysqli_real_escape_string($link, $brand);
        $status_esc = mysqli_real_escape_string($link, $status);
        $created_at_esc = mysqli_real_escape_string($link, $created_at);

        $q = "INSERT INTO cars (Car_Number, Brand, Status, Owner_ID, Created_At)
              VALUES ('$car_number_esc', '$brand_esc', '$status_esc', $owner_id, '$created_at_esc')";

        if (mysqli_query($link, $q)) {
            $msg = "OK. Додано машину з Car_ID=" . h((string)mysqli_insert_id($link));
        } else {
            $msg = "SQL error: " . h(mysqli_error($link));
        }
    }
}
?>
<!doctype html>
<html lang="uk">
<head>
  <meta charset="utf-8">
  <title>Додати машину</title>
</head>
<body style="font-family:Arial;">
  <h3>Додати нову машину</h3>
  <?php if ($msg !== "") echo "<p style='color:blue'>" . h($msg) . "</p>"; ?>

  <form method="post">
    <label>Car_Number:</label><br>
    <input type="text" name="Car_Number" size="30"><br><br>

    <label>Brand:</label><br>
    <input type="text" name="Brand" size="30"><br><br>

    <label>Status:</label><br>
    <input type="text" name="Status" size="30" placeholder="Found або Stolen"><br><br>

    <label>Owner:</label><br>
    <select name="Owner_ID">
      <option value="0">-- вибери власника --</option>
      <?php foreach ($owners as $o): ?>
        <option value="<?php echo (int)$o["Owner_ID"]; ?>">
          <?php echo h($o["Owner_ID"] . " - " . $o["Name"] . " " . $o["Surname"]); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <label>Created_At:</label><br>
    <input type="date" name="Created_At" value="<?php echo date('Y-m-d'); ?>"><br><br>

    <input type="submit" value="Додати">
  </form>

  <p><a href="cars.php">Повернутися до таблиці cars</a> | <a href="index.php">На головну</a></p>
</body>
</html>
