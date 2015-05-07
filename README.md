## CodeEcommerce
Ecommerce em Laravel5 do curso da CodeEducation

# Para Instalação
- Copiar o arquivo .env.example para .env e preenchê-lo com os
dados do seu banco de dados Mysql

- Para configurar/criar as tabelas no banco de dados, rode
os seguintes comandos:

# Para preparar as migrations
$ php artisan migrate:install
$ php artisan migrate:refresh

# Para popular o banco de dados
$ php artisan db:seed

# Rode no browser
- localhost/public/admin/products
