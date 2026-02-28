# Installation eines VServer in der Strato Server Cloud

## Serverdaten

Der Server-Benutzername lautet root.
Das Passwort für den Zugriff auf den Server lautet *zQ%n$8R72

### Zugangsdaten

Host: 82.165.250.164
Benutzer: root
Initial-Passwort: *zQ%n$8R72

### DNS

DNS-Hostname: a1a6f4f.online-server.cloud
Image: Quelle: STRATO Images
Betriebssystem: Ubuntu 24.04

### IP

IPv4-Adresse: 82.165.250.164
IPv6-Adresse: Keine IPv6-Adresse verfügbar

### Konfiguration

Typ:Cloud Server
CPU:1 vCore
RAM:2 GB
SSD:20 GB
Connection speed up to:400 Mbps

### Firewall-Richtlinien

82.165.250.164 Linux
Private Netzwerke: Kein privates Netzwerk vorhanden
Monitoring-Richtlinien: Standard Monitoring-Richtlinie
Datacenter region: Rechenzentrum:Süddeutschland
Availability zone: 1
Erstellungsdatum: 27.02.26 21:25:29

## Konfiguration des Webserver

### SSH-Zugänge

#### SSH-Zugang für root

```bash
ssh-copy-id -i ~/.ssh/id_ed25519.pub root@82.165.250.164

```

Now try logging into the machine, with:

```bash
ssh root@82.165.250.164

```

and check to make sure that only the key(s) you wanted were added.

#### SSH-Zugang für norbert

##### Neuen Benutzer anlegen

```bash
useradd -m -s /bin/bash norbert
passwd norbert
```

##### norbert der Gruppe norbert und www-data hinzufügen

```bash
usermod -aG www-data norbert
usermod -aG norbert www-data
```

#### SSH-Zugang für einrichten

```bash
ssh-copy-id -i ~/.ssh/id_ed25519.pub norbert@82.165.250.164

```

Now try logging into the machine, with:

```bash
ssh norbert@82.165.250.164

```

and check to make sure that only the key(s) you wanted were added.

##### Root-Login per SSH deaktivieren (empfohlen)

```bash
nano /etc/ssh/sshd_config
```

Diese Zeile anpassen:

```text
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

#### PHP 8.4 + PHP-FPM installieren

```bash
add-apt-repository -y ppa:ondrej/php
apt-get update

apt install -y php8.4-fpm php8.4-cli php8.4-common \
  php8.4-mysql php8.4-xml php8.4-mbstring php8.4-curl \
  php8.4-zip php8.4-bcmath php8.4-tokenizer php8.4-gd \
  php8.4-redis php8.4-intl

systemctl enable php8.4-fpm
systemctl start php8.4-fpm
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
mysql -u root -p -> *zQ%n$8R72
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

### Nginx konfigurieren

```bash
nano /etc/nginx/sites-available/wahlprognose
```

Inhalt:

```nginx
server {
    listen 80;
    server_name 82.165.250.164;
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
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
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

```bash
chown -R norbert:www-data /var/www
```

## Laravel installieren und konfigurieren

### Als norbert einloggen

```bash
ssh norbert@82.165.250.164

```

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

### Laravel App von Github clonen

```bash
cd /var/www
git clone git@github.com:nfgarching/wahlprognose.git

composer install
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
APP_URL=http://82.165.250.164

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wahlprognose
DB_USERNAME=wahlprognose
DB_PASSWORD=sicheres_passwort
```

---

## SSL mit Let's Encrypt einrichten

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d http://82.165.250.164 -d www.deine-domain.de
```

---

## Redis installieren (optional)

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

## Abschließender Check

```bash
cd /var/www/wahlprognose
php artisan migrate
php artisan config:cache
php artisan route:cache
```

---

Aufruf der Webseite http://82.165.250.164