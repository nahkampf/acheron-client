<html>
  <head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <script src="../api.js"></script>

    <script type="text/javascript">
    window.addEventListener("load", (event) => {
        var firstRun = true;
        var cache = new Map();

        function getRandomInt(min, max) {
            const minCeiled = Math.ceil(min);
            const maxFloored = Math.floor(max);
            return Math.floor(Math.random() * (maxFloored - minCeiled + 1) + minCeiled); // The maximum is inclusive and the minimum is inclusive
        }

        // first, we clone the (invisible) template
        var template = document.getElementById('template');
        async function getVitals() {
            const data = await get("<?=$api_dsn?>biomonitor");
            if (data) {
                Object.entries(data.contents).forEach((monitor) => {
                    var clone = template.cloneNode(true);
                    clone.style.display="block";
                    clone.id = "monitor_" + monitor[1].surferId;
                    //clone.classList.remove('template');
                    clone.style.backgroundColor = monitor[1].color;
                    //clone.classList.add()
                    clone.getElementsByClassName('name')[0].innerText = monitor[1].person_name;
                    clone.getElementsByClassName('rank')[0].innerText = monitor[1].rank;
                    clone.getElementsByClassName('portrait')[0].src = "/assets/portraits/" + monitor[1].portrait;

                    // randomize values from parameters
                    clone.getElementsByClassName('pulse')[0].innerText = getRandomInt(monitor[1].pulse_low, monitor[1].pulse_high);
                    clone.getElementsByClassName('spo2')[0].innerText = getRandomInt(monitor[1].spo2_low, monitor[1].spo2_high) + "%";
                    clone.getElementsByClassName('bp')[0].innerText = getRandomInt(monitor[1].bp_low, monitor[1].bp_high) + "/m";

//                    document.getElementById("monitor_" + monitor[1].surferId).remove();
                    if (firstRun) {
                        template.after(clone);
                    } else {
                        document.getElementById("monitor_" + monitor[1].surferId).replaceWith(clone);
                    }
                    // compare status to previous and maybe play a sound
                    // but skip if this is our first run
                    if (!firstRun) {
                        var oldstate = cache.get(monitor[1].surferId);
                        var newstate = monitor[1].currentState;
                        if (oldstate != newstate) { // state changed
                            if (newstate == 8) { // deceased
                                console.log("play: DECEASED");
                            }
                            if (newstate == 9) { // dying
                                console.log("play: CRITICAL");
                            }
                            if (newstate == 9) { // dying
                                console.log("play: CRITICAL");
                            }
                        }
                        if (cache.get(monitor[1].surferId) != monitor[1].currentState) {
                            if (monitor[1].currentState != 6 && cache.get(monitor[1].surferId))
                            switch(monitor[1].currentState) {
                                case "8": // DECEASED
                                    console.log("play: DECEASED");
                                    break;
                                    case "9": // DYING
                                    console.log("play: CRITICAL");
                                    break;
                                    case "6": // DISCONNECTED
                                    console.log("play: DISCONNECTED");
                                    break;
                                    case "8": // DECEASED
                                    console.log("play: DECEASED");
                                    break;
                            }
                            console.log("CHANGE OF STATE! For id " + monitor[1].surferId + " state changed from " + cache.get(monitor[1].surferId) + " to " + monitor[1].currentState);
                        }
                    }
                    cache.set(monitor[1].surferId, monitor[1].currentState);
                });
            } else {
                console.error("No data from biomonitor!");
            }
            template.style.display="none";
            firstRun = false;
        }
        getVitals();
        var Timer = setInterval(getVitals, 4000);
    });
    </script>
    <style>
/*
  1. Use a more-intuitive box-sizing model.
*/
*, *::before, *::after {
  box-sizing: border-box;
}
/*
  2. Remove default margin
*/
* {
  margin: 0;
}
/*
  Typographic tweaks!
  3. Add accessible line-height
  4. Improve text rendering
*/
body {
  line-height: 1.5;
  -webkit-font-smoothing: antialiased;
}
/*
  5. Improve media defaults
*/
img, picture, video, canvas, svg {
  display: block;
  max-width: 100%;
}
/*
  6. Remove built-in form typography styles
*/
input, button, textarea, select {
  font: inherit;
}
/*
  7. Avoid text overflows
*/
p, h1, h2, h3, h4, h5, h6 {
  overflow-wrap: break-word;
}
/*
  8. Create a root stacking context
*/
#root, #__next {
  isolation: isolate;
}
    body, table {
        color: white;
    }

      body {
        background-color: black;
        overflow: hidden;
        width:1920px;
        font-family: "Roboto Mono", monospace;
      }
      .card {
        width:960px;
        height:265px;
        color: #ccc;
        box-sizing: border-box;
        border:1px solid black;
        float: left;
        background-color: #123b1d;
      }
      .portrait {
        height:265px;
        border:1px solid black;
      }
      .name {
        font-size: 60px;
        text-transform: uppercase;
        text-align: center;
      }
      .rank {
        font-size: 30px;
        text-transform: uppercase;
        text-align: center;
      }
      table {
        float: left;
        position: relative;
      }
      td {
        text-align: center;
        vertical-align: top;
      }
      .val {
        font-size: 50px;
      }
      .concern {
        background-color: #a79612;
      }
      .critical {
        background-color: #a71212;
      }
      .deceased {
        background-color: #240808;
      }
      .disconnected {
        background-color: #303030;
      }
      .status-deceased {
        color: #ffaa00;
        font-size: 30px;
        animation: blink 1.5s infinite;
        animation-fill-mode: both;
      }
      .status-disconnected {
        color: #d2e0e8;
        font-size: 30px;
        animation: blink 1.5s infinite;
        animation-fill-mode: both;
      }
      .template {
        display: none;
      }

    @keyframes blink {
        0% { opacity: 0 }
        50% { opacity: 1 }
        100% { opacity: 0 }
    }
    .template {
        float:left;
        width:50%;
        display: block;
    }
    </style>
  </head>
<body>
  <div id="template" class="template">
    <table style="width:100%;">
        <tr>
            <td style="width:175px;"><img src="/assets/portraits/nope.gif" class="portrait"></td>
            <td>
                <table style="width:100%;">
                    <tr>
                        <td colspan="3">
                            <p class="rank">Rank</p>
                            <p class="name">Name</p>
                            <p class="status">STATUS</p>
                        </td>
                    </tr>
                    <tr>
                        <td>PULSE RATE</td>
                        <td>SPO<sup>2</sup></td>
                        <td>RESPIRATION RATE</td>
                    </tr>
                    <tr>
                        <td class="val pulse">-</td>
                        <td class="val spo2">-</td>
                        <td class="val bp">-</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
  </div>
</body>
</html>