## About

Sistema de control de acceso para el Consorcio Portuario de la ciudad de Mar del Plata.
El sistema esta desarrollado utilizando principalmente [Laravel 5.6](https://laravel.com/docs/5.6/) y [Vue.js](https://vuejs.org/).

## Instalación del entorno de desarrollo

Una vez clonado el repositorio, y cumpliendo con los [pre-requisitos de Laravel](https://laravel.com/docs/5.6/installation#server-requirements), los pasos a seguir son:
- Realizar una copia del archivo **.env.example** y nombrarlo **.env**. Dentro de la copia, configurar la información de la base de datos a utilizar.
- Dentro de la carpeta del proyecto, ejecutar **composer install** para instalar las dependencias de PHP.
- Luego es necesario instalar las dependencias de Node.js, por lo tanto correr el comando **npm install**.
- Una vez finalizada la instalación de las dependencias, correr el comando **php artisan key:generate** para generar la clave del proyecto.
- Corriendo el comando **php artisan migrate** se crearán las tablas necesarias para el funcionamiento del sistema.
- Finalmente, correr el comando **php artisan serve** para iniciar el servidor de desarrollo, el cual (por defecto) será accesible a través de http://localhost:8000.

Opcionalmente, se pueden utilizar los siguientes comandos:
- **php artisan db:seed**: crea un set de datos de testeo para algunas de las tablas.
- **npm run dev**: compila los assets (Vue.js, Sass, etc.) en los archivos correspondientes.
- **nmp run watch**: compila los assets y se queda a la espera de nuevos cambios, compilando los archivos correspondientes ante cada cambio.
