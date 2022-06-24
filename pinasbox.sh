#!/bin/bash
workdir="$(dirname "$0")"

echo "Start piWebCTRL."
/home/pi/pinasbox/pinb.sh webctrl start &
echo "Starting X Server and piDeskboard UI."
startx -- -nocursor -quiet