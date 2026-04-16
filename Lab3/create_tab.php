<?php
require_once "db.php";

$q1 = "CREATE TABLE IF NOT EXISTS owners (
  Owner_ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(50) NOT NULL,
  Surname VARCHAR(50) NOT NULL,
  Created_At DATE NOT NULL,
  PRIMARY KEY (Owner_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$q2 = "CREATE TABLE IF NOT EXISTS cars (
  Car_ID INT NOT NULL AUTO_INCREMENT,
  Car_Number VARCHAR(50) NOT NULL,
  Brand VARCHAR(50) NOT NULL,
  Status VARCHAR(50) NOT NULL,
  Owner_ID INT NOT NULL,
  Created_At DATE NOT NULL,
  PRIMARY KEY (Car_ID),
  INDEX (Owner_ID),
  CONSTRAINT fk_cars_owner FOREIGN KEY (Owner_ID)
    REFERENCES owners(Owner_ID)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$ok1 = mysqli_query($link, $q1);
$ok2 = mysqli_query($link, $q2);

echo "<p>owners: " . ($ok1 ? "OK" : "ERROR: " . h(mysqli_error($link))) . "</p>";
echo "<p>cars: " . ($ok2 ? "OK" : "ERROR: " . h(mysqli_error($link))) . "</p>";
echo '<p><a href="index.php">На головну</a></p>';
?>
