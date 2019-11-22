<?php

// objekat za databazu
class DB {

    private static $_instance = null;
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0;

    // pravljenje konekcije iz niza u init.php da bi se pokretao an svakoj stranici
    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/dbname'), Config::get('mysql/username'), Config::get('mysql/password'));
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // pozivanje instance klase ako nije setovana, u suprotnom vraca setovani objekat
    // tako da nikada ne mozemo imati vise istovremenih konekcija na bazu iz skripte
    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    
    //query funkcija koja ce pozvati sql query sa bind parametrima kroz niz
    //query("SELECT * FROM ets_korisnici WHERE ime = ? and prezime = ?", array('Dejan', 'Cicovic'));
    public function query($sql, $params = array()) {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }

            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }

    // funkcija za query nad bazom
    // DB::getInstance()->action('select *', 'ets_korisnici', ['ime', '=', 'Dejan']);
    public function action($action, $table, $where = array()) {
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }

    // DB::getInstance()->get('ets_korisnici', array('username', '=', 'ime'));
    public function get($table, $where) {
        return $this->action('SELECT *', $table, $where);
    }

    //delete funkcija
    public function delete($table, $where) {
        return $this->action('DELETE', $table, $where);
    }

    //DB::getInstance()->insert('users', array('ime' => 'Dejan', 'prezime' => 'Cicovic'));
    public function insert($table, $fields = array()) {
        $keys = array_keys($fields);
        $values = '';
        $x = 1;

        foreach ($fields as $field) {
            $values .= '?';
            if ($x < count($fields)) {
                $values .= ', ';
            }
            $x++;
        }

        $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    //DB::getInstance()->update('korisnici', 'korisnikID', 2, array('ime' => 'Dejan', 'prezime' => 'Cicovic'));
    public function update($table, $id, $fields) {
        $set = '';
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";
            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    //rezultati querija
    public function results() {
        return $this->_results;
    }

    //prvi rezultat
    public function first() {
        return $this->results()[0];
    }

    //error funkcija
    public function error() {
        return $this->_error;
    }

    //provera da li ima rez u query
    public function count() {
        return $this->_count;
    }

}

?>