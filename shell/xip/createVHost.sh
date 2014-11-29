name=$1

cat <<EOF | sudo tee /etc/apache2/sites-available/$name.conf
<VirtualHost *:80>
   ServerAdmin webmaster@$name.com
   ServerName $name
   ServerAlias $name.*.xip.io

   DirectoryIndex index.html index.php
   DocumentRoot /var/www/$name/public_html

   <Directory /var/www/$name/public_html/>
      Options Indexes FollowSymLinks MultiViews
      AllowOverride All
      Order allow,deny
      allow from all
   </Directory>

   LogLevel warn
   ErrorLog  /var/www/$name/public_html/error.log
   CustomLog /var/www/$name/public_html/access.log combined
</VirtualHost>
EOF
