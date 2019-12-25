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
        foreach ($items as $item => $rules) {
            //pristup drugom delu niza za proveru pravila
            foreach ($rules as $rule => $rule_value) {
                $value = $source[$item];
                $item = escape($item);
                //provera da li je polje obavezno i ispis greske ako je prazno
                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$item} je obavezno");
                } elseif (!empty($value)) {
                    //provera ostalih pravila
                    switch ($rule) {
                        case $value:
                        case 'min' :
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$item} mora imati minimum {$rule_value} karaktera!");
                            }
                            break;
                        case 'max' :
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$item} mora imati najvise {$rule_value} karaktera!");
                            }
                            break;
                        case 'matches' :
                            if ($value != $source[$rule_value]) {
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
        if (empty($this->_errors)) {
            $this->_passed = true;
        }
        return $this;
    }

    // provera za upload slike
    public function checkImg($postSrc, $fileSrc, $widtha, $heighta, $size) {
        if (isset($postSrc)) {
            if (!empty($fileSrc["tmp_name"])) {
                $fileinfo = getimagesize($fileSrc["tmp_name"]);
                $width = $fileinfo[0];
                $height = $fileinfo[1];
                $ext = Config::get('avatar/file_type');

                $file_extension = pathinfo($fileSrc["name"], PATHINFO_EXTENSION);

                // proveri da li ima fajla
                if (!file_exists($fileSrc["tmp_name"])) {
                    echo "Izaberite sliku";
                    return false;
                }
                // proveri ekstenziju
                else if (!in_array($file_extension, $ext)) {
                    echo "Nedozvoljen format. Samo: jpg, png, gif";
                    return false;
                }    // proveri velicinu
                else if (($fileSrc["size"] > $size)) {
                    $sizea = $size / 1000 . ' KB';
                    echo "Velicina slike prelazi {$sizea} Kb";
                    return false;
                }    // proveri dimenzije
                else if ($width > $widtha || $height > $heighta ) {
                    echo "Dimenzije slike moraju biti do {$widtha} x {$heighta}";
                    return false;
                } else {
                    $target = "img/avatars/" . basename($fileSrc["name"]);
                    if (move_uploaded_file($fileSrc["tmp_name"], $target)) {
                        return true;
                    } else {
                        echo "Problem pri uploadu";
                        return false;
                    }
                }
            } else {
                return true;
            }
        }
    }

    public function checkTopic($topic) {
        if (empty($topic['naslov'])) {
            $this->addError('Naslov je obavezan');
        }

        $existingTopic = $this->_db->action('SELECT *', 'ets_blog', array('naslov', '=', $topic['naslov']));
        if ($existingTopic) {
            $this->addError('Naslov vec postoji');
        }

        if (empty($this->_errors)) {
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
