<?php
if (@$_GET["id"]) {
$string = "Æ(∩÷°╬7┬<P▓«¥9¢+╙ôz7Pτj≥█4├ ⌐╘4┴<.Rë¿Ω<ú┤ ▄±╜╗┌╗Ö.=¬ìy╟Siδ6∞1╖'ä¥ö";
?>
<a href="?module=decrypt" class="button-yellow">&laquo; BACK</a>
<table class="yellow">
    <thead>
        <th>
            SIGNAL PAYLOAD
        </th>
        <th>
            DETAILS
        </th>
    </thead>
    <tbody>
        <tr>
            <td>
            <table>
            <?php
                $numchars = mb_strlen($string);
                $rows = ceil($numchars / 8);
                for($row = 0; $row < $rows + 1; $row++) {
                ?>
                <tr>
                <?php
                    for ($col = 0; $col < 8; $col++) {
                    ?>
                    <td><?=mb_substr($string, $col, 1)?></td>
                    <?php
                    }
                ?>
                </tr>
                <?php
                }
            ?>
            </table>
            </td>
            <td>deets</td>
        </tr>
    </tbody>
</table>
<?php
die();
}
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
            <tr>
                <td>20:31:04</td>
                <td>A5</td>
                <td>PUPPETMASTER (XM23)</td>
                <td><span class="red">UNKNOWN</span></td>
                <td><a href="?module=decrypt&id=2" class="button-green">DECIPHER</a></td>
            </tr>
        </tbody>
        </table>
    </td>
    </tr>
</table>
