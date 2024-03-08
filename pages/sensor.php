<table>
    <tr>
        <td style="width: 33%">
            &gt; SENSOR NETWORK
            <table class="cyan zebra">
                <thead>
                    <tr>
                        <th>SENSOR</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>EPSILON</td>
                        <td>ONLINE</td>
                    </tr>
                    <tr>
                        <td>THETA</td>
                        <td>ONLINE</td>
                    </tr>
                    <tr>
                        <td>KAPPA</td>
                        <td>ONLINE</td>
                    </tr>
                    <tr>
                        <td>LAMBDA</td>
                        <td><span class="red fgblink">OFFLINE</span></td>
                    </tr>
                    <tr>
                        <td>OMICRON</td>
                        <td>ONLINE</td>
                    </tr>
                    <tr>
                        <td>SIGMA</td>
                        <td>ONLINE</td>
                    </tr>
                </tbody>
            </table>
        </td>
        <td>
            &gt; UNHANDLED INTERCEPTS
            <table class="red">
                <thead>
                    <tr>
                        <th>INTERCEPT TIME</th>
                        <th>PRIMARY</th>
                        <th>SECONDARY</th>
                        <th>VELOCITY</th>
                        <th>HEADING</th>
                        <th>DESIGNATION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>23:42:12</td>
                        <td>SIGMA [301&deg;]</td>
                        <td>THETA [052&deg;]</td>
                        <td>2m/s</td>
                        <td>142&deg;</td>
                        <td>---</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            &gt; SIGNALS HANDLED
        <table class="green zebra">
        <thead>
            <tr>
                <th>INTERCEPT TIME</th>
                <th>PRIMARY</th>
                <th>SECONDARY</th>
                <th>VELOCITY</th>
                <th>HEADING</th>
                <th>DESIGNATION</th>
            </tr>
        </thead>
        <tbody>
<?php
for($x=0; $x<15; $x++) {
  $sensors = ["SIGMA", "THETA", "KAPPA", "LAMBDA", "OMICRON", "SIGMA"];
  rnd:
  $sensor1 = $sensors[mt_rand(0, count($sensors) -1)];
  $sensor2 = $sensors[mt_rand(0, count($sensors) -1)];
  $desigs = ["XM1 HVY TRANSPORT", "XM2 LT TRANSPORT", "XM3 LOGISTICS NODE", "XM4 FUELER", "XM5 RECHARGE DRONE", "XM6 SOIL SAMPLER", "XM7 DRILL RIG", "XM8 SPORE BARGE", "XM9 LIFTER", "XM10 LIGHT LIFTER", "XM11 MANIPULATOR", "XM12 LT REPAIR UNIT", "XM13 HVY REPAIR UNIT", "XM14 CONDUIT LAYER", "XM15 EARTHMOVER", "XM16 UTILITY BARGE", "XM17 LT ORBITAL TUG", "XM18 HVY ORBITAL TUG", "XM19 SCRAP EXTRACTOR", "XM20 MOBILE SMELTER", "XM21 WATER CRACKER", "XM22 BIOHARVESTER", "XM23 PUPPETMASTER", "XM24 RHINO", "XM25 HAMMERHEAD", "XM26 TARANTULA", "XM27 VAMPIRE", "XM28 NIGHTOWL", "XM29 CRAB", "XM30 MOSQUITO", "XM31 SWARM", "XM32 SCYTHE"];
  if ($sensor1 == $sensor2) goto rnd;
  $vel = mt_rand(0,5);
  if ($vel == 0) {
    $vel = "STATIONARY";
  } else {
    $vel = $vel . "." . mt_rand(0,9) . " m/s";
  }
  $desig = $desigs[mt_rand(0, count($desigs) -1)];
?>
            <tr>
                <td>23:42:12</td>
                <td><?=$sensor1?> [<?=mt_rand(0, 359)?>&deg;]</td>
                <td><?=$sensor2?> [<?=mt_rand(0, 359)?>&deg;]</td>
                <td><?=$vel?></td>
                <td><?=($vel=="STATIONARY") ? "---" : mt_rand(0, 350) . "&deg;";?></td>
                <td><?=$desig?></td>
            </tr>
<?php
}
?>
        </tbody>
        </table>
    </td>
    </tr>
</table>
