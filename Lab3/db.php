?php
mysqli_report(MYSQLI_REPORT_OFF);

$DB_HOST = "localhost";
$DB_USER = "admin";
$DB_PASS = "admin";
$DB_NAME = "first_db";
$DB_PORT = 3706;

$link = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, "", $DB_PORT);
if (!$link) {
    die("Connection error: " . mysqli_connect_error());
}

if (!mysqli_select_db($link, $DB_NAME)) {
    die("DB not selected: " . mysqli_error($link));
}

mysqli_set_charset($link, "utf8mb4");

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function get_int($arr, $key) {
    if (!isset($arr[$key])) return null;
    return (int)$arr[$key];
}

function get_sort($key, $allowed, $default) {
    if (!isset($_GET[$key])) return $default;
    $val = (string)$_GET[$key];
    return in_array($val, $allowed, true) ? $val : $default;
}

function get_order($default="ASC") {
    $o = isset($_GET["order"]) ? strtolower((string)$_GET["order"]) : strtolower($default);
    return ($o === "desc") ? "DESC" : "ASC";
}
?>
