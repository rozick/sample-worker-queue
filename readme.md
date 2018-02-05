## Sample-Worker-Queue System

Sample queue system based on mysql and using workers (for this demonstration one). The main files here are inside inside the app directory. 

Modules/QueueClass.php, Modules/Worker.php, and Http/Controllers/MessageController.php, and Console/Commands/CreateWorkers.php

The routes are in routes/web.php.

Using the lightweight lumen framework (based off symfony components) to provide routing, ORM, and creating console commands.

To run the worker. Type `php artisan rafi:workers` in the console. To stop the worker, just stop the command inside the console.

For rest api. Post to `localhost/send-message` using something like cURL or Postman with strings data, message_type, and category_type. 

Put to `localhost/reserve-message` with strings message_type, category, and reserver name(required).

Delete to `localhost/delete-message/[message_id]`. Message id in the URL.

Make sure to use `composer install` to get lumen/symfony dependencies.
