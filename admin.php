<?php
if ($_POST) {
    file_put_contents("config.ini", $_POST["config"]);
    header("Location: /");
    die();
}
if (@$_GET["action"] == "reset") {
    copy("config.ini.sample", "config.ini");
}
$configData = file_get_contents("config.ini");
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    exec("ipconfig /all", $ip);
} else {
    exec("ifconfig", $ip);
}
?>
<html>
    <head>

    </head>
    <body>
        <h1>ADMIN</h1>
        <a href="/">&raquo; go back</a> | <a href="?action=reset">reset config to default</a><br>
        <h2>Config file</h2>
        <form method="post" action="">
            <textarea name="config" style="font-family: Noto Sans mono, monospace; background-color: black; color: white; width:100%;height:500px;"><?=$configData?></textarea>
            <input type="submit" value=" SAVE ">
        </form>
        <h2>Client info</h2>
        <pre><?php print_r($ip);?></pre>
    </body>
</html>