<?php

class User {

    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn;

    public function __construct($user = null) {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    // logout
                }
            }
        } else {
            $this->find($user);
        }
    }

    // apdejt profila
    public function update($fields = array(), $id = null) {
        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()->korisnikID;
        }
        if (!$this->_db->update('ets_korisnici', $id, $fields)) {
            throw new Exception('There was a problem updating your details.');
        }
    }

    //Metoda za kreiranje korisnika
    public function create($fields = array()) {
        if ($this->_db->insert('ets_korisnici', $fields)) {
            echo 'Uspesno ste se registrovali';
        }
    }
    
    //provera dozvola kroz json
    public function hasPermission($key) {
        $group = $this->_db->get('ets_grupe', array('grupeID', '=', $this->data()->pristup));
        
        if($group->count()) {
            $permissions = json_decode($group->first()->pristup, true);
            
            if($permissions[$key] == true) {
                return true;
            }
        }
        return false;
    }

    public function data() {
        return $this->_data;
    }

    public function exists() {
        return (!empty($this->_data)) ? true : false;
    }

    //proverava da li je korisnik ulogovan
    public function isLoggedIn() {
        return $this->_isLoggedIn;
    }

    //unset sesije i brisanje cookija
    public function logout() {
        $this->_db->delete('ets_sesije', array('korisnikID', '=', $this->data()->korisnikID));

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    //proveri da li postoji korisnik
    public function find($user = null, $field = 'email') {
        if ($user) {
            $field = (is_numeric($user)) ? 'korisnikID' : 'email';
            $data = $this->_db->get('ets_korisnici', array($field, '=', $user));

            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    //login provera podataka
    public function login($email = null, $password = null, $remember = false) {

        // ako korisnik ima cookie a ne sesiju postavi mu sesiju
        if (!$email && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->korisnikID);
        } else {
            $user = $this->find($email, 'email');


            //da li postoji korisnik
            if ($user) {
                //da li se poklapa lozinka
                if ($this->data()->lozinka === Hash::make($password)) {
                    //Postavljanje sesije
                    Session::put($this->_sessionName, $this->data()->korisnikID);

                    if ($remember) {
                        //proveri da li ima setovane sesije u bazi
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->get('ets_sesije', array('korisnikID', '=', $this->data()->korisnikID));
                        //ako nema insertuj u bazu
                        if (!$hashCheck->count()) {
                            $this->_db->insert('ets_sesije', array(
                                'korisnikID' => $this->data()->korisnikID,
                                'hash' => $hash
                            ));
                        }
                        //ako ima uzmi rezultat iz tabele
                        else {
                            $hash = $hashCheck->first()->hash;
                        }
                        //postavi mu cookie pri logovanju
                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }

                    return true;
                }
            }
        }
        return false;
    }

}
