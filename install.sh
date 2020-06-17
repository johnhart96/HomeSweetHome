cd ~

apt-get update
apt-get install git wakeonlan apache2 php7.3 libapache2-mod-php php7.3-sqlite -y
path="`grep -i 'DocumentRoot' /etc/apache2/sites-available/000-default.conf`"
www="${path/DocumentRoot /}"
sed -i '11 b; s/AllowOverride None\b/AllowOverride All/' /etc/apache2/apache2.conf
systemctl restart apache2
rm -rf HomeSweetHome
git clone https://github.com/johnhart96/HomeSweetHome/ $www
mv $www/HomeSweetHome/* $www
rm -rf $www/HomeSweetHome
rm $www/index.html
echo 'AuthUserFile /var/www/html/.htpasswd' >> $www/.htaccess
echo 'AuthType Basic' >> $www/.htaccess
echo 'AuthName "Please login"' >> $www/.htaccess
echo 'Require valid-user' >> $www/.htaccess

htpasswd -mc $www/.htpasswd admin





