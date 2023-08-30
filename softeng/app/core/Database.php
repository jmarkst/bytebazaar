<?php
/**
* Database.php
* Naglalaman ng Database trait.
* Para sa pag-connect sa database.
**/

trait Database {
    /**
    * Database trait
    * Main trait definition para sa database-related functions.
    **/
    private function connect() {
        /**
        * connect():
        * Iko-connect sa database.
        * Return:
        * - $conn: yung PDO connection.
        **/
        $string = 'mysql:hostname='.DBHOST.';dbname='.DBNAME;
        $conn = new PDO($string, DBUSER, DBPASS);
        return $conn;
    }

    public function getRow($query, $data = []) {
        /**
        * getRow($query, $data):
        * - Run ng single row sql operation.
        * - Halos katulad ng query(), pero first index lang ang ire-return.
        * Parameters:
        * - $query: yung SQL query na papatakbuhin.
        * - $data: array ng data na ipapasa sa SQL query.
        * Returns:
        * - $res: (success) yung first index lang ng array of results.
        * - (fail): false (ie. may error)
        **/

        # connect sa db
        $conn = $this->connect();
        # prepare yung sql query
        $sql = $conn->prepare($query);
        # execute yung query, check kung ok.
        $chk = $sql->execute($data);
        if ($chk) {
            # kung meron, kunin natin yung results.
            $res = $sql->fetchAll(PDO::FETCH_OBJ);
            if (is_array($res) && count($res)) {
                # kung may laman yung results (success)
                return $res[0];
            }
        }
        # may error.
        return false;
    }

    public function lastInsertId() {
        $conn = $this->connect();
        return $conn->lastInsertId();
    }

    public function query($query, $data = []) {
        /**
        * query($query, $data):
        * - Run ng isang kumpletong sql query.
        * Parameters:
        * - $query: yung SQL query na papatakbuhin.
        * - $data: array ng data na ipapasa sa SQL query.
        * Returns:
        * - $res: (success) array ng mga results
        * - (fail): false (ie. may error)
        **/

        # connect sa db
        $conn = $this->connect();
        # prepare yung sql query
        $sql = $conn->prepare($query);
        # execute yung query, check kung ok.
        $chk = $sql->execute($data);
        $id = $conn->lastInsertId();
        if ($chk) {
            # kung meron, kunin natin yung results.
            $res = $sql->fetchAll(PDO::FETCH_OBJ);
            if (is_array($res) && count($res)) {
                # kung may laman yung results (success)
                return $res;
            }
        }
        # may error.
        //show($id);
        return $id;
    }
}