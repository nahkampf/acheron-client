#!/bin/sh
cat scss/*.scss > combined.temp
#sass --style=compressed scss/combined.temp css/style.css
sass combined.temp css/style.css
rm -rf combined.temp
