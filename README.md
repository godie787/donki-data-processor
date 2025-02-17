#  DONKI Data Processor API

API en Laravel para consumir los datos de la NASA DONKI y procesar información de instrumentos y actividades espaciales.

##  Requisitos
Antes de instalar, asegúrate de tener instalado lo siguiente:

- PHP: 8.2.12 o superior
- Composer: 2.6.6 o superior
- Laravel: 11.42.1
- XAMPP

##  Instalación

### 1. Instalar PHP y Composer
Si no tienes PHP y Composer instalados, sigue estos pasos:

####  Instalar XAMPP (Opcional, si deseas MySQL y Apache)
- Descarga e instálalo.
- Asegúrate de activar los módulos de Apache y MySQL si usas una base de datos.

####  Instalar PHP manualmente
- Descarga PHP.
- Agrega la ruta de PHP al Path de tu sistema.
- Verifica la instalación con:
  php -v


####  Instalar Composer
- Descarga Composer.
- Instálalo y verifica con:
  composer -V


### 2️. Clonar el repositorio
git clone https://github.com/tu-usuario/tu-repositorio.git
cd tu-repositorio

### 3️. Instalar dependencias
composer install

### 4️. Configurar variables de entorno
Copia el archivo de configuración y edítalo:
cp .env.example .env

Luego, abre el archivo '.env' y añade tu clave de API de la NASA:
NASA_API_KEY=your_api_key_here

Si no tienes una API key, obtén una gratuita en (https://api.nasa.gov/).

### 5️. Generar clave de aplicación
php artisan key:generate

### 6️. Ejecutar el servidor de desarrollo
php artisan serve

##  Endpoints Disponibles

### - Obtener los endpoints válidos
GET /api/valid-endpoints

### - Obtener todos los instrumentos utilizados
GET /api/instruments

### - Obtener todas las IDs de actividades
GET /api/activity-ids

### - Obtener porcentaje de uso de cada instrumento
GET /api/instrument-usage

### - Obtener porcentaje de actividades por instrumento
POST /api/instrument-activity

##  Importar colección en Postman

Para probar los endpoints, importa la colección de Postman:

1️. Descarga el archivo donki_collection.json 
2️. En Postman, ve a File > Import.  
3️. Selecciona el archivo y listo. 
