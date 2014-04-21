<?php
// Add this line
use LaravelBook\Ardent\Ardent;

class Conversa extends Ardent{
	protected $fillable = array('saltConversa', 'senha', 'dataCriacao', 'status');
	protected $guarded = array('checkSenha');





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