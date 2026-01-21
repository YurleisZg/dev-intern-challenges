#!/bin/bash

# Script de inicialización de la base de datos
# Este script se ejecuta automáticamente cuando se crea el contenedor

echo "Esperando a que MariaDB esté listo..."
until mysql -u root -p"${MYSQL_ROOT_PASSWORD}" -e "SELECT 1" &> /dev/null; do
  sleep 1
done

echo "Creando usuario y otorgando permisos..."
mysql -u root -p"${MYSQL_ROOT_PASSWORD}" <<-EOSQL
    CREATE USER IF NOT EXISTS '${MYSQL_USER}'@'%' IDENTIFIED BY '${MYSQL_PASSWORD}';
    GRANT ALL PRIVILEGES ON ${MYSQL_DATABASE}.* TO '${MYSQL_USER}'@'%';
    FLUSH PRIVILEGES;
EOSQL

echo "Base de datos inicializada correctamente!"
