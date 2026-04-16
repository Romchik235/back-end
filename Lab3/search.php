<?php
require_once "db.php";

$keyword = trim((string)($_GET['keyword'] ?? ''));
$pattern = trim((string)($_GET['pattern'] ?? ''));
$date_from = trim((string)($_GET['date_from'] ?? ''));
$date_to = trim((string)($_GET['date_to'] ?? ''));

$keyword_result = null;
$pattern_result = null;
$date_result = null;

if ($keyword !== '') {
    $keyword_esc = mysqli_real_escape_string($link, $keyword);
    $like = "%$keyword_esc%";

    $sql_keyword = "
        SELECT c.Car_ID, c.Car_Number, c.Brand, c.Status, c.Created, o.Name, o.Surname
        FROM cars c
        JOIN owners o ON c.Owner_ID = o.Owner_ID
        WHERE c.Car_Number LIKE '$like'
           OR c.Brand LIKE '$like'
           OR c.Status LIKE '$like'
           OR o.Name LIKE '$like'
           OR o.Surname LIKE '$like'
        ORDER BY c.Car_ID ASC
    ";
    $keyword_result = mysqli_query($link, $sql_keyword);
}

if ($pattern !== '') {
    $sql_pattern_text = str_replace('*', '%', $pattern);
    $sql_pattern_text = mysqli_real_escape_string($link, $sql_pattern_text);

    $sql_pattern = "
        SELECT c.Car_ID, c.Car_Number, c.Brand, c.Status, c.Created,
               o.Name, o.Surname
        FROM cars c
        JOIN owners o ON c.Owner_ID = o.Owner_ID
        WHERE c.Car_Number LIKE '$sql_pattern_text'
           OR c.Brand LIKE '$sql_pattern_text'
           OR c.Status LIKE '$sql_pattern_text'
           OR o.Name LIKE '$sql_pattern_text'
           OR o.Surname LIKE '$sql_pattern_text'
        ORDER BY c.Car_ID ASC
    ";
    $pattern_result = mysqli_query($link, $sql_pattern);
}

if ($date_from !== '' && $date_to !== '') {
    $date_from_esc = mysqli_real_escape_string($link, $date_from);
    $date_to_esc = mysqli_real_escape_string($link, $date_to);

    $sql_date = "
        SELECT c.Car_ID, c.Car_Number, c.Brand, c.Status, c.Created,
               o.Name, o.Surname
        FROM cars c
        JOIN owners o ON c.Owner_ID = o.Owner_ID
        WHERE c.Created >= '$date_from_esc'
          AND c.Created <= '$date_to_esc'
        ORDER BY c.Created ASC, c.Car_ID ASC
    ";
    $date_result = mysqli_query($link, $sql_date);
}

function render_table($result, $title) {
    if (!$result) return;
    echo "<h3>" . h($title) . "</h3>";
    echo '<table border="1" cellpadding="6" cellspacing="0" style="margin-bottom:20px; width:100%;">';
    echo '<tr>
            <th>Car_ID</th>
            <th>Car_Number</th>
            <th>Brand</th>
            <th>Status</th>
            <th>Created</th>
            <th>Owner</th>
          </tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . h($row['Car_ID']) . "</td>";
        echo "<td>" . h($row['Car_Number']) . "</td>";
        echo "<td>" . h($row['Brand']) . "</td>";
        echo "<td>" . h($row['Status']) . "</td>";
        echo "<td>" . h($row['Created']) . "</td>";
        echo "<td>" . h($row['Name'] . " " . $row['Surname']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
<!doctype html>
<html lang="uk">
<head>
  <meta charset="utf-8">
  <title>Пошук інформації</title>
</head>
<body style="font-family:Arial;">
  <h2>Пошук інформації</h2>

  <form method="get" style="margin-bottom:20px;">
    <fieldset>
      <legend>Пошук за ключовим словом</legend>
      <input type="text" name="keyword" value="<?php echo h($keyword); ?>" placeholder="Наприклад: Toyota">
      <button type="submit">Шукати</button>
    </fieldset>
  </form>

  <form method="get" style="margin-bottom:20px;">
    <fieldset>
      <legend>Пошук за шаблоном</legend>
      <input type="text" name="pattern" value="<?php echo h($pattern); ?>" placeholder="Наприклад: AA* або K*">
      <button type="submit">Шукати</button>
    </fieldset>
  </form>

  <form method="get" style="margin-bottom:20px;">
    <fieldset>
      <legend>Пошук у діапазоні дат</legend>
      <label>Від:</label>
      <input type="date" name="date_from" value="<?php echo h($date_from); ?>">
      <label>До:</label>
      <input type="date" name="date_to" value="<?php echo h($date_to); ?>">
      <button type="submit">Шукати</button>
    </fieldset>
  </form>

  <?php
    render_table($keyword_result, "Результати пошуку за ключовим словом");
    render_table($pattern_result, "Результати пошуку за шаблоном");
    render_table($date_result, "Результати пошуку у діапазоні дат");
  ?>

  <p>
    <a href="stats.php">До статистики</a> |
    <a href="index.php">На головну</a>
  </p>
</body>
</html>
