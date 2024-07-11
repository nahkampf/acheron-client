<?php
if ($_GET["action"] == "sigint") {
    $signal = json_decode(file_get_contents($api_dsn . "signals/" . (int)$_GET["id"]));
    $emitter = json_decode(file_get_contents($api_dsn . "emitters/" . $signal->emitter))[0];
?>
<script>
    window.addEventListener("load", (event) => {
        const cw1value = document.querySelector("#cw1value");
        const cw1input = document.querySelector("#cw1");
        value.textContent = input.value;
        input.addEventListener("input", (event) => {
            value.textContent = event.target.value;
        });
    });
</script>
<table>
    <tr>
        <td>
            &gt; SIGNAL ANALYSIS
            <table class="green" id="sigintsignals">
                <tbody>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td><img src="../assets/spectrograms/<?=$emitter->spectrogram_sample?>"></td>
                                </tr>
                            </table>
                        </td>
                        <td style="min-width:25%;">
                            <table>
                                <tr>
                                    <td class="green-dark" style="min-width:12%; vertical-align: top;">
                                    INTERCEPT TIME
                                    </td>
                                    <td style="min-width:12%; vertical-align: top;">
                                    <?=substr($signal->interceptTime, 11, 8)?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="green-dark" style="min-width:12%; vertical-align: top;">
                                        DESIGNATION
                                    </td>
                                    <td style="min-width:12%; vertical-align: top;">
                                        <?=$signal->designation?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="green-dark" style="min-width:12%; vertical-align: top;">
                                        VELOCITY
                                    </td>
                                    <td style="min-width:12%; vertical-align: top;">
                                        <?=$signal->velocity?> m/s
                                    </td>
                                </tr>
                                <thead>
                                    <th colspan="2">SIGNAL DATA</th>
                                </thead>
                                <thead>
                                    <th colspan="2">RECOGNITION MANUAL</th>
                                </thead>
                                <tr>
                                    <td>CARRIER WAVE 1</td>
                                    <td><input type="range" id="cw1" min="20" max="7000" step="10"></input> <output id="cw1value"></output> Hz</td>
                                </tr>
                                <tr>
                                    <td>CARRIER WAVE 2</td>
                                    <td><input type="range" id="cw2" min="20" max="7000" step="10"></input> <output id="cw2value"></output> Hz</td>
                                </tr>
                                <tr>
                                    <td>CARRIER WAVE 3</td>
                                    <td><input type="range" id="cw3" min="20" max="7000" step="10"></input> <output id="cw3value"></output> Hz</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>

<?php
die();
}
?>
<table>
    <tr>
        <td>
            &gt; INTERCEPTED SIGNALS
            <table class="green zebra" id="sigintsignals">
                <thead>
                    <tr>
                        <th>TIME</th>
                        <th>DESIGNATION</th>
                        <th>TYPE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="unhandled">
                        <td>23:05:12</td>
                        <td>A4</td>
                        <td><a class="button-yellow" href="?action=sigint&id=1">ANALYZE</a></td>
                    </tr>
                    <tr>
                        <td>23:02:12</td>
                        <td>A3</td>
                        <td>XM23/PUPPETEER</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
