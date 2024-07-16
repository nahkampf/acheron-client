<?php
if (@$_GET["action"] == "sigint") {
    $signal = json_decode(file_get_contents($api_dsn . "signals/" . (int)$_GET["id"]));
    $emitter = json_decode(file_get_contents($api_dsn . "emitters/" . $signal->emitter))[0];
?>
<script>
        let firstSearch = 1;
        window.addEventListener("load", (event) => {
        const cwsvalue = document.querySelector("#cwsvalue");
        const cwsinput = document.querySelector("#cwsinput");
        cwsvalue.textContent = cwsinput.value;
        cwsinput.addEventListener("input", (event) => {
            cwsvalue.textContent = event.target.value;
            getSigintData();
        });
        const start = document.querySelector("input[name=start]");
        start.addEventListener("change", (event) => {
            getSigintData();
        });
        const mid = document.querySelector("input[name=mid]");
        mid.addEventListener("change", (event) => {
            getSigintData();
        });
        const end = document.querySelector("input[name=end]");
        end.addEventListener("change", (event) => {
            getSigintData();
        });

        async function getSigintData() {
            const formData = new FormData();
            formData.append("cws", document.getElementById('cwsinput').value);
            formData.append("start", document.getElementById('start').checked);
            formData.append("mid", document.getElementById('mid').checked);
            formData.append("end", document.getElementById('end').checked);
            let sigintSearch = new Audio('/assets/sound/sigint_search.wav');
            var res = await post("<?=$api_dsn?>emitters/sigint", formData);
            if (firstSearch == 0) {
                sigintSearch.play();
            }
            console.log(res);
            updateResults(res);
        }

        function updateResults(res) {
            resholder = document.getElementById("ress");
            if (res.contents == "[]") {
                resholder.innerHTML = "";
                resholder.innerHTML = "No search results<br><a href=\"\" class=\"button-red\">DESIGNATE UKNOWN</a>";
                let sigintError = new Audio('/assets/sound/wrong.wav');
                if (firstSearch == 0) {
                    sigintError.play();
                }
                firstSearch = 0;
                return;
            }
            resholder.innerHTML = "";
            Object.entries(res.contents).forEach((xm) => {
                console.log(xm);
                var cw = "";
                var dc = "<tr><td>Dataclusters</td><td>";
                if (xm[1].carrierwave1_frequency) {
                    cw += "<tr><td>Primary carrier wave</td><td><span class=\"cyan\">" + xm[1].carrierwave1_frequency + "</span> Hz</td></tr>";
                }
                if (xm[1].carrierwave2_frequency) {
                    cw += "<tr><td>Secondary carrier wave</td><td><span class=\"cyan\">" + xm[1].carrierwave2_frequency + "</span> Hz</td></tr>";
                }
                if (xm[1].carrierwave3_frequency) {
                    cw += "<tr><td>Tertiary carrier wave</td><td><span class=\"cyan\">" + xm[1].carrierwave3_frequency + "</span> Hz</td></tr>";
                }
                if (xm[1].datacluster_start=="Y") {
                    dc += "[<span class=\"cyan\">START</span>] ";
                }
                if (xm[1].datacluster_middle=="Y") {
                    dc += "[<span class=\"cyan\">MIDDLE</span>] ";
                }
                if (xm[1].datacluster_end=="Y") {
                    dc += "[<span class=\"cyan\">END</span>] ";
                }
                dc += "</td></tr>";
                var spectro = "<tr><td colspan=\"2\"><a href=\"\" class=\"button-yellow\">SPECTROGRAM</a><a href=\"\" class=\"button-green\">SELECT</a></td></tr>";
                var vel = (xm[1].known_max_velocity == null) ? "unknown" : xm[1].known_max_velocity + " m/s";
                var maxvel = "<tr><td>Known max velocity</td><td><span class=\"cyan\">" + vel  +"</span></tr>"
                var content = "<table class=\"sigint_searchresult\"><tr><td colspan=\"2\" class=\"sigint_searchresult_header\">" + xm[1].name + " (" + xm[1].number + ")</td></tr>" + cw + dc + maxvel + spectro + "</table>"
                //resholder.innerHTML = resholder.innerHTML + xm[1].name + " (" + xm[1].number + ")<br>";
                resholder.innerHTML = resholder.innerHTML + content;
            });
        }
        getSigintData();
    });
</script>
<form method="get" id="sigint_form" name="sigint_form">
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
                                    <th colspan="2">IDENTIFY EMITTER TYPE</th>
                                </thead>
                                <tr>
                                    <td># OF CARRIER WAVES</td>
                                    <td><input type="range" min="0" max="3" step="1" id="cwsinput" value="0"></input> <output id="cwsvalue"></output> </td>
                                </tr>
                                <tr>
                                    <td>DATA CLUSTERS</td>
                                    <td>
                                        <label class="form-control">
                                            <input type="checkbox" name="start" id="start"> START<br>
                                        </label>
                                        <label class="form-control">
                                            <input type="checkbox" name="mid" id="mid"> MIDDLE<br>
                                        </label>
                                        <label class="form-control">
                                            <input type="checkbox" name="end" id="end"> END<br>
                                        </label>
                                        </td>
                                </tr>
                                <tr>
                                    <td id="ress" colspan="2"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
</form>

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
                        <td><a class="button-yellow" href="?action=sigint&id=2">ANALYZE</a></td>
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
