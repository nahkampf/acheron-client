<?php
if (@$_GET["action"] == "save") {
    $options = [ 
        'http' => [ 
            'method'  => 'POST', 
            'header'  => 'Content-type: application/x-www-form-urlencoded', 
            'content' => http_build_query(['message' => $_POST["message"]]), 
        ], 
    ]; 
    $context  = stream_context_create($options); 
    $response = file_get_contents($api_dsn . "science/decrypt/" . (int)$_GET["id"], false, $context); 
    header("Location: /?module=decrypt");
    die();
}
if (@$_GET["id"]) {
    require("CP437.php");
    $signal = json_decode(file_get_contents($api_dsn . "signals/" . (int)$_GET["id"]));
?>
<a href="?module=decrypt" class="button-yellow">&laquo; BACK</a>
<table class="yellow">
    <thead>
        <th width="25%">
            PAYLOAD
        </th>
        <th width="25%">
            NUMERICAL REPRESENTATION
        </th>
        <th>
            DETAILS
        </th>
    </thead>
    <tbody>
    <?php
    if (@$signal->encryptedMessage->cp437_message == null) {
?>
    <tr>
        <td colspan="3">MESSAGE PAYLOAD INCOMPLETE, CORRUPT OR INVALID. NO DECIPHERING POSSIBLE.</td>
<?php
    die();
    }
?>
        <tr>
            <td style="width:150px;font-size:24px;" class="cyan">
            <?php
                $charstring = "";
                $hexstring = "";
                $cpstring = "";
                $row = 0;
                $col = 0;
                if (@$signal->encryptedMessage->cp437_message == null) {
                    die("PAYLOAD INCOMPLETE OR CORRUPT, NO SIGNAL ANALYSIS POSSIBLE");
                }
                foreach(explode(" ", $signal->encryptedMessage->cp437_message) as $idx => $char) {
                    if (Acheron\CP437::toUTF8((int)$char) == 0) continue;
                    $charstring .= "&#" . Acheron\CP437::toUTF8((int)$char) . ";";
                    $cpstring .= " " . str_pad($char, 3, "0", STR_PAD_LEFT);
                    $col++;
                    if ($col>7) {
                        $col = 0;
                        $row++;
                        $charstring .= "<br>";
                        $cpstring .= "<br>";
                    }
                }
                echo $charstring;
            ?>
            </td>
            <td style="width:500px; font-size:24px;">
                <?=$cpstring?>
            </td>
            <td>
                DESIGNATION: <span class="cyan"><?=$signal->designation?></span><br><br>
                <a href="#" class="button-magenta" id="autobutton" style="display:none;">AUTO-RESOLVE</a><br>
                <script>
                    function showAuto() {
                        document.getElementById('autobutton').style.display="inline-block";
                    }
                    window.addEventListener("load", (event) => {
                        setTimeout(() => {
                          showAuto();
                        }, "60000");
                    });

                        // shade &#9617; block &#9608;
                        document.getElementById('autobutton').addEventListener("click", (event) => {
                            const dialog = document.getElementById('autoresolve');
                            dialog.style.display="block";
                            const block = "&#9608;";
                            const shade = "&#9617;";
                            var bar = shade.repeat(40);
                            var progress = 0;
                            function sleep(ms) {
                                return new Promise(resolve => setTimeout(resolve, ms));
                            }
                            async function analyze() {
                                var counter = document.getElementById('counter');
                                counter.innerHTML = bar;
                                for (let x = 0; x < 40; x++) {
                                    bar = block.repeat(x);
                                    bar += shade.repeat((39 -x));
                                    counter.innerHTML = bar;
                                    //                                    document.getElementById('counter').innerHTML = "&#9608;" + document.getElementById('counter').innerHTML;
                                await sleep(2000);
                                }
                                counter.innerHTML = "ANALYSIS COMPLETE, PHRASES DECIPHERED:";
                                var message = "<?=$signal->encryptedMessage->cleartext_message?>";
                                counter.innerHTML += "<br><span class=\"white\">" + message + "</span>";
                            }
                            analyze();
                        });
                    </script>
                <div class="autoresolve" id="autoresolve" style="display:none;">
                    <p>ANALYZING PAYLOAD</p>
                    <span id="counter"></span>
                </div>
                DECIPHERED MESSAGE<br>
                <form action="?id=<?=$_GET["id"]?>&module=decrypt&action=save" method="post">
                <textarea name="message" style="border:1px solid yellow;" autofocus rows=5, cols=50></textarea>
                <br>
                <input type="submit" value=" SAVE " class="button-yellow">
                </form>
            </td>
        </tr>
    </tbody>
</table>
<?php
die();
}
$signals = json_decode(file_get_contents($api_dsn . "signals/"));
?>
<a href="/" class="button-yellow">&laquo; BACK</a>
<table>
    <tr>
        <td colspan="2">
            &gt; SIGNAL LOG
        <table class="green zebra" id="intercepts">
        <thead>
            <tr>
                <th>INTERCEPT TIME</th>
                <th>DESIGNATION</th>
                <th>TYPE</th>
                <th>MESSAGE</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
<?php
foreach ($signals as $signal) {
    if ((int)$signal->designed_type > 0) {
        $emitter = json_decode(file_get_contents($api_dsn . "emitters/" . $signal->designated_type));
        $emitterText = strtoupper($emitter[0]->name) . " [" . $emitter[0]->number . "]";
    } else {
        $emitterText = "--";
    }
?>
            <tr>
                <td><?=substr($signal->interceptTime, 11)?></td>
                <td><?=$signal->designation?></td>
                <td><?=$emitterText?></td>
                <td><?php
                    if ($signal->decipheredMessage) {
                        echo $signal->decipheredMessage;
                    } else {
                        echo "<span class=\"red\">UNKNOWN</span>";
                    }
                ?></td>
                <td><a href="?module=decrypt&id=<?=$signal->id?>" class="button-green">DECIPHER</a></td>
            </tr>
<?php
}
?>
        </tbody>
        </table>
    </td>
    </tr>
</table>
