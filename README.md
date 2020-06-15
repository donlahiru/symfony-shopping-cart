# symfony-shopping-cart

##  Installation
* Clone the project.

* Rename .env.example to .env and add your database credentials and database name.
``` bash
 DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
```

* Install dependencies 
``` bash
composer install
``` 

* Create the database
``` bash
php bin/console doctrine:database:create
```

* Execute database migrations
``` bash
 php bin/console doctrine:migrations:migrate
```

* Download the symfony local web server
``` bash
wget https://get.symfony.com/cli/installer -O - | bash
```

* Run server
``` bash
symfony server:start
```

* Run Unit Test
``` bash
 ./bin/phpunit
```

##  assumptions
* When you are in checkout page if you want to use coupon code the code is
``` bash
CODE99
```
