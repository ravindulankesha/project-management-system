Instructions-

1.Clone the repository

2.Copy the .env.example file and paste and rename it as .env

3.Replace the lines 22-27 in the newly created .env with the following
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=customer_projects
DB_USERNAME=root
DB_PASSWORD=

4.Open a terminal in the project root directory and enter composer install 

5.Open a terminal in the project root directory and enter 
php artisan key:generate

6.create a new mysql DB named customer_projects and enter php artisan migrate in terminal

7.Finally Enter this in terminal
php artisan serve