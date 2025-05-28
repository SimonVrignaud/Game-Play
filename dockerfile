FROM php:8.2-apache

# Mise à jour et installation des dépendances nécessaires
RUN apt update && apt upgrade -y \
  && apt install -y \
    libfreetype6-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    nano \
    nala \
  && docker-php-ext-configure gd --with-freetype=/usr/include/freetype2/ \
  && docker-php-ext-install pdo_mysql gd \
  && a2enmod rewrite \
  # Autoriser les .htaccess dans /var/www/
  && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
  # Nettoyage des fichiers inutiles pour alléger l'image
  && apt clean && rm -rf /var/lib/apt/lists/*

# Exposer le port par défaut d'Apache
EXPOSE 80