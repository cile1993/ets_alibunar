<?php
class Hash {
        //Kreiranje lozinke
	public static function make($string){
		return hash('sha256', $string . 'MqA9WPCw');
	}
	public static function unique(){
		return self::make(uniqid());
	}
}
?>