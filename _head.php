<!DOCTYPE html>
<html>
    <head>
        <title><?=($window_title) ?: "ACHERON TERMINAL" ?></title>
        <link rel="stylesheet" href="css/style.css">
    </head>
<body>
<script>
window.addEventListener("load", (event) => {
    var alertSwitch = null; // holds our "last state" so that we don't play stuff on repeat
    var firstRun = true; // is this the first time we run? Then don't do the audio
    async function getAlertState() {
        const data = await get("<?=$api_dsn?>alert");
        if (data) {
            handleAlert(data);
            firstRun = false;
        }
    }
    function handleAlert(data) {
        var state = data.contents.current_state;
        const bluedialog = document.querySelector("#codeblue");
        const reddialog = document.querySelector("#codered");
        switch(state) {
            case "red":
                if (!firstRun) {
                    bluedialog.close();
                    let codeRedNotification = new Audio('/assets/sound/code_red.wav');
                    if (alertSwitch != "red") {
                        codeRedNotification.play();
                    }
                    alertSwitch = "red";
                    reddialog.showModal();
                }
                break;
            case "blue":
                if (!firstRun) {
                    reddialog.close();
                    const dialog = document.querySelector("#codeblue");
                    let codeBlueNotification = new Audio('/assets/sound/code_blue.wav');
                    if (alertSwitch != "blue") {
                        codeBlueNotification.play();       
                    }
                    alertSwitch = "blue";
                    bluedialog.showModal();
                }
                break;
            case "green":
            default:
                if (!firstRun) {
                    bluedialog.close();
                    reddialog.close();
                    let codeGreenNotification = new Audio('/assets/sound/code_green.wav');
                    if (alertSwitch != "green") {
                        codeGreenNotification.play();       
                        firstRun = false;
                    }
                    alertSwitch = "green";
                }
                break;
        }
        firstRun = false;
    }

    var alertMonitor = setInterval(() => {
        var currentAlertState = getAlertState();
    }, "2000");
});
</script>
<dialog class="alert" id="codered">
    <p class="fgblink">CODE RED</p>
    <p>ENEMY INCURSION LIKELY IMMINENT</p>
    <p>ASSUME A DEFENSIVE POSTURE IMMEDIATELY</p>
</dialog>
<dialog class="alert" id="codeblue">
    <p class="fgblink">CODE BLUE</p>
    <p>ENEMY DETECTION POSSIBLE</p>
    <p>TURN OFF ALL ELECTRONICS</p>
</dialog>
<div class="screen">
