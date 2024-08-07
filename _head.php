<!DOCTYPE html>
<html>
    <head>
        <title><?=($window_title) ?: "ACHERON TERMINAL" ?></title>
        <link rel="stylesheet" href="css/style.css">
    </head>
<body>
<script src="api.js"></script>
<script src="hotkeys.min.js"></script>
<script>
window.addEventListener("load", (event) => {
    hotkeys('ctrl+del', function (event, handler){
        switch (handler.key) {
            case 'ctrl+del':
                window.location="admin.php";
                event.preventDefault()
            break;
        }
    });
    var alertSwitch = null; // holds our "last state" so that we don't play stuff on repeat
    var firstRun = true; // is this the first time we run? Then don't do the audio
    async function getTime() {
        const data = await get("<?=$api_dsn?>time");
        if (data) {
            handleTime(data);
        }
    }
    async function getAlertState() {
        const data = await get("<?=$api_dsn?>alert");
        if (data) {
            handleAlert(data);
            firstRun = false;
        }
    }

    function handleTime(data) {
        console.log(data.contents[0].ts.substring(11));
        document.getElementById('currenttime').innerHTML = data.contents[0].ts.substring(11);
    }
    function handleAlert(data) {
        var state = data.contents.current_state;
        const bluedialog = document.querySelector("#codeblue");
        const reddialog = document.querySelector("#codered");
        currentAlertLevel = window.localStorage.getItem('currentAlertLevel') ? window.localStorage.getItem('currentAlertLevel') : "";
//        console.log("data state: " + state + ", local storage: " + currentAlertLevel);
        switch(state) {
            case "red":
//                console.log("data state: " + state + ", local storage: " + currentAlertLevel);
                bluedialog.close();
                let codeRedNotification = new Audio('/assets/sound/code_red.wav');
                if (currentAlertLevel != "red") {
                    console.log("RED ALERT!");
                    codeRedNotification.play();
                }
                alertSwitch = "red";
                localStorage.setItem('currentAlertLevel', alertSwitch);
                reddialog.showModal();
                break;
            case "blue":
                reddialog.close();
                const dialog = document.querySelector("#codeblue");
                let codeBlueNotification = new Audio('/assets/sound/code_blue.wav');
                if (currentAlertLevel != "blue") {
                    console.log("CODE BLUE");
                    codeBlueNotification.play();       
                }
                alertSwitch = "blue";
                localStorage.setItem('currentAlertLevel', alertSwitch);
                bluedialog.showModal();
                break;
            case "green":
            default:
                bluedialog.close();
                reddialog.close();
                let codeGreenNotification = new Audio('/assets/sound/code_green.wav');
                if (currentAlertLevel != "green") {
                    console.log("CONDITION GREEN");
                    codeGreenNotification.play();       
                }
                alertSwitch = "green";
                localStorage.setItem('currentAlertLevel', alertSwitch);
                break;
        }
    }

    var alertMonitor = setInterval(() => {
        var currentAlertState = getAlertState();
    }, "2000");

    var timeTes = setInterval(() => {
        var currentTime = getTime();
    }, "5000");
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
