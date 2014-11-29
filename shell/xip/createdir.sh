name=$1

sudo mkdir -p /var/www/$name/public_html
sudo chown -R $USER:$USER /var/www/$name/public_html
