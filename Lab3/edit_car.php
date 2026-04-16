<?php
require_once "db.php";

$id = get_int($_GET, "id");
if ($id === null || $id <= 0) {
    die("Не задано id. Відкрий сторінку як edit_car.php?id=1");
}

$msg = "";

$owners_res = mysqli_query($link, "SELECT Owner_ID, Name, Surname FROM owners ORDER BY Owner_ID ASC");
$owners = [];
if ($owners_res) {
    while ($o = mysqli_fetch_assoc($owners_res)) $owners[] = $o;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $car_number = trim((string)($_POST["Car_Number"] ?? ""));
    $brand = trim((string)($_POST["Brand"] ?? ""));
    $status = trim((string)($_POST["Status"] ?? ""));
    $owner_id = (int)($_POST["Owner_ID"] ?? 0);

    if ($car_number === "" || $brand === "" || $status === "" || $owner_id <= 0) {
        $msg = "Помилка: всі поля мають бути заповнені, Owner_ID має бути вибраний.";
    } else {
        $car_number_esc = mysqli_real_escape_string($link, $car_number);
        $brand_esc = mysqli_real_escape_string($link, $brand);
        $status_esc = mysqli_real_escape_string($link, $status);

        $q = "UPDATE cars
              SET Car_Number='$car_number_esc', Brand='$brand_esc', Status='$status_esc', Owner_ID=$owner_id
              WHERE Car_ID=$id";
        if (mysqli_query($link, $q)) {
            $msg = "OK. Запис змінено.";
        } else {
            $msg = "SQL error: " . h(mysqli_error($link));
        }
    }
}

$res = mysqli_query($link, "SELECT * FROM cars WHERE Car_ID=$id");
$row = $res ? mysqli_fetch_assoc($res) : null;
if (!$row) {
    die("Car не знайдено (id=" . h($id) . ")");
}
?>
<!doctype html>
<html lang="uk">
<head><meta charset="utf-8"><title>Редагувати машину</title></head>
<body style="font-family:Arial;">
  <h3>Редагувати машину Car_ID=<?php echo h($id); ?></h3>
  <?php if ($msg !== "") { echo "<p style='color:blue'>" . h($msg) . "</p>"; } ?>

  <form method="post" action="">
    <label>Car_Number:</label><br>
    <input type="text" name="Car_Number" size="30" value="<?php echo h($row["Car_Number"]); ?>"><br><br>

    <label>Brand:</label><br>
    <input type="text" name="Brand" size="30" value="<?php echo h($row["Brand"]); ?>"><br><br>

    <label>Status:</label><br>
    <input type="text" name="Status" size="30" value="<?php echo h($row["Status"]); ?>"><br><br>

    <label>Owner:</label><br>
    <select name="Owner_ID">
      <option value="0">-- вибери власника --</option>
      <?php foreach ($owners as $o): 
        $oid = (int)$o["Owner_ID"];
        $selected = ($oid === (int)$row["Owner_ID"]) ? "selected" : "";
      ?>
        <option value="<?php echo $oid; ?>" <?php echo $selected; ?>>
          <?php echo h($o["Owner_ID"] . " - " . $o["Name"] . " " . $o["Surname"]); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <input type="submit" value="Змінити">
  </form>

  <p><a href="cars.php">Повернутися до таблиці cars</a> | <a href="owner.php?id=<?php echo (int)$row["Owner_ID"]; ?>">До власника</a> | <a href="index.php">На головну</a></p>
</body>
</html>
