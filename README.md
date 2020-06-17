# HomeSweetHome
HomeSweetHome is a free home network web interface for sending Wake-On-Lan commands and launching remote control. It is designed to work over a VPN and not port forwarded, but it will work portforwarded, but the remote control features will not work unless directly connected or routable to your home lan. I recomend [PiVPN](https://www.pivpn.io/)

## Requirements
* Debian Linux / Raspberry Pi OS
* Apache 2
* PHP 7.3
* PHP-SQLite3
* wakeonlan

## Prerequisites
* Setup a VPN solution first, so that you have remote access
* Setup a Static IP on the machine your installing HomeSweetHome to
* Port forward ether your VPN port or the web interface port.

## Installation
```console
wget https://raw.githubusercontent.com/johnhart96/HomeSweetHome/master/install.sh
sudo bash install.sh
rm install.sh
```
 
 ## Login
 You can login from the IP address or DNS hostname of your machine, the default username is 'admin' and the password is whatever you entered during installation
