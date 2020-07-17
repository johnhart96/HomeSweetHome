set echo off
clear
echo " _________________________________________"
echo " |       HomeSweetHome - Installer        |"
echo " |----------------------------------------|"
echo " |                                        |"
echo " | Welcome to the installer, in this      |"
echo " | installer we will install all the      |"
echo " | required packages and will be ready to |"
echo " | go when complete. Note this will only  |"
echo " | currently work on Debian or            |"
echo " | Raspberry Pi OS. For more infomation   |"
echo " | See out github page.                   |"
echo " |________________________________________|"
echo " "
read -p " Press any key to begin ..."
cd ~
echo -ne '#                        (01%)\r'
apt-get update > /dev/null
echo -ne '####                     (15%)\r'
apt-get install git apache2 php7.3 libapache2-mod-php php7.3-sqlite php7.3-zip dnsmasq -y > /dev/null
echo -ne '########                 (25%)\r'
path="`grep -i 'DocumentRoot' /etc/apache2/sites-available/000-default.conf`"
ip="`hostname -i`"
echo -ne '#########                (30%)\r'
www="${path/DocumentRoot /}"
sed -i '11 b; s/AllowOverride None\b/AllowOverride All/' /etc/apache2/apache2.conf
echo -ne '############             (45%)\r'
systemctl restart apache2 > /dev/null
echo -ne '###############          (50%)\r'
rm -rf HomeSweetHome > /dev/null
git clone https://github.com/johnhart96/HomeSweetHome/ > /dev/null
echo -ne '#################        (60%)\r'
mv HomeSweetHome/* $www
rm -rf $www/HomeSweetHome
echo -ne '######################   (80%)\r'
rm $www/index.html > /dev/null
echo -ne '######################## (90%)\r'
echo 'AuthUserFile /var/www/html/.htpasswd' >> $www/.htaccess
echo 'AuthType Basic' >> $www/.htaccess
echo 'AuthName "Please login"' >> $www/.htaccess
echo 'Require valid-user' >> $www/.htaccess
systemctl stop dnsmasq >> /dev/null
systemctl disable dnsmasq >> /dev/null
echo -ne '#########################(99%)\r'
sleep 1
clear
echo " _________________________________________"
echo " |       HomeSweetHome - Installer        |"
echo " |----------------------------------------|"
echo " |                                        |"
echo " | Please enter your admin password below |"
echo " |________________________________________|"
htpasswd -mc $www/.htpasswd admin
clear
echo " ________________________________________"
echo " |      HomeSweetHome - Installer        |"
echo " |---------------------------------------|"
echo " |                                       |"
echo " |         Installation complete         |"
echo " |                                       |"
echo " | Username: admin                       |"
echo " | Password: (what you entered above)    |"
echo " |                                       |"
echo " | You can access the web interface      |"
echo " | using the IP address of hostname      |"
echo " |_______________________________________|"
