<?php
class Session{
        //proveri da li sesija postoji
	public static function exists($name){
		return (isset($_SESSION[$name])) ? true : false;
	}
        //postavi u sesiju
	public static function put($name, $value){
		return $_SESSION[$name] = $value;
	}
        //procitaj iz sesije
	public static function get($name){
		return $_SESSION[$name];
	}
        //unsetuj iz sesije
	public static function delete($name){
		if(self::exists($name)){
			unset($_SESSION[$name]);
		}
	}
        //ispis poruke korisniku koji se ispisuje i brise odmah
	public static function msg($name, $string = null){
		if (self::exists($name)){
			$session = self::get($name);
			self::delete($name);
			return $session;
		} else{
			self::put($name, $string);
		}
	}
}
?>