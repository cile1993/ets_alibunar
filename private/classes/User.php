<?php
class User {
    private $_db,
            $_data,
            $_sessionName,
            $_isLoggedIn;
    
    public function __construct($user = null) {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        
        if(!$user) {
            if(Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                
                if($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    // logout
                }
            }
        } else {
            $this->find($user);
        }
    }
    
    //Metoda za kreiranje korisnika
    public function create($fields = array()) {
        if($this->_db->insert('ets_korisnici', $fields)) {
            echo 'Uspesno ste se registrovali';
        }
    }
    
    public function data() {
        return $this->_data;
    }
    
    //proverava da li je korisnik ulogovan
    public function isLoggedIn() {
		return $this->_isLoggedIn;
    }
    
    public function logout(){
		Session::delete($this->_sessionName);
		//Cookie::delete($this->_cookieName);
	}
    
    //proveri da li postoji korisnik
    public function find($user = null, $field = 'email') {
        if($user) {
            $data = $this->_db->get('ets_korisnici', array($field, '=', $user));
            
            if($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }
    
    //login provera podataka
    public function login($email = null, $password = null) {
        $user = $this->find($email, 'email');
        //da li postoji korisnik
        if($user) {
            //da li se poklapa lozinka
            if($this->data()->lozinka === Hash::make($password)){
                //Postavljanje sesije
                Session::put($this->_sessionName, $this->data()->korisnikID);
                return true;
            }
        } else {
            return false;
        }
    }

}