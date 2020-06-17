apt-get update
apt-get install git wakeonlan apache2 php7.3 libapache2-mod-php php7.3-sqlite -y

echo 'www-data    ALL=(ALL:ALL) ALL' | tee -a /etc/sudoers
sed -i '11 b; s/AllowOverride None\b/AllowOverride All/' /etc/apache2/apache2.conf
systemctl restart apache2
chmod 777 -R /var/www/html
rm -rf HomeSweetHome
git clone https://github.com/johnhart96/HomeSweetHome/
mv HomeSweetHome/* /var/www/html/
rm -rf HomeSweetHome
rm /var/www/html/index.html
echo 'AuthUserFile /var/www/html/.htpasswd' >> /var/www/html/.htaccess
echo 'AuthType Basic' >> /var/www/html/.htaccess
echo 'AuthName "Please login"' >> /var/www/html/.htaccess
echo 'Require valid-user' >> /var/www/html/.htaccess

htpasswd -mc /var/www/html/.htpasswd admin



