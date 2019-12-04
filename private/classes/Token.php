<?php
class Token{
        //generisanje tokena
	public static function generate(){
		return Session::put(Config::get('session/token_name'), md5(uniqid()));
	}
        
        //generisanje tokena za reset
        public static function tokenMake(){
            return md5(uniqid());
        }
        
        //provera da li se token sa forma poklapa sa onim iz sesije
	public static function check($token){
		$tokenName = Config::get('session/token_name');
		if(Session::exists($tokenName) && $token === Session::get($tokenName)){
			Session::delete($tokenName);
			return true;
		}
		return false;
	}
}
?>