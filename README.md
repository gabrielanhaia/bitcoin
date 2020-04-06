[![CircleCI](https://circleci.com/gh/gabrielanhaia/bitcoin/tree/master.svg?style=svg&circle-token=41d95a85d2f0b2ac88eb5eaaa36a7a920eed23c9)](https://circleci.com/gh/gabrielanhaia/bitcoin/tree/master)
    <img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License">
    
# Paxful - test

# API Documentation

* Postman Collection: https://www.getpostman.com/collections/8272261576b13a4019f5
* Apiary (Online API DOC): https://paxful.docs.apiary.io/#reference/transactions/transactions/transfer-money

## Running the project without docker

1. For some reason the Guzzle Http can't access the same server when we are using (PHP built in / php artisan serve).
So I recommend that you install in you machine Apache or Nginx and clone the folder inside your web directory. The index should be the folder *public*.

2. Copy the file *.env.example* to *.env* and change the variables bellow with your database options:
DB_CONNECTION=mysql
DB_HOST=mysql
DB_DATABASE=default
DB_USERNAME=default
DB_PASSWORD=secret
DB_PORT=3306

3. Run the command `composer install`
4. Run `php artisan key:generate`
5. Run `php artisan migrate`
6. Run `paxful:exchange_rates:manual_import`
7. Enjoy :)

## Running the project by Docker

1. Access the folder *laradock* and run: <br>
```docker-compose up -d nginx mysql ```

2. Copy the file *.env.example* to *.env* and change the variables bellow with this values:
DB_CONNECTION=mysql
DB_HOST=mysql
DB_DATABASE=default
DB_USERNAME=default
DB_PASSWORD=secret
DB_PORT=3306

3. Run the command `composer install`
4. Run `php artisan key:generate`
5. Run `php artisan migrate`
6. Run `paxful:exchange_rates:manual_import`
7. Enjoy :)

## About the Project

Important points about my test that I tried to follow (always):

1. To write a clear code easily to maintain (any developer)
2. Try to use Design Patterns (Only in situations that really make sense).
3. to follow the SOLID principles
4. Cover the project with Unit tests as much as possible (I wrote a few tests, It's not 100% covered).
5. PHP and developing without a framework (on the integration packages to get the exchange rates)
6. Organization (I used the Trello for controlling all tasks, I can share it with you if you would like).
7. PSRs.
8. Use different layers to organize the project when it grows (Repositories and Services)
9. Micro commits with clear messages.

*DONT FORGET TO LOOK AT THIS PACKAGE (It is part of the test)*<br>
[LINK HERE]<link.here>

## Important things implemented by me

0. The transactions (Transfers) are stored at the database with a status 'PENDING', then, the transaction is send to a queue to be processed. The result can be two new status 'PROCESSED' or 'NOT_PROCESSED' (When something wrong happens).
1. Transaction types: There are 4 types "TRANSFER_DEBIT" (The wallet that sends the money), "TRANSFER_CREDIT' (The wallet that receives the money), "DEBIT" (When the account receive money from another source, it could be a deposit, first wallet, etc), "CREDIT" (It could be when someone use a debit card or cash the money).
2. Repository layer: It implemented it to encapsulate the ORM (eloquent/data sources). Who knows if in the future we will change the things.
3. My repositories are receiving and returning Entities. I try to use this patter in big projects because it's much better the arrays that can't guarantee the data passed to the methods.
Besides that, with Entities (DTO), We can easily change the data source in the repositories (if it is necessary).
4. I am using the Service Layer to put the logic, on this way it's easier for the developers to maintain the project. Besides that, it is easier to test.
5. The repositories, services and models are all being injected by the Container (DI Laravel).
6. I am versioning the API, if we have APPs, web platforms and/or external integrations consuming our API, it will be easier to change/improve the endpoints.
7. The API token is really simple (as the required), it's just a api_token on the user table, in a real project we would think about a JWT token and maybe user OAuth...
8. I put all the models in an especific directory (I do not know why the Laravel don't change it, it is a mess).
9. The database was projected with index to speed up the queries (You can see the model on `database/`, there are a few files there.
10. I am getting the properties by the objects and you can see that in some layers I am not getting the object in the return (just using the normal behavior of the objects), for sure I agree it would be defined at the beginning of the project. I think it is an important thing to be defined, new developers can get confused with the object references.
11. To be sure that the last amount is correct I am getting by the transaction order (processed_at) (status = PROCESSED)
12. There are maybe 2 comments inside the methods (to explain something). I would never put a comment inside a method. I follow the principle that any developer should look at a method and understand it by himself.
13. In a real application, I would create an account (Paxful account) to store the company bitcoins to transfer the initial amount (debit) and earn the profit (credit)
14. I created a table to store settings (You can see there the "Maximum of wallets per user", "Bonus of bitcoins for new users" and "Profit per transfers between different users")
15. I created a manual importer (app/Console/Commands/ManualImportExchangeRates.php) for the exchange rates. I created it just to import all the rates quickly, so I can test my endpoints. My goal is to start the official importer (integration with the API) if I don't have enough time to do everything I am planning for the project.
16. The transactions are processed in a Queue (centralized), in production environment we could use REDIS and have different machines running our webserver. All of then would send the jobs to be processed in the same queue.

## The problems founded and observations

I have found different problems to solve in this project, a few of them were:
1. The security (It is working with money).
2. Don't lose transactions (never).
3. Keep the transactions/balance organized.
4. Loads of validations to don't duplicate or allow the users earn bitcoins.

## Technologies/Methodologies

**PHP 7.3**
**Laravel**
**Docker/Laradock**
**Composer**
**MySql 5.7**
**PHPUnit:**
**CircleCi:**
**Trello:** I tried to follow the scrum principles (actually planning and using the kanban) to keep the project organized. In the cards, there are the number of the issues on GitHub.
**PSRs:** I am following the PSR's for a clean code.
** Another technologies:** GitHub, GIT

## Tests

I didn't implement all unit tests, there are more unit tests on the packages (integrations). But there are a few important tests that could be checked.

Running the tests: `php vendor/bin/phpunit`

## Things to improve

1. Database: I don't think mysql is a problem, but I was searching about different approaches ant technologies, I found new digital banks that have a similar structure of the transaction tables. They are using a database called Datomic (https://www.datomic.com/nubanks-story.html)
It is really interesting.
2. Security
3. An account to store the Paxful Bitcoins (profit, bonus for first wallets...)
4. Helpers.

## License

It is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
