<script src="/api.js"></script>
<?php
switch (@$_GET["action"]) {
    case "edit_signal":
        include("edit_signal.php");
        exit;
    break;
}
?>
<script>
function editSignal(id) {
    alert(id);
}
window.addEventListener("load", (event) => {
    /**
     * SENSORS
     */
    async function getSensors() {
        const data = await get("<?=$api_dsn?>sensors");
        if (data) {
            handleSensors(data);
        }
    }

    function handleSensors(sensorData) {
        // compare our cache to the new data to find changes
        var cachedSensors = JSON.parse(window.localStorage.getItem("cachedSensors"));
        if (cachedSensors == null) {
            cachedSensors = sensorData.contents;
            window.localStorage.setItem("cachedSensors", JSON.stringify(cachedSensors));
        }
        var existsInCache = 0;
        var isUpdated = 0;
        var offlineNotification = 0;
        var onlineNotification = 0;
        Object.entries(sensorData.contents).forEach((sensor) => {
            Object.entries(cachedSensors).forEach((cachedSensor) => {
                if (cachedSensor[1].id == sensor[1].id) {
                    existsInCache++;
                    if (cachedSensor[1].status != sensor[1].status) {
                        if (cachedSensor[1].status == "online") {
                            if (offlineNotification < 1) {
                                offlineNotification = 1;
                                let newSignalNotification = new Audio('/assets/sound/sensor_offline.wav');
                                newSignalNotification.play();
                            }
                        }
                        if (cachedSensor[1].status == "offline") {
                            if (onlineNotification < 1) {
                                onlineNotification = 1;
                                let newSignalNotification = new Audio('/assets/sound/sensor_online.wav');
                                newSignalNotification.play();
                            }
                        }
                        isUpdated++;
                    }
                }
            });
        });
        //console.log(isUpdated);
        if (isUpdated > 0) {
        }
        window.localStorage.setItem("cachedSensors", JSON.stringify(sensorData.contents));
        
        // then wipe the table
        var tbody = document.getElementById("sensors").getElementsByTagName('tbody')[0];
        tbody.innerHTML = "";
        Object.entries(sensorData.contents).forEach((entry) => {
            const [key, value] = entry;
            // entry[1] is our object
            var sensor = entry[1];
            updateSensorTable(sensor);
        });
    }
    // run a first one
    getSensors();

    // then set up an interval
    const updateSensors = setInterval(() => {
        var sensors = getSensors();       
    }, 5000);

    function updateSensorTable(sensor) {
        // if it exists, update that column
        // if it doesn't exist, append that column to the top
        var tbody = document.getElementById("sensors").getElementsByTagName('tbody')[0];
        row = tbody.insertRow(0);
        row.setAttribute('id', 'sensor_' + sensor.id);
        row.setAttribute('data-status', sensor.status);
        const cell_name = row.insertCell();
        cell_name.textContent = sensor.name;

        cell_status = row.insertCell();
        if (sensor.status == "online") {
            cell_status.textContent = "ONLINE";
            cell_status.className = "green";
        } else {
            cell_status.textContent = "OFFLINE";
            cell_status.className = "red fgblink";
        }
    }

    /**
     * SIGNALS
     */
    async function getSignals(firstRun = false) {
        const data = await get("<?=$api_dsn?>signals");
        if (data) {
            handleSignals(data, firstRun);
        }
    }

    getSignals(true); // do the first one

    // then set up an interval
    const updateSignals = setInterval(() => {
        var signals = getSignals();       
    }, 5000);

    function handleSignals(signalData, firstRun = false) {
        Object.entries(signalData.contents).forEach((entry) => {
            const [key, value] = entry;
            // entry[1] is our object
            var signal = entry[1];
            updateTable(signal, firstRun);
        });
    }

    function updateTable(signal, firstRun = false) {
        // TODO: We need some method of removing dead/orphaned signals
        // from the table without wiping it (because that will trigger)
        // "new signal detected"

        // step 1: does this signal already exist in the table?
        oldSignal = document.getElementById("signal_" + signal.id);
        if (oldSignal) {
            document.getElementById("signal_" + signal.id).remove();
        } else {
            if (!firstRun) {
                let newSignalNotification = new Audio('/assets/sound/signal_intercepted.wav');
                newSignalNotification.play();
                console.log("Hey, new signal! Play a sound!");
            }
        }

        // if it exists, update that column
        // if it doesn't exist, append that column to the top
        var tbody = document.getElementById("intercepts").getElementsByTagName('tbody')[0];
        row = tbody.insertRow(0);
        row.setAttribute('id', 'signal_' + signal.id);
        row.setAttribute('onClick', 'location.href=\"javascript:editSignal(' + signal.id + ')\";');
        // is this signal handled?
        if (signal.handled == "N") {
            row.setAttribute('class', 'unhandled');
        } else {
            row.setAttribute('class', 'handled');
        }
        const cell_time = row.insertCell();
        if (signal.interceptTime) {
            cell_time.textContent = signal.interceptTime.substring(11);
        } else {
            cell_time.textContent = "?";
        }

        const cell_primary = row.insertCell();
        var bearing = new Number(signal.primary_sensor.bearings.bearing_from_source);
        cell_primary.textContent = signal.primary_sensor.name + ": " + bearing.toPrecision(3) + "°";

        cell_secondary = row.insertCell();
        var bearing = new Number(signal.secondary_sensor.bearings.bearing_from_source);
        cell_secondary.textContent = signal.secondary_sensor.name + ": " + bearing.toPrecision(3) + "°";

        const cell_velocity = row.insertCell();
        cell_velocity.textContent = signal.velocity + " m/s";

        const cell_heading = row.insertCell();
        heading = signal.heading;
        if (!heading) {
            heading = "---";
        } else {
            heading = heading + "°";
        }
        cell_heading.textContent = heading;

        cell_designation = row.insertCell();
        var desig = signal.designation;
        if (!desig) desig = "---";
        cell_designation.textContent = desig;
    }
});    
</script>
<table>
    <tr>
        <td style="width: 50%">
            &gt; SENSOR NETWORK
            <table class="cyan zebra" id="sensors">
                <thead>
                    <tr>
                        <th>SENSOR</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </td>
        <td style="width: 50%">
        &gt; SURFOPS POSITION TRACKING
        <table class="blue zebra">
                <thead>
                    <tr>
                        <th>SURFOPS POSITION FIX</th>
                        <th>TIME</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>??</td>
                        <td>17:32:12</td>
                    </tr>
                    <tr>
                        <td>??</td>
                        <td>17:29:17</td>
                    </tr>
                    <tr>
                        <td>??</td>
                        <td>17:22:12</td>
                    </tr>
                    <tr>
                        <td>??</td>
                        <td>17:18:35</td>
                    </tr>
                    <tr>
                        <td>??</td>
                        <td>17:13:04</td>
                    </tr>
                    <tr>
                        <td>??</td>
                        <td>17:08:01</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
<table>
    <tr>
        <td colspan="2">
            &gt; SIGNAL LOG
        <table class="green zebra" id="intercepts">
        <thead>
            <tr>
                <th>INTERCEPT TIME</th>
                <th>P. SENSOR</th>
                <th>S. SENSOR</th>
                <th>VELOCITY</th>
                <th>HEADING</th>
                <th>DESIGNATION</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        </table>
    </td>
    </tr>
</table>
