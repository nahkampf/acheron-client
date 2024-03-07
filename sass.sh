#!/bin/sh
cat scss/*.scss > scss/combined.temp
sass --style=compressed scss/combined.temp css/style.css
rm -rf scss/combined.temp
