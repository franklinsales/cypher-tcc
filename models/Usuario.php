<?php
// Add this line
use LaravelBook\Ardent\Ardent;

class Usuario extends Ardent{

	// protected $table = 'usuarios';

	public $autoPurgeRedundantAttributes = true;
	public $timestamps = false;

	/**
	* Ardent validation rules
	*/
	public static $rules = array(
	  'nome' => 'required|between:4,16',
	  'email' => 'email'
	);


	

}


?>