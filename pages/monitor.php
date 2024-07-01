<html>
  <head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <script src="../api.js"></script>

    <script type="text/javascript">
    window.addEventListener("load", (event) => {
        var cachedData = null;
        // first, we clone the (invisible) template
        var template = document.getElementById('template');
        async function getVitals() {
            const data = await get("http://acheron-server.test:81/public/api/biomonitor");
            if (data) {
                Object.entries(data.contents).forEach((monitor) => {
                    var clone = template.cloneNode(true);
                    clone.id = "monitor_" + monitor[1].id;
                    clone.classList.remove('template');
                    //clone.classList.add()
                    clone.getElementsByClassName('name')[0].innerHtml = "hehe";

                    clone.setAttribute('display', 'block');
                    template.after(clone);
                });
                cachedData = data; // update cache
            } else {
                console.error("No data from biomonitor!");
            }
        }

        getVitals();
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
        height:270px;
        color: #ccc;
        box-sizing: border-box;
        border:1px solid black;
        float: left;
        background-color: #123b1d;
      }
      .portrait {
        height:270px;
        border:1px solid white;
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
    </style>
  </head>
<body>
  <div id="template">
    <table style="width:100%;">
        <tr>
            <td style="width:175px;"><img id="monitor_0_portrait" src="portrait.png" class="portrait"></td>
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
                        <td>PULSE</td>
                        <td>SpO<sup>2</sup></td>
                        <td>BP</td>
                    </tr>
                    <tr>
                        <td class="val">118</td>
                        <td class="val">94%</td>
                        <td class="val">112/70</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
  </div>
</body>
</html>