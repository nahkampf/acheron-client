<?php
// read the config file
$config = @parse_ini_file("config.ini");
if (!$config) {
    die("Unable to load configuration. Please contact an onsite maintenence technician.");
}
$api_dsn = $config["API_BASEURL"] . ":" . $config["API_PORT"]  . "/"  . $config["API_PATH"] . "/";

// if we're in "production mode" don't display errors and only log critical ones


if ($config["MODE"] != "dev") {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
} else {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

switch (@$config["TERMINAL_TYPE"]) {
    case "sensor":
        $window_title = "SENSOR STATION";
        $topbar_title = "SENSOR STATION";
        $page = "sensor";
        break;
    case "sigint":
        $window_title = "SIGNALS INTELLIGENCE";
        $topbar_title = "SIGNALS INTELLIGENCE";
        $page = "sigint";
        break;
    case "geoloc":
        $window_title = "GEOLOCATION";
        $topbar_title = "GEOLOCATION";
        $page = "geoloc";
        break;
    case "science":
        $window_title = "SCIENCE STATION";
        $topbar_title = "SCIENCE STATION";
        $page = "science";
        break;
    case "logs":
        $window_title = "LOG STATION";
        $topbar_title = "LOG STATION";
        $page = "logs";
        break;
    case "commander":
        $window_title = "COMMANDERS STATION";
        $topbar_title = "COMMANDERS STATION";
        $page = "commander";
        break;
        case "monitor":
            $window_title = "BIOMONITOR";
            $topbar_title = "BIOMONITOR";
            $page = "monitor";
            break;
    case "public":
    default:
        $window_title = "PUBLIC TERMINAL";
        $topbar_title = "PUBLIC TERMINAL";
        $page = "public";
        break;
}
?>

<?php require "_head.php"; ?>
<?php require "_topbar.php"; ?>
    <div class="main">
<?php require "pages/{$page}.php"; ?>
    </div>
    <br><br><br><br>
<?php require "_foot.php" ?>