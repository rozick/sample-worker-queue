<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
   echo '<h1 style="text-align:center">
   Welcome to the Queue... 
   </h1>';
});

/**
 * Send our message
 * @params 
 * var string data; enum message_type, category_type
 */
$router->post('/send-message', 'MessageController@sendMessage');

/**
 * Reserve our message
 * @params 
 * var enum message_type, category; string reserver name (required)
 */
$router->put('/reserve-message', 'MessageController@reserveMessage');

/**
 * Delete our message
 * @params 
 * var integer message_id
 */
$router->delete('/delete-message/{message_id}', 'MessageController@deleteMessage');

