#!/bin/bash

# Configurar Apache para que escuche en el puerto que Render asigna
# (Asegura que el sitio raíz esté configurado correctamente)
echo "Iniciando Apache..."

# Ejecutar Apache en primer plano (necesario para que Render no detenga el contenedor)
# Es vital usar -DFOREGROUND para que el contenedor no se cierre
exec apache2ctl -D FOREGROUND