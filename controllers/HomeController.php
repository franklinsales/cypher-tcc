<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}

	public function usuario($nome = null){
		$user = new Usuario;
  		$user->nome = $nome;
  		$erros = $user->validate();
  		if($erros)	{
  			$user->save();
  			return View::make('hello')->with('nome', $nome);
  		}
  		else{
  			echo "Erro";
  		}
	}

}
