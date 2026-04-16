<?php
require_once "db.php";

$id = get_int($_GET, "id");
if ($id === null || $id <= 0) {
    die("Не задано id. Відкрий сторінку як edit_owner.php?id=1");
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim((string)($_POST["Name"] ?? ""));
    $surname = trim((string)($_POST["Surname"] ?? ""));

    if ($name === "" || $surname === "") {
        $msg = "Помилка: Name та Surname не можуть бути порожні.";
    } else {
        $name_esc = mysqli_real_escape_string($link, $name);
        $surname_esc = mysqli_real_escape_string($link, $surname);

        $q = "UPDATE owners SET Name='$name_esc', Surname='$surname_esc' WHERE Owner_ID=$id";
        if (mysqli_query($link, $q)) {
            $msg = "OK. Запис змінено.";
        } else {
            $msg = "SQL error: " . h(mysqli_error($link));
        }
    }
}

$res = mysqli_query($link, "SELECT * FROM owners WHERE Owner_ID=$id");
$row = $res ? mysqli_fetch_assoc($res) : null;
if (!$row) {
    die("Owner не знайдено (id=" . h($id) . ")");
}
?>
<!doctype html>
<html lang="uk">
<head><meta charset="utf-8"><title>Редагувати власника</title></head>
<body style="font-family:Arial;">
  <h3>Редагувати власника Owner_ID=<?php echo h($id); ?></h3>
  <?php if ($msg !== "") { echo "<p style='color:blue'>" . h($msg) . "</p>"; } ?>

  <form method="post" action="">
    <label>Name:</label><br>
    <input type="text" name="Name" size="30" value="<?php echo h($row["Name"]); ?>"><br><br>

    <label>Surname:</label><br>
    <input type="text" name="Surname" size="30" value="<?php echo h($row["Surname"]); ?>"><br><br>

    <input type="submit" value="Змінити">
  </form>

  <p><a href="owner.php?id=<?php echo (int)$id; ?>">Повернутися до власника</a> | <a href="owner.php">Всі власники</a> | <a href="index.php">На головну</a></p>
</body>
</html>
