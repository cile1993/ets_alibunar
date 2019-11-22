<?php
class Validate {
    private $_passed = false,
            $_errors = array(),
            $_db = null;
    
    // napravi konekciju na bazu ili se konektuj na postojecu
    public function __construct() {
        $this->_db = DB::getInstance();
    }
    
    // provera za sva input polja
    public function check($source, $items = array()) {
        //pristup prvom delu niza za proveru pravila
        foreach($items as $item => $rules) {
            //pristup drugom delu niza za proveru pravila
            foreach ($rules as $rule => $rule_value) {
                $value = $source[$item];
                $item = escape($item);
                //provera da li je polje obavezno i ispis greske ako je prazno
                if($rule === 'required' && empty($value)) {
                    $this->addError("{$item} je obavezno");
                } elseif (!empty ($value)) {
                    //provera ostalih pravila
                    switch ($rule) {
                        case $value:
                        case 'min' :
                            if (strlen($value) < $rule_value){
                                $this->addError("{$item} mora imati minimum {$rule_value} karaktera!");
                            }
                            break;
                        case 'max' :
                            if (strlen($value) > $rule_value){
                                $this->addError("{$item} mora imati najvise {$rule_value} karaktera!");
                            }
                            break;
                        case 'matches' :
                            if ($value != $source[$rule_value]){
                                $this->addError("{$rule_value} mora biti ista kao {$rule_value}");
                            }
                            break;
                        case 'unique' :
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if ($check->count()) {
                                $this->addError("{$item} je vec registrovan!");
                            }
                            break;

                        default:
                            break;
                    }
                }
            }
        }
        // ako je prazan error niz vrati true u suprotnom vrati errore
        if(empty($this->_errors)) {
            $this->_passed = true;
        }
        
        return $this;
        
    }
    
    private function addError($error) {
        $this->_errors[] = $error;
    }
    
    public function errors() {
        return $this->_errors;
    }
    
    public function passed() {
        return $this->_passed;
    }
    
}