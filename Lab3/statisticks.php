<?php
require_once "db.php";

$date_array = getdate();
$begin_date = date("Y-m-d", mktime(0, 0, 0, $date_array['mon'], 1, $date_array['year']));
$end_date   = date("Y-m-d", mktime(0, 0, 0, $date_array['mon'] + 1, 0, $date_array['year']));

$q_total_owners = "SELECT COUNT(Owner_ID) AS total_owners FROM owners";
$r_total_owners = mysqli_query($link, $q_total_owners);
$total_owners = mysqli_fetch_assoc($r_total_owners)['total_owners'] ?? 0;

$q_total_cars = "SELECT COUNT(Car_ID) AS total_cars FROM cars";
$r_total_cars = mysqli_query($link, $q_total_cars);
$total_cars = mysqli_fetch_assoc($r_total_cars)['total_cars'] ?? 0;

$q_month_owners = "SELECT COUNT(Owner_ID) AS month_owners
                   FROM owners
                   WHERE Created >= '$begin_date' AND Created<= '$end_date'";
$r_month_owners = mysqli_query($link, $q_month_owners);
$month_owners = mysqli_fetch_assoc($r_month_owners)['month_owners'] ?? 0;

$q_month_cars = "SELECT COUNT(Car_ID) AS month_cars
                 FROM cars
                 WHERE Created >= '$begin_date' AND Created<= '$end_date'";
$r_month_cars = mysqli_query($link, $q_month_cars);
$month_cars = mysqli_fetch_assoc($r_month_cars)['month_cars'] ?? 0;

$q_last_owner = "SELECT * FROM owners ORDER BY Owner_ID DESC LIMIT 1";
$r_last_owner = mysqli_query($link, $q_last_owner);
$last_owner = mysqli_fetch_assoc($r_last_owner);

$q_top_owner = "
    SELECT o.Owner_ID, o.Name, o.Surname, COUNT(c.Car_ID) AS car_count, o.Created
    FROM owners o
    LEFT JOIN cars c ON o.Owner_ID = c.Owner_ID
    GROUP BY o.Owner_ID, o.Name, o.Surname
    ORDER BY car_count DESC, o.Owner_ID ASC
    LIMIT 1
";
$r_top_owner = mysqli_query($link, $q_top_owner);
$top_owner = mysqli_fetch_assoc($r_top_owner);
?>
<!doctype html>
<html lang="uk">
<head>
  <meta charset="utf-8">
  <title>Статистика сайту</title>
</head>
<body style="font-family:Arial;">
  <h2>Сторінка статистики</h2>

  <p><b>Всього записів у першій таблиці (owners):</b> <?php echo h((string)$total_owners); ?></p>
  <p><b>Всього записів у другій таблиці (cars):</b> <?php echo h((string)$total_cars); ?></p>

  <p><b>Записів за останній місяць у owners:</b> <?php echo h((string)$month_owners); ?></p>
  <p><b>Записів за останній місяць у cars:</b> <?php echo h((string)$month_cars); ?></p>

  <h3>Останній запис у першій таблиці (owners)</h3>
  <?php if ($last_owner): ?>
    <p>
      Owner_ID: <?php echo h($last_owner['Owner_ID']); ?> |
      Name: <?php echo h($last_owner['Name']); ?> |
      Surname: <?php echo h($last_owner['Surname']); ?> |
      Created: <?php echo h($last_owner['Created']); ?>
    </p>
  <?php else: ?>
    <p>Таблиця owners порожня.</p>
  <?php endif; ?>

  <h3>Запис у першій таблиці, що має найбільше пов’язаних записів у другій</h3>
  <?php if ($top_owner): ?>
    <p>
      Owner_ID: <?php echo h($top_owner['Owner_ID']); ?> |
      Name: <?php echo h($top_owner['Name']); ?> |
      Surname: <?php echo h($top_owner['Surname']); ?> |
      Created: <?php echo h($top_owner['Created']); ?> |
      Кількість машин: <?php echo h((string)$top_owner['car_count']); ?>
    </p>
  <?php else: ?>
    <p>Немає даних.</p>
  <?php endif; ?>

  <hr>
  <p>
    <a href="search.php">Пошук інформації</a> |
    <a href="index.php">На головну</a>
  </p>
</body>
</html>
