<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});


Route::post('/conversa/add', 'ConversaController@criarConversa');

Route::get('/conversa/add', function(){
	return View::make('criarConversa');
});

Route::post('/conversa/login/{tokenConversa}', 'ConversaController@acessarConversa');

Route::post('/mensagem/enviar/{tokenConversa}', 'ConversaController@enviarMensagem');

ROute::post('/conversa/mensagens/{tokenConversa}', 'ConversaController@listarMensagens');

Route::get('/conversa/login/{idConversa}', 'ConversaController@loginConversa');

// Route::get('/usuario/{nome}', 'HomeController@usuario');

// Route::get('/user', function()
// {
//   $user = new User;
//   $user->username = 'philipbrown';
//   // $user->email = 'philipbrown.com';
//   $user->password = 'deadgiveaway';
//   $user->password_confirmation = 'deadgiveaway';
//   var_dump($user->save());
// });