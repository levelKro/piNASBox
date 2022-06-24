#!/bin/bash

workdir="/home/pi/pinasbox"

cd $workdir

case "$1" in 
	"icon")
		sh $workdir/run_icon.sh $2 $3 $4 &
	;;
    "poweroff")
		sh $workdir/icon.sh 0 power topleft &
		sudo shutdown -h now
	;;
	"reboot")
		sh $workdir/icon.sh 0 power topleft &
		sudo shutdown -r now
	;;
	"webctrl")
		if [ "$2" = "start" ]
		then
			echo "Starting piWebCTRL"
		    sudo -E /usr/bin/python3 $workdir/ctrl/webctrl.py >$workdir/webctrl.log 2>&1 &
		elif [ "$2" = "stop" ]
		then
			if [ ! -z "$(pgrep -f webctrl.py)" ]
			then
				let pid=$(pgrep -f webctrl.py)
				echo "piWebCTRL is stopped (kill)."
				sudo -E kill -9 $pid
			else
				echo "piWebCTRL is not running."
			fi
		elif [ "$2" = "restart" ]
		then
			if [ ! -z "$(pgrep -f webctrl.py)" ]
			then
				let pid=$(pgrep -f webctrl.py)
				echo "piWebCTRL is stopped (kill)."
				sudo -E kill -9 $pid
			else
				echo "piWebCTRL is not running."
			fi
			sleep 2
			echo "Starting piWebCTRL"
			sudo -E /usr/bin/python3 $workdir/ctrl/webctrl.py >$workdir/webctrl.log 2>&1 &
		fi
	;;
	"ui")
		if [ "$2" = "start" ]
		then
			echo "Starting NASBox UI"
			DISPLAY=:0 nice /usr/bin/python3 $workdir/pinasbox.py >$workdir/pinasbox.log 2>&1 &
		elif [ "$2" = "stop" ]
		then
			if [ ! -z "$(pgrep -f pinasbox.py)" ]
			then
				let pid=$(pgrep -f pinasbox.py)
				echo "NASBox UI is stopped (kill)."
				sudo -E kill -9 $pid
			else
				echo "NASBox UI is not running."
			fi
		elif [ "$2" = "restart" ]
		then
			if [ ! -z "$(pgrep -f pinasbox.py)" ]
			then
				let pid=$(pgrep -f pinasbox.py)
				echo "NASBox UI is stopped (kill)."
				sudo -E kill -9 $pid			
			else
				echo "NASBox UI is not running."
			fi
			sleep 2
			echo "Starting NASBox UI"
			DISPLAY=:0 nice /usr/bin/python3 $workdir/pinasbox.py >$workdir/pinasbox.log 2>&1 &	
		elif [ "$2" = "check" ]
		then
			if pgrep -f "nasbox.py" > /dev/null 
			then
				#is running
				echo "piNASBox UI is running."
			else
				#not running, restart them
				echo "piNASBox UI is not running."
			fi

		fi
	;;
	"start")
		sh $workdir/pinasbox.sh
	;;
esac
		