# SonWEB
SonWEB is a administrative Website for Devices flashed with [Sonoff-Tasmota](https://github.com/arendst/Sonoff-Tasmota).   
You can find it here: [SonWEB GitHub](https://github.com/reloxx13/SonWEB)

## Features
* Login protected
* Multi Update Process
  * Select devices to update
  * Automatic Modus downoads latest firmware bin from the Tasmota GIT
* Show device informations
* Mobile Responsive (Bootstrap4)
* Config devices
* SelfUpdate function for SonWEB
* NightMode (Enable/Disable/Auto) in settings
* AutoScan to find Tasmota Devices
   
## Requirements
   
### Windows
I provide a ready2use ZIP on the releases page with XAMPP

### Unix Linux (e.g. Raspi Jessie)
* A Webserver 
  * recommend apache2
  * recommend php7 (works with php5, too)
  * php-curl php-zip Modules installed
* Link to update Raspi Jessie with Apache2 to PHP7.0 (not important, should also run at PHP5)
  * if planned to use nginx, ignore this session in the instructions, be aware follow the output in console
  * https://electrodrome.net/debian-8-jessie-php-7-0-und-nginx-auf-dem-raspberry-pi-installieren-und-einrichten/
* Link to install the service without new webserver implementation on Raspi
  * the example is on ubuntu but works well for raspi, be aware follow the output in console
  * https://github.com/reloxx13/SonWEB/wiki/Guide-for-Ubuntu-Server-16.04


### Docker
SonWEB is available in a docker image: [raymondmm/sonoff-docker](https://hub.docker.com/r/raymondmm/sonweb/), which is a Linux Alpine (3.7) based image with Apache2 and Php7 installed and supports **arm** (i.e. Raspberry PI3) and **amd64** (i.e. Synology DSM). Check out the [Wiki for running SonWEB in a Docker Container](https://github.com/RaymondMouthaan/sonweb-docker/wiki) for install instructions.


## Installation
### Windows
1. Download the XAMPP Zip package from the releases page
2. Extract the Zip (recommend to put the xamp folder in C:\
3. Run once the xamp\setup_xampp.bat
4. Start xampp-control.exe
5. Start Apache in the opened ControlCenter
6. Get your local IP Address

Now you can type in your browser http://YOURLOCALIP and SonWEB shows up.

### Unix
For unix its a bit harder. You need to install a web server with php-zip and php-curl modules installed. Also mod_rewrite must be enabled. I suggest to look in the [Guide for Ubuntu Server 16.04](https://github.com/reloxx13/SonWEB/wiki/Guide-for-Ubuntu-Server-16.04) and try to adjust it to your server OS.
    
## Example Images
![Login](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/1.png)
![Startpage](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/2.png)
![Devices](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/3.png)
![Device Add/Edit](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/3_1.png)
![Device Config_GENERAL](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/4.png)
![Device Config_Network](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/4_1.png)
![Device Update 1](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/5.png)
![Device Update 2](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/5_1.png)
![Device Update 3](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/5_2.png)
![Settings](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/6.png)
![Settings](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/7.png)
   
![Navi_M](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/m1.png)
![Startpage_M](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/m2.png)
![Devices_M](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/m3.png)
![Config_General_M](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/m4.png)
![Config_Network_M](https://raw.githubusercontent.com/reloxx13/reloxx13.github.io/master/media/sonweb/readme/m4_1.png)
