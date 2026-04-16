<?php
require_once "db.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim((string)($_POST["Name"] ?? ""));
    $surname = trim((string)($_POST["Surname"] ?? ""));
    $created_at = trim((string)($_POST["Created_At"] ?? ""));

    if ($name === "" || $surname === "" || $created_at === "") {
        $msg = "Помилка: усі поля мають бути заповнені.";
    } else {
        $name_esc = mysqli_real_escape_string($link, $name);
        $surname_esc = mysqli_real_escape_string($link, $surname);
        $created_at_esc = mysqli_real_escape_string($link, $created_at);

        $q = "INSERT INTO owners (Name, Surname, Created_At)
              VALUES ('$name_esc', '$surname_esc', '$created_at_esc')";

        if (mysqli_query($link, $q)) {
            $msg = "OK. Додано власника з Owner_ID=" . h((string)mysqli_insert_id($link));
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
  <title>Додати власника</title>
</head>
<body style="font-family:Arial;">
  <h3>Додати нового власника</h3>
  <?php if ($msg !== "") echo "<p style='color:blue'>" . h($msg) . "</p>"; ?>

  <form method="post">
    <label>Name:</label><br>
    <input type="text" name="Name" size="30"><br><br>

    <label>Surname:</label><br>
    <input type="text" name="Surname" size="30"><br><br>

    <label>Created_At:</label><br>
    <input type="date" name="Created_At" value="<?php echo date('Y-m-d'); ?>"><br><br>

    <input type="submit" value="Додати">
  </form>

  <p><a href="owner.php">Повернутися до owners</a> | <a href="index.php">На головну</a></p>
</body>
</html>
