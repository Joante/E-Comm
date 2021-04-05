<ol type="inherit">Requisitos:
    <li>Php 7.2+</li>
    <li>MySQL 5.5+</li>
    <li>Apache 2 con Mod Rewrite activado</li>
</ol>

<ol type=”A”>Instalar:
  <li>Clonar el repositorio</li>
  <li>Correr el comando: <strong>composer install</strong></li>
  <li>Modificar los atributos en el archivo .env:
  			- DB_DATABASE=e-comm
            - DB_USERNAME=root
            - DB_PASSWORD=
  </li>
  <li>Correr el comando: <strong>php artisan migrate</strong></li>
  <li>Correr el comando: <strong>php artisan db:seed</strong></li>
  <li>Correr el comando: <strong>php artisan key:generate</strong></li>
  <li>Correr el comando: <strong>php artisan serve</strong></li>
</ol>
