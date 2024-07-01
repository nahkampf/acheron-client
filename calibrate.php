<html><head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Frequency Scope</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script type="text/javascript" src="HashRandomizer.js"></script>
  <script type="text/javascript" src="Tone.js"></script>

  <link rel="stylesheet" href="css/style.css"></link>
<style id="compiled-css" type="text/css">
:root {
  --bg: #181918;
  --fg: #FFFF55;
}

* {
  box-sizing: border-box;
}

*::selection {
  background: var(--fg);
  color: var(--bg);
}

html,
body {
  background: var(--bg);
  color: var(--fg);
  font-family: "Noto Sans Mono", monospace;
  font-weight: 400;
  font-style: normal;
  margin: 0;
  padding: 0;
  font-size: 32px;
  scrollbar-color: currentColor rgba(var(--text), 0.8);
  scrollbar-width: thin;
}

body {
  height: 100vh;
  width: 100vw;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

button,
input,
textarea,
pre {
  background: var(--bg);
  color: var(--fg);
  font-family: "Noto Sans Mono", monospace;
  text-transform: uppercase;
}

/** POST FX */

body::after {
  content: '';
  height: 100vh;
  width: 100vw;
  position: fixed;
  top: 0;
  left: 0;
  pointer-events: none;
  /*background-image: linear-gradient(to bottom, transparent 0%, transparent 99%, var(--fg) 100%), linear-gradient(to right, transparent 0%, transparent 99%, var(--fg) 100%);*/
  background-size: 2em 2em;
}

html::after {
  content: '';
  height: 100vh;
  width: 100vw;
  position: fixed;
  top: 0;
  left: 0;
  pointer-events: none;
/*  background-image: linear-gradient(to bottom, #000, var(--fg)), linear-gradient(to right, #000, var(--fg));*/
  background-size: 100% 3px, 3px 100%;
/*
  box-shadow: inset 0 0 20vmax #000;
  mix-blend-mode: color-dodge;
  animation: 427s linear 0s infinite roll;
*/
}

@keyframes roll {
  0% {
    background-position: 0 0%;
    background-color: transparent;
  }
  
  100% {
    background-position: 0 100%;
    background-color: transparent;
  }
}


@keyframes alert {
  0% {
    color: var(--fg);
    background: transparent;
  }
  50% {
    color: var(--bg);
    background: #d00;
  }
}

/** 
PAGES
*/

section,
canvas {
  height: 100vh;
  width: 100vw;
}

ul {
  border-top: 0.1em currentColor solid;
  padding: 0;
  margin: 0;
}

li {
  list-style: none;
  margin: 0.2em;
}

.selected {
  background: var(--fg);
  color: var(--bg);
  font-weight: bolder;
}

.warning {
  animation: alert 1s steps(1) infinite;
}

.view {
  display: none;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.view.active {
  display: flex;
}

.init button {
  font-size: 15vmin;
  padding: 0.5em;
  border: 0.1em solid currentColor;
}

.init button:active {
  color: var(--bg);
  background: var(--fg);
  box-shadow: 0 0 1em var(--fg);
}

#sample,
#scope {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

#controls {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  align-content: space-between;
  justify-content: space-between;
  border: 0.25rem solid transparent;
}

#controls > div {
  display: flex;
  justify-content: space-between;
  flex-direction: row;
  align-content: center;
  align-items: flex-start;
  width: 100%;
}

#controls > div.systems:last-of-type {
  align-items: center;
}

.system {
  cursor: pointer;
}

#controls button,
#controls input,
#controls select,
.system h3 {
  font-size: 42px;
  padding: 0.25em;
  border: 0.1em solid currentColor;
  text-transform: uppercase;
}

#controls .active,
#controls button:active {
  color: var(--bg);
  background: var(--fg);
  box-shadow: 0 0 0.25em var(--fg);
}

.system.selected {
  color: var(--fg);
  background: transparent;
}

.system.selected > h3 {
  background: var(--fg);
  color: var(--bg);
}

.system:not(.selected) ul {
  display: none;
}

.system.selcted ul {
  display: unset;
}

.match {
  font-size: 10vh;
}
.match::after {
  content: '%';
  margin-right: 0.25em;
}
.match.selcted {
  color: var(--fg) !important;
  background: transparent !important;
}

.inactive {
  color: #600;
}
    /* EOS */
  </style>

  <script id="insert"></script>

</head>
<body>
<section class="view init active">
  <button>
    activate
  </button>
</section>

<section class="view analyzer">
  <div id="sample"></div>
  <div id="scope"></div>

  <div id="controls" class="parent">
    <div class="systems">
      <div class="system type">
        <h3>Waveform</h3>
        <ul>
          <li>sine</li>
          <li>square</li>
          <li>triangle</li>
          <li>sawtooth</li>
        </ul>
      </div>
      <div class="system frequency">
        <h3>frq</h3>
        <ul>
          <li>10</li>
          <li>20</li>
          <li>30</li>
          <li>40</li>
          <li>50</li>
          <li>60</li>
          <li>70</li>
          <li>80</li>
          <li>90</li>
        </ul>
      </div>
      <div class="system frequency-fine">
        <h3>finetune</h3>
        <ul>
          <li>0</li>
          <li>1</li>
          <li>2</li>
          <li>3</li>
          <li>4</li>
          <li>5</li>
          <li>6</li>
          <li>7</li>
          <li>8</li>
          <li>9</li>
        </ul>
      </div>
      <div class="system detune">
        <h3>detune</h3>
        <ul>
          <li>00</li>
          <li>10</li>
          <li>20</li>
          <li>30</li>
          <li>40</li>
          <li>50</li>
          <li>60</li>
          <li>70</li>
          <li>80</li>
          <li>90</li>
        </ul>
      </div>
      <div class="system sync">
        <h3>sync</h3>
      </div>
    </div>

    <div class="systems">
      <div class="system load-sample inactive">
        <h3>reinitialize</h3>
      </div>
      <div class="match">0</div>
    </div>
  </div>
</section>

    <script type="text/javascript">
// Utils
const HR = new HashRandomizer();
const MAX_FREQ = 99;
const MAX_DETUNE = 90;

// UI
class VisBase extends HTMLElement {
  static get observedAttributes() {
    return ['color'];
  }

  constructor() {
    super(...arguments);
    this.values = {};
    this.height = 1024;
    this.width = 2048;
    this.normalizeCurve = true;
    this.attachShadow({
      mode: "open"
    });
    this.render();
    this.canvas = this.render();
    this.context = this.canvas.getContext("2d");
  }

  scale(v, inMin, inMax, outMin, outMax) {
    return ((v - inMin) / (inMax - inMin)) * (outMax - outMin) + outMin;
  }

  draw(values) {
    if (this.canvas) {
      const canvas = this.canvas;
      const context = this.context;

      canvas.height = this.height;
      canvas.width = this.width;
      const width = canvas.width;
      const height = canvas.height;
      context.clearRect(0, 0, width, height);

      const maxValuesLength = 2048;
      if (values.length > maxValuesLength) {
        const resampled = new Float32Array(maxValuesLength);
        // down sample to maxValuesLength values
        for (let i = 0; i < maxValuesLength; i++) {
          resampled[i] =
            values[Math.floor((i / maxValuesLength) * values.length)];
        }
        values = resampled;
      }
      const max = this.normalizeCurve ?
        Math.max(0.001, ...values) * 1.1 :
        1;
      const min = this.normalizeCurve ?
        Math.min(-0.001, ...values) * 1.1 :
        0;
      const lineWidth = 5;
      context.lineWidth = lineWidth;
      context.beginPath();
      for (let i = 0; i < values.length; i++) {
        const v = values[i];
        const x = this.scale(i, 0, values.length, lineWidth, width - lineWidth);
        const y = this.scale(v, max, min, 0, height - lineWidth);
        if (i === 0) {
          context.moveTo(x, y);
        } else {
          context.lineTo(x, y);
        }
      }
      context.lineCap = "round";
      context.strokeStyle = this.getAttribute('color') ?? "#fc0";
      context.stroke();
    }
  }

  updated() {
    clearTimeout(this.timeout);
    this.timeout = setTimeout(() => {
      this.generate();
    }, 50);
  }

  render() {
    this.shadowRoot.innerHTML = `
      <div id="container">
        <canvas></canvas>
      </div>
    `;
    return this.shadowRoot.querySelector("canvas");
  }

  set color(val) {
    //this.setAttribuite('color', val);
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (name === 'color') {
      this.color = newValue;
    }
  }
}

class ToneWaveformElement extends VisBase {
  constructor() {
    super(...arguments);
  }
  generate() {
    this.render();
  }
  loop() {
    requestAnimationFrame(this.loop.bind(this));
    if (!this.tone) {
      return;
    }
    const values = this.tone.getValue();
    this.draw(values);
  }
  bind(tone) {
    this.tone = tone;
    this.loop();
  }
}

customElements.define("analyzer-waveform", ToneWaveformElement);

/**
 * Create an audio node element
 */
function createWaveform({
  tone,
  parent,
  height,
  color,
}) {
  const element = document.createElement("analyzer-waveform");
  element.bind(tone);

  if (parent) {
    parent.appendChild(element);
  }
  if (height) {
    element.setAttribute("height", height.toString());
  }
  if (color) {
    element.setAttribute("color", color);
  }
  return element;
}

// Views
const initializer = document.querySelector(".init")
const analyzer = document.querySelector(".analyzer")

//attach a click listener to a play button
document.querySelector(".init > button").addEventListener("click", async () => {
  await Tone.start();
  initializer.classList.remove('active');
  analyzer.classList.add('active');

  [...document.querySelectorAll(".parent .system, .system ul > li")].forEach(btn => {
    btn.onclick = () => {
       [...document.querySelectorAll(".systems > .selected, .system ul > li")].forEach(clear);
       
      btn.classList.add('selected')
      handleKeydown({preventDefault: ()=>{}, key: 'Enter'})
    }
  })

  activate();
});

let playing = false;
let active = false;
let sample = new Tone.Oscillator().toDestination();
let scope = new Tone.Oscillator({type: "sine"}).toDestination();

const types = [
  "sine",
  "square",
  "triangle",
  "sawtooth",
];

const loadSample = (settings = {}) => {
  const sampleSettings = {
    type: HR.random(types),
    frequency: HR.random(MAX_FREQ - 10) + 10,
    detune: 10 * HR.random(MAX_DETUNE/10),
    ...settings
  }
  sample.set(sampleSettings)
  
  console.log({settings: {
    detune: sample.detune.value,
    frq: sample.frequency.value,
  }, sampleSettings, sample })
}

function calcMatch() {
  toggleSound()
  toggleSound()
  const total = (types.length - 1) + MAX_FREQ + MAX_DETUNE
  const sampleType = sample?.type ?? '';
  const scopeType = scope?.type ?? '';
  
  const diff = Math.abs(
    (
      types.indexOf(sampleType) +
      sample.frequency.value +
      sample.detune.value
    )/total -
    (
      types.indexOf(scopeType) +
      scope.frequency.value +
      scope.detune.value
    )/total
  );
  
//  const match = diff === 0 && (
  const match = diff < 0.6 && (
    types.indexOf(sampleType) == types.indexOf(scopeType) &&
    sample.frequency.value == scope.frequency.value &&
    sample.detune.value ==scope.detune.value
  )

  console.log("diff: " + diff + ", match " + match);

  document.querySelector(".match").innerText = 100 - Math.ceil(diff * 100) - (match ? 0 : 1);

  const syncBtn = document.querySelector(".sync > h3")
  syncBtn.classList[match ? "add" : "remove"]("warning");
  
  const loadBtn = document.querySelector(".load-sample")
  loadBtn.classList[match ? "remove" : "add"]("inactive");
  
  return match;
}

function activate() {
  const sampleWaveform = new Tone.Waveform();
  sample.connect(sampleWaveform);

  const scopeWaveform = new Tone.Waveform();
  scope.connect(scopeWaveform);

  createWaveform({
    color: "#FF5555",
    tone: sampleWaveform,
    height: window.innerHeight,
    parent: document.querySelector("#sample"),
  });

  createWaveform({
    tone: scopeWaveform,
    height: window.innerHeight,
    parent: document.querySelector("#scope"),
  });
  
  active = !active;

  loadSample()
  scope.frequency.value = 0;
  toggleSound(); 
}

function toggleSound() {
  sample[playing ? "stop" : "start"]();
  scope[playing ? "stop" : "start"]();
  playing = !playing;
}

// UI
const handleUpdate = {
  set(obj, prop, value) {
    // Undefined value
    if (value === []._) {
      return false;
    }
    // Non scope props
    if (['load-sample', 'sync'].includes(prop)) {
      calcMatch();
      return false;
    }
    // Numeric props
    if (['frequency', 'detune'].includes(prop)) {
      scope[prop].value = obj[prop] = +value;
    }
    
    if (['frequency-fine'].includes(prop)) {
      obj[prop] = +value;
      scope.frequency.value = +value + obj.frequency;
    }
    
     // Type props
    if (['type'].includes(prop)) {
      obj[prop] = value
      scope.set({ [prop]: value });
    }
    calcMatch();
    return true;
  },
};
const settings = new Proxy({}, handleUpdate);

const saveSettings = () => (
  [...document.querySelectorAll('.system')]
  .forEach(sys => {
    const prop = [...sys.classList].find(c => !['system', 'selected'].includes(c));
    const value = [...sys.querySelectorAll('li')]
      .find(s => s.classList.contains('selected'))?.innerText;
    settings[prop] = value
  })
)

const clear = s => s?.classList?.remove('selected')
const next = (el, dir = 1) => {
  const prev = dir == -1
  const sibling = el[
    prev ? 'previousElementSibling' : 'nextElementSibling'
  ]
  return sibling || el.parentNode[prev ? 'lastChild' : 'firstChild'];
}

const handleKeydown = (e) => {
  e.preventDefault()
  const {
    key
  } = e
  let activeSystem = document.querySelector(".system.selected:not(.inactive)")
  let activeSettings = document.querySelector(".system.selected .selected")

  switch (key) {
    case "ArrowUp":
    case "ArrowDown":
      if (!!activeSystem) {
        if (!activeSettings) {
          const currentSettings = activeSystem.querySelectorAll('li')
          currentSettings[
            key == "ArrowUp" ? currentSettings.length - 1 : 0
          ].classList?.add('selected')
          return;
        }
        activeSettings.classList.remove('selected')
        next(activeSettings, key == "ArrowUp" ? -1 : 1)
          .classList?.add('selected')
      }
      break;

    case "ArrowLeft":
    case "ArrowRight":
      activeSystem = document.querySelector(".system.selected")
      if (!activeSystem) {
        const currentSystems = document.querySelectorAll(".system")
        currentSystems[
          key == "ArrowLeft" ? currentSystems.length - 1 : 0
        ].classList?.add('selected')
        return;
      }

      [...document.querySelectorAll(".parent .systems > .selected")].forEach(clear)
      next(activeSystem, key == "ArrowLeft" ? -1 : 1)
        .classList?.add('selected')
      break;
    case "Tab":
      break;
    case " ":
    case "Enter":
      if (!!activeSystem) {
          const currentSettings = activeSystem.innerText;
          switch (currentSettings) {
            case 'NEXT SAMPLE':
              loadSample()
              scope.set({
                type: "sine",
                frequency: 0,
                detune: 0,
              })
              break;
            case 'SYNC':
              calcMatch()
              break;
          }
      }
      break;
    default:
  }

  saveSettings('sample')
}

window.onkeydown = handleKeydown
  </script>
</body></html>