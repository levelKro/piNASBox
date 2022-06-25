# PiNASBox
Turn any Raspberry Pi into a great NAS Box.

## Require

* Raspberry Pi (Zero is great choice)
* Ethernet + USB Hat (for Raspberry Pi Zero)
* LCD 3.5"
* 2 physical buttons
* Temperatur sensor based on DS18B20
* One or more USB Disk

## Installation

Prepare a new SD Card with Raspbian OS Lite, make sure to enable the SSH and the Raspberry can be connected to a network with Ethernet.

> You can use the Wireless but you need to configure the Raspberry by yourself to use it.

### Install pre-requis packages

```
sudo apt update && sudo apt upgrade -y
sudo apt -qq install -y git wget samba apache2 
sudo apt -qq install -y --no-install-recommends xserver-xorg x11-xserver-utils xinit openbox
sudo apt -qq install -y python3-dev python3-pip yasm python3-numpy
sudo apt -qq install -y php php-cli php-curl php-xml php-xmlrpc
pip install --upgrade pip setuptools wheel
pip install numpy psutil gpiozero Pillow wxpython configparser 
```

### Setup system

### *Display*

The UI is made for a 480x320 display, like TFT/LCD 3.5" models. You need to setup it before continue.

You can use the Good TFT Git for that, cover major display.

```
cd /home/pi
git clone https://github.com/goodtft/LCD-show
cd LCD-show
sudo ./<model>35-show
```

For more information, consult the manual of your hardware.


### *Fix for Display with Touch*

A problem exists with LCD screens with the Touch, as they are designed for desktop use. The fb-turbo driver is loaded, but causes the desktop to crash, preventing its use.

To correct this problem, the file must be renamed and corrected.

```
sudo sed -i 's|fbturbo|fbdev|g' /usr/share/X11/xorg.conf.d/99-fbturbo.conf
sudo mv /usr/share/X11/xorg.conf.d/99-fbturbo.conf /usr/share/X11/xorg.conf.d/99-fbdev.conf
```

### *Edit the config file*

Edit the */boot/config.txt* and add/edit this lines.

```
disable_touchscreen=1
dtoverlay=w1-gpio
dtparam=audio=off
```

And reboot.

### *Temperature sensor*

PiNASBox come with the DS18B20 script for read temperature. You can use any compatible sensor. 
The configuration file is ready, if you have followed the previous step. 

Enable the temperature module with this commands;

```
sudo modprobe w1-gpio
sudo modprobe w1-therm
```

And verify if you see the sensor with this command.

```
ls /sys/bus/w1/devices
```

If you see a item beginning with "28-", the sensor is corectly installed.

### *Real Time CLock (RTC) module*

I suggest to instal a RTC module with you project, because you can use the drive on network without Internet connection.

See on Internet for a tutorial to how setup a RTC on Rasberry Pi.

## Setup the PiNASBox

Clone this repository on you Raspberry pi.

```
cd /home/pi
git clone https://github.com/levelkro/pinasbox
```

After, you need to add autorun commands.

In the */etc/xdg/openbox/autostart* file, add these lines at the end.

```
xset s off
xset s noblank
xset -dpms
setxkbmap -option terminate:ctrl_alt_bksp
DISPLAY=:0 nohup /home/pi/pinasbox/pinb.sh ui start >/dev/null 2>&1 &
```

In the */home/pi/.profile* file, add these line at the end.

```
[[ -z $DISPLAY && $XDG_VTNR -eq 1 ]] && /home/pi/pinasbox/pinasbox.sh
```

In the */etc/samba/smb.conf* file, add these line at the end.

```
[home$]
   path = /home/pi
   browseable = yes
   force user = pi
   force group = pi
   read only = no
   writable = yes
   guest ok = yes


[DRIVE A]
   path = /mnt/hdd1
   browseable = yes
   force user = pi
   force group = pi
   read only = no
   writable = yes
   guest ok = yes

[DRIVE B]
   path = /mnt/hdd2
   browseable = yes
   force user = pi
   force group = pi
   read only = no
   writable = yes
   guest ok = yes

[DRIVE C]
   path = /mnt/hdd3
   browseable = yes
   force user = pi
   force group = pi
   read only = no
   writable = yes
   guest ok = yes
```
Enable the modules Rewrite and Headers in apache.

```
sudo a2enmod rewrite
sudo a2enmod headers
```

In the */etc/apache2/sites-available/000-default.conf* and */etc/apache2/sites-available/apache2.conf* files, change these lines.

```
/var/www/html
```
For this one.
```
/homepi/pinasbox/www
```

And change this value
```
AllowOverride None
```
for this one.
```
AllowOverride All
```

Restarting the Raspberry Pi.

## Adding Hard drives

The PiNASBox support 3 Hard drives, if you need, more, you need to edit the scripts.

Each drive must be set a mount into this style;

- /mnt/hdd1
- /mnt/hdd2
- /mnt/hdd3


For better performance, use the UUID to link mount to hard drive. If you need help for manage your hard drives, you can install Webmin. Is what I do. If you use less than 3 drives, is not a problem, only mount the drive you have.

For use the Web file explorer, you need create Symbolic link into web folder, for that, execute this commands.

```
ln -s /mnt/hdd1 "/home/pi/pinasbox/www/get/Drive A"
ln -s /mnt/hdd2 "/home/pi/pinasbox/www/get/Drive B"
ln -s /mnt/hdd3 "/home/pi/pinasbox/www/get/Drive C"
```

## Buttons

Be default, wen use the GPIO #19 and #26, you can edit them, and other settings into the */home/pi/pinasbox/config.ini* file.
