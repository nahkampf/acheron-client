<script src="/api.js"></script>
<script>
window.addEventListener("load", (event) => {
    async function getSignals(firstRun = false) {
        const data = await get("http://acheron-server.test:81/public/api/signals");
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
        // step 1: does this signal already exist in the table?
        oldSignal = document.getElementById("signal_" + signal.id);
        if (oldSignal) {
            document.getElementById("signal_" + signal.id).remove();
        } else {
            if (!firstRun) {
                let newSignalNotification = new Audio('/assets/sound/newsignal.mp3');
                newSignalNotification.play();
                console.log("Hey, new signal! Play a sound!");
            }
        }

        // if it exists, update that column
        // if it doesn't exist, append that column to the top
        var tbody = document.getElementById("intercepts").getElementsByTagName('tbody')[0];
        row = tbody.insertRow();
        row.setAttribute('id', 'signal_' + signal.id);
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
    </tr>
    <tr>
        <td colspan="2">
            &gt; SIGNAL LOG
        <table class="green zebra" id="intercepts">
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
        </tbody>
        </table>
    </td>
    </tr>
</table>
