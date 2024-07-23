<?php
if (@$_GET["action"] == "analyze") {
    $sample = json_decode(file_get_contents($api_dsn . "samples/" . (int)$_GET["id"]))[0];
?>
<a href="/?module=samples" class="button-yellow">&laquo; BACK</a>
<div class="analyzer" id="analyzer" style="display:none;width:640px;">
    <p>ANALYZING SAMPLE</p>
    <span id="counter"></span>
</div>

<script>
    // shade &#9617; block &#9608;
    const dialog = document.getElementById('analyzer');
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
        await sleep(100);
        }
        counter.innerHTML = "PRELIMINARY ANALYSIS COMPLETE:";
        var message = <?=json_encode($sample->data)?>;
        counter.innerHTML += "<br><span class=\"white\"><pre>" + message + "</pre></span>";
        const update = await get("<?=$api_dsn?>samples/<?=(int)$_GET["id"]?>/analyzed");
    }
    analyze();
</script>

<?php
die();
}
if (@$_GET["action"] == "view") {
    $sample = json_decode(file_get_contents($api_dsn . "samples/" . (int)$_GET["id"]))[0];
?>
<a href="/?module=samples" class="button-yellow">&laquo; BACK</a>
<br><br>
<span class="cyan-dark">SAMPLE RECEIVED:</span> <span class="cyan"><?=$sample->timestamp?></span><br>
<pre>
<?=$sample->data?>
</pre>
<?php
    die();
}

$samples = json_decode(file_get_contents($api_dsn . "samples/"));
?>
<a href="/" class="button-yellow">&laquo; BACK</a>
<a href="/?module=samples" class="button-red">&raquo; LOAD SAMPLES</a>
<table>
    <tr>
        <td>
            &gt; SAMPLES
            <table class="red zebra" id="sigintsignals">
                <thead>
                    <tr>
                        <th>TIME</th>
                        <th>DATA</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
<?php
foreach (@$samples as $idx => $sample) {
?>
                    <tr>
                        <td><?=substr($sample->timestamp, 11)?></td>
                        <td><?=($sample->analyzed == "Y") ? "ANALYSIS COMPLETE" : "NOT PROCESSED"?></td>
                        <td><?=($sample->analyzed == "Y") ? "<a href=\"?module=samples&action=view&id={$sample->id}\" class=\"button-white\">VIEW ANALYSIS</a>" : "<a href=\"?module=samples&action=analyze&id={$sample->id}\" class=\"button-red\">ANALYZE</a>"?></td>
                        <td></td>
                    </tr>
<?php
}
?>
                </tbody>
            </table>
        </td>
    </tr>
</table>


