<script src="/api.js"></script>
<?php
if (@$_POST) {
    $options = [ 
        'http' => [ 
            'method'  => 'POST', 
            'header'  => 'Content-type: application/x-www-form-urlencoded', 
            'content' => http_build_query(['intercepting_operator' => $_POST["operator"]]), 
        ], 
    ]; 
    $context  = stream_context_create($options); 
    $response = file_get_contents($api_dsn . "signals/" . (int)$_GET["id"], false, $context); 
}

if (@$_GET["subaction"] == "designate") {
    $next_number = json_decode(file_get_contents($api_dsn . "signals/nextdesignation"))[0];
    $options = [ 
        'http' => [ 
            'method'  => 'POST', 
            'header'  => 'Content-type: application/x-www-form-urlencoded', 
            'content' => http_build_query(['designation' => $next_number, 'handled' => 'Y']), 
        ], 
    ]; 
    $context  = stream_context_create($options); 
    $response = file_get_contents($api_dsn . "signals/" . (int)$_GET["id"], false, $context); 
}
$signalData = json_decode(file_get_contents($api_dsn . "signals/" . $_GET["id"]));
?>
<br>
<form method="POST" action="?action=edit_signal&subaction=save&id=<?=$_GET["id"]?>" id="signalform">
<table style="max-width: 800px !important; margin: 0 auto;">
    <tr>
        <td>
            <table class="yellow zebra">
                <thead>
                    <tr>
                        <th>SIGNAL HANDLING</th>
                    </tr>
                </thead>
                <tbody>
                    <table class="yellow">
                        <tr>
                            <td class="yellow-dark">INTERCEPT TIME</td>
                            <td><?=substr($signalData->interceptTime, 11,8);?></td>
                            <td class="yellow-dark">OPERATOR</td>
                            <td>
<?php
if ($signalData->interceptingOperator) {
?>
                            <?=strtoupper($signalData->interceptingOperator)?>
<?php
} else {
?>
                            <input type="text" name="operator" autofocus required style="text-transform: uppercase;">
<?php
}
?>
                            </td>
                        </tr>
                        <tr>
                            <td class="yellow-dark">PRIMARY SENSOR</td>
                            <td><?=$signalData->primary_sensor->name?></td>
                            <td class="yellow-dark">BEARING</td>
                            <td><?=floor($signalData->primary_sensor->bearings->bearing_from_target)?>&deg;</td>
                        </tr>
                        <tr>
                            <td class="yellow-dark">SECONDARY SENSOR</td>
                            <td><?=$signalData->secondary_sensor->name?></td>
                            <td class="yellow-dark">BEARING</td>
                            <td><?=floor($signalData->secondary_sensor->bearings->bearing_from_target)?>&deg;</td>
                        </tr>
                        <tr>
                            <td class="yellow-dark">VELOCITY</td>
                            <td><?=(int)$signalData->velocity?> m/s</td>
                            <td class="yellow-dark">HEADING</td>
                            <td><?=$signalData->heading?>&deg;</td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <a href="/" class="button-yellow"> &laquo; RETURN</a>
                            </td>
                            <td>
<?php
if ($signalData->designation) {
?>
                            <span class="yellow-dark">DESIGNATION</span> <span class="red"><?=$signalData->designation?></span>
<?php
} else {
?>
                            <a href="?action=edit_signal&subaction=designate&id=<?=$_GET["id"]?>" class="button-cyan">ADD DESIGNATION</a>
<?php
}
?>
                            </td>
                            <td>
<?php
if (@strlen($signalData->designation) > 0) {
?>
                            <a href="javascript:document.getElementById('signalform').submit();" class="button-green">SAVE</a>
<?php
} else {
}
?>
                            </td>
                        </tr>
                    </table>
                </tbody>
            </table>
        </td>
    </tr>
</table>
</form>