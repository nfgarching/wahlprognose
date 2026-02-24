# Strato Server Cloud

## Serverdaten

Der Server-Benutzername lautet root.
Das Passwort für den Zugriff auf den Server lautet MqC@h&4%!i

### Zugangsdaten:

Host: 217.160.15.161
Benutzer: root
Initial-Passwort: MqC@h&4%!i

### DNS:

DNS-Hostname: 7cd7667.online-server.cloud
Image: Quelle: STRATO Images
Betriebssystem: Ubuntu 24.04

### IP:

IPv4-Adresse: 217.160.15.161
IPv6-Adresse: Keine IPv6-Adresse verfügbar

### Konfiguration:

Typ:Cloud Server
CPU:1 vCore
RAM:1 GB
SSD:10 GB
Connection speed up to:400 Mbps

### Firewall-Richtlinien:

217.160.15.161 Linux
Private Netzwerke: Kein privates Netzwerk vorhanden
Monitoring-Richtlinien: Standard Monitoring-Richtlinie
Datacenter region: Rechenzentrum:Süddeutschland
Availability zone: 1
Erstellungsdatum: 24.02.26 09:34:04

## Konfiguration des Webserver

### SSH-Zugänge

#### SSH-Zugang für root

```bash
ssh-copy-id -i ~/.ssh/id_ed25519.pub root@217.160.15.161

```

Now try logging into the machine, with:

```bash
ssh root@217.160.15.161

```

and check to make sure that only the key(s) you wanted were added.

#### SSH-Zugang für norbert

##### Benutzer norbert anlegen

```bash
useradd -m -s /bin/bash norbert
passwd norbert
```

##### norbert der Gruppe www-data hinzufügen

```bash
usermod -aG www-data norbert
```

##### www-data der Gruppe norbert hinzufügen

```bash
usermod -aG norbert www-data
```

##### SSH-Zugang für norbert einrichten (optional, aber empfohlen)

```bash
mkdir -p /home/norbert/.ssh
chmod 700 /home/norbert/.ssh
# Deinen öffentlichen SSH-Key hinterlegen
nano /home/norbert/.ssh/authorized_keys
chmod 600 /home/norbert/.ssh/authorized_keys
chown -R norbert:norbert /home/norbert/.ssh
```

Now try logging into the machine, with:

```bash
ssh norbert@217.160.15.161

```

and check to make sure that only the key(s) you wanted were added.


##### Root-Login per SSH deaktivieren (empfohlen)

```bash
nano /etc/ssh/sshd_config
```

Diese Zeile anpassen:

```
PermitRootLogin no
```

Dann SSH neu starten:

```bash
systemctl restart sshd
```

> **Wichtig:** Stelle sicher, dass du dich vorher erfolgreich als `norbert` per SSH einloggen kannst, bevor du den Root-Login deaktivierst – sonst sperrst du dich aus.





### Webserver einrichten

Hier ist die angepasste Anleitung für den Root-User und die App `wahlprognose`:

---

#### System aktualisieren

```bash
apt update && apt upgrade -y
```

---

#### Nginx installieren

```bash
apt install -y nginx
systemctl enable nginx
systemctl start nginx
```

---

#### PHP 8.3 + PHP-FPM installieren

```bash
apt install -y php8.3-fpm php8.3-cli php8.3-common \
  php8.3-mysql php8.3-xml php8.3-mbstring php8.3-curl \
  php8.3-zip php8.3-bcmath php8.3-tokenizer php8.3-gd \
  php8.3-redis php8.3-intl

systemctl enable php8.3-fpm
systemctl start php8.3-fpm
```

---

### MySQL installieren

```bash
apt install -y mysql-server
systemctl enable mysql
mysql_secure_installation
```

Datenbank anlegen:

```bash
mysql -u root -p
```

```sql
CREATE DATABASE wahlprognose;
CREATE USER 'wahlprognose'@'localhost' IDENTIFIED BY 'sicheres_passwort';
GRANT ALL PRIVILEGES ON wahlprognose.* TO 'wahlprognose'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

### Composer installieren

```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer --version
```

---

### Node.js installieren

´´´bash
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt-get install -y nodejs
node --version
npm --version
´´´

After upgrading, run npm install again (since node_modules may need rebuilding), then npm run build or composer dev.

### SSH-Schlüssel für GitHub installieren

Im ~/.ssh/-Verzeichnis fehlen die nötigen Dateien (id_rsa, id_ed25519 o.ä.) — nur authorized_keys und known_hosts sind vorhanden. Lösung: SSH-Schlüssel erstellen und bei GitHub hinterlegen:

1. Neuen SSH-Schlüssel generieren

```bash
ssh-keygen -t ed25519 -C "norbert.froehler@gmail.com"
```

2. Öffentlichen Schlüssel anzeigen 

```bash
cat ~/.ssh/id_ed25519.pub
```

Dann den angezeigten Schlüssel unter GitHub → Settings → SSH and GPG keys → New SSH key eintragen.

---

### Nginx konfigurieren

```bash
nano /etc/nginx/sites-available/wahlprognose
```

Inhalt:

```nginx
server {
    listen 80;
    server_name deine-domain.de www.deine-domain.de;
    root /var/www/wahlprognose/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Site aktivieren:

```bash
ln -s /etc/nginx/sites-available/wahlprognose /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

---


Hier sind die Schritte, um einen dedizierten Systembenutzer `norbert` anzulegen und ihn korrekt mit `www-data` zu verknüpfen:



## Laravel installieren und konfigurieren

### Als norbert einloggen

```bash
ssh norbert@217.160.15.161

```

### Laravel App von Github clonen

```bash
cd /var/www
git clone git@github.com:nfgarching/wahlprognose.git
composer install
sudo apt install npm
npm install && npm run build
```

### Verzeichnisrechte anpassen

```bash
chown -R norbert:www-data /var/www/wahlprognose
chmod -R 750 /var/www/wahlprognose
chmod -R 775 /var/www/wahlprognose/storage
chmod -R 775 /var/www/wahlprognose/bootstrap/cache
```

---

### Laravel `.env` konfigurieren

```bash
cd /var/www/wahlprognose
cp .env.example .env
php artisan key:generate
nano .env
```

Anpassen:

```env
APP_NAME=Wahlprognose
APP_URL=http://217.160.15.161

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wahlprognose
DB_USERNAME=wahlprognose
DB_PASSWORD=sicheres_passwort
```

---

## 10. SSL mit Let's Encrypt einrichten

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d deine-domain.de -d www.deine-domain.de
```

---

## 11. Redis installieren (optional)

```bash
apt install -y redis-server
systemctl enable redis
```

In `.env`:

```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

---

## 11. Abschließender Check

```bash
cd /var/www/wahlprognose
php artisan migrate
php artisan config:cache
php artisan route:cache
```

---


Aufruf der Webseite http://217.160.15.161


### Clone App

git clone git@github.com:nfgarching/wahlprognose.git
