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
    return $router->app->version();
});

$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->post('/register', 'AuthController@register');

//Board Routes
$router->get('/boards', 'BoardController@index');
$router->get('/boards/{boardId}', 'BoardController@show');
$router->put('/boards/{boardId}', 'BoardController@update');
$router->delete('/boards/{boardId}', 'BoardController@destroy');
$router->post('/boards', 'BoardController@store');

//List Routes
$router->get('/boards/{boardId}/list', 'ListController@index');
$router->get('/boards/{board}/list/{list}', 'ListController@show');
$router->put('/boards/{boardId}/list/{listId}', 'ListController@update');
$router->delete('/boards/{boardId}/list/{listId}', 'ListController@destroy');
$router->post('/boards/{boardId}/list', 'ListController@store');

//Card Routes
$router->get('/boards/{boardId}/list/{listId}/card', 'CardController@index');
$router->get('/card/{cardId}', 'CardController@show');
$router->put('/card/{cardId}', 'CardController@update');
$router->put('/card/{cardId}/list/{listId}', 'CardController@updateList');
$router->delete('/card/{cardId}', 'CardController@destroy');
$router->post('/boards/{boardId}/list/{listId}/card', 'CardController@store');
$router->patch('/card/update-all', 'CardController@updateAll');

//Comment Routes
$router->get('/card/{cardId}/comment', 'CommentController@index');
$router->get('/comment/{commentId}', 'CommentController@show');
$router->put('/comment/{commentId}', 'CommentController@update');
$router->delete('/comment/{commentId}', 'CommentController@destroy');
$router->post('/card/{cardId}/comment', 'CommentController@store');
$router->patch('/card/update-all', 'CommentController@updateAll');

//get the user information
$router->get('/users','AuthController@userinfo');

