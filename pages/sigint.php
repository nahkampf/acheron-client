<?php
if (@$_GET["action"] == "select") {
    $data = ['signalId' => @$_GET["id"], 'emitterId' => @$_GET["set"]]; 
    if (@$_GET["auto"]) {
        $data = ['signalId' => $_GET["id"], 'auto' => true];
    }
    $options = [ 
        'http' => [ 
            'method'  => 'POST', 
            'header'  => 'Content-type: application/x-www-form-urlencoded', 
            'content' => http_build_query($data), 
        ], 
    ]; 
    $context  = stream_context_create($options); 
    $response = file_get_contents($api_dsn . "emitters/sigint/classify", false, $context);
    header("Location: /");
    die();
}
if (@$_GET["action"] == "sigint") {
    $signal = json_decode(file_get_contents($api_dsn . "signals/" . (int)$_GET["id"]));
    $emitter = json_decode(file_get_contents($api_dsn . "emitters/" . $signal->emitter))[0];
?>
<script src="/essential_audio.js"></script>
<link rel="stylesheet" href="/css/essential_audio.css"></link>
<script>
    let firstSearch = 1;
    window.addEventListener("load", (event) => {
        var originalSpec = document.getElementById('original_spectrogram');
        window.original = originalSpec.cloneNode(true);
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
                resholder.innerHTML = "No search results<br><a href=\"\" class=\"button-red\">DESIGNATE UKNOWN</a><a href=\"?action=select&id=<?=$_GET["id"]?>&auto=true\" class=\"button-grey\">AUTO</a>";
                let sigintError = new Audio('/assets/sound/wrong.wav');
                if (firstSearch == 0) {
                    sigintError.play();
                }
                firstSearch = 0;
                return;
            }
            resholder.innerHTML = "";
            var unknown = "<br><a href=\"\" class=\"button-red\">DESIGNATE UKNOWN</a><a href=\"?action=select&id=<?=$_GET["id"]?>&auto=true\" class=\"button-grey\">AUTO</a>";
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
                var spectro = "<tr><td colspan=\"2\"><a id=\"compare_" +  xm[1].id + "\" href=\"javascript:compareSpectrogram('" + xm[1].spectrogram_sample + "');\" class=\"button-yellow\">COMPARE</a><a href=\"/?action=select&id=<?=$_GET["id"]?>&set=" + xm[1].id + "\" class=\"button-green\">SELECT</a></td></tr>";
                var vel = (xm[1].known_max_velocity == null) ? "unknown" : xm[1].known_max_velocity + " m/s";
                var maxvel = "<tr><td>Known max velocity</td><td><span class=\"cyan\">" + vel  +"</span></tr>"
                var content = "<table class=\"sigint_searchresult\"><tr><td colspan=\"2\" class=\"sigint_searchresult_header\">" + xm[1].name + " (" + xm[1].number + ")</td></tr>" + cw + dc + maxvel + spectro + "</table>"
                //resholder.innerHTML = resholder.innerHTML + xm[1].name + " (" + xm[1].number + ")<br>";
                resholder.innerHTML = resholder.innerHTML + content;
            });
            resholder.innerHTML = resholder.innerHTML + unknown;
        }

        // do the initial search 
        getSigintData();
    });
    
    function compareSpectrogram(spectroFile) {
        var source = document.getElementById('original_spectrogram');
        source.src = "/assets/spectrograms/" + spectroFile;
        var revert = document.getElementById('revert');
        revert.style.display="inline";
    }
    function revert() {
        var source = document.getElementById('original_spectrogram');
        source.src = window.original.src;
        var revert = document.getElementById('revert');
        revert.style.display = "none";
    }
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
                                    <td id="spectrogram_holder">
                                        <a href="javascript: revert();" class="button-red" id="revert" style="position: absolute; top: 50px; left: 10px; display: none;">REVERT TO ORIGINAL</a>
                                        <img src="../assets/spectrograms/<?=$emitter->spectrogram_sample?>" id="original_spectrogram">
                                    </td>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                    <td>
                                    > AUDIO RECORDING
                                    <br><br>
                                    <div id="player_box" style="padding-left:30px;width:1370px;">
	                                    <div>
		                                    <div class="essential_audio" data-url="/assets/waveforms/<?=$emitter->waveform_file?>"></div>
	                                    </div>
                                    </div>
                                    <br><br>
                                    </td>
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

<dialog id="spectro">
</dialog>

<?php
die();
}

$signals = json_decode(file_get_contents($api_dsn . "signals/"));
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
                        <th>VELOCITY</th>
                        <th>TYPE</th>
                    </tr>
                </thead>
                <tbody>
<?php
foreach($signals as $idx => $signal) {
?>
                    <tr class="<?=($signal->handled == "Y") ? "" : "unhandled"?>">
                        <td><?=substr($signal->interceptTime,  11)?></td>
                        <td><?=$signal->designation?></td>
                        <td><?=$signal->velocity?></td>
                        <td>
<?php
if ($signal->handled == "Y") {
    $emitter = json_decode(file_get_contents($api_dsn . "emitters/" . $signal->designated_type))[0];
    echo mb_strtoupper($emitter->name) . " [" . $emitter->number . "]";
?>
                            <a class="button-grey" href="?action=sigint&id=<?=$signal->id?>">REDESIGNATE</a>
<?php
} else {
?>
                            <a class="button-yellow" href="?action=sigint&id=<?=$signal->id?>">ANALYZE</a>
<?php
}
?>
                        </td>
                    </tr>
<?php
}
?>
                </tbody>
            </table>
        </td>
    </tr>
</table>