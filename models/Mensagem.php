<?php
// Add this line
use LaravelBook\Ardent\Ardent;

class Mensagem extends Ardent{

	protected $table = 'mensagens';
	
	// protected $fillable = array();
	// protected $guarded = array();
	public $autoPurgeRedundantAttributes = true;
	public $timestamps = false;


	/**
	* Ardent validation rules
	*/
	// public static $rules = array(
	// 	'senha' => 'numeric'
	// );

}


?>