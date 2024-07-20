<?php
if($config["TERMINAL_TYPE"] != "monitor") {
?>
    <div class="topbar">
        <div class="topbar-left"><?=($config["MODE"]=="dev") ? "[DEV] " : "";?><?=$topbar_title?></div>    
        <div class="topbar-right" id="currenttime">--</div>    
    </div>
<?php
}
?>