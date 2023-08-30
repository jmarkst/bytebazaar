<?php
/**
* Model.php
* Naglalaman ng Model trait
**/

trait Model {
    /**
    * Model trait
    * Main definition trait para sa mga model.
    * Naglalaman ng mga CRUD sql operations.
    **/
    # gamitin yung Database trait.
    use Database;

    protected $limit = 10;
    protected $offset = 0;
    protected $order = "ASC";
    protected $orderCol = "id";
    protected $errors = [];

    public function findAll($data = []) {
        /**
        * findAll():
        * - sql SELECT  ALL operation
        * Parameters:
        * - $data: array ng data na ipapasa, default ay []
        **/

        # gawa ng string na gagamitin sa query string.
        $sql = "SELECT * FROM $this->table ORDER BY $this->orderCol $this->order LIMIT $this->limit OFFSET $this->offset";
        return $this->query($sql, $data);
    }
    
    public function findAllNoLimit($data = []) {
        $sql = "SELECT * FROM $this->table ORDER BY $this->orderCol $this->order";
        return $this->query($sql, $data); 
    }

    public function getCount() {
        $sql = "SELECT * FROM $this->table";
        try {
            return count($this->query($sql));
        } catch (TypeError $e) {
            return 0;
        }
    }
    
    public function search($col, $stri, $data = []) {
        $sql = "SELECT * FROM $this->table WHERE $col LIKE '%$stri%'";
        return $this->query($sql, $data);
    }

    public function setOrder($arrange, $col) {
        $this->order = $arrange;
        $this->orderCol = $col;
    }

    public function setOffset($off) {
        $this->offset = $off;
    }

    public function getLastRowId() {
        return $this->lastInsertId();
    }

    public function where($data, $dataNot = []) {
        /**
        * where($data, $dataNot):
        * - sql WHERE operation
        * Parameters:
        * - $data: yung array ng data na gagamitin.
        * - $dataNot: yung array ng data na HINDI gagamitin.
        * Returns:
        * - $res: query results, naka-array.
        **/
        # kunin natin yung mga keys ng array.
        $keys = array_keys($data);
        $nkeys = array_keys($dataNot);

        # gawa ng string na gagamitin sa query string.
        # pinagdikit-dikit na mga key:value pairs.
        $sql = "SELECT * FROM $this->table WHERE ";
        foreach ($keys as $key) {
            $sql .= $key." = :".$key." && ";
        }
        foreach ($nkeys as $key) {
            $sql .= $key." != :".$key." && ";
        }

        # tanggalin yung dangling &&
        $sql = trim($sql, " && ");
        # isama yung limit at offset sa query string.
        $sql .= " ORDER BY $this->orderCol $this->order LIMIT $this->limit OFFSET $this->offset";
        # i-merge yung data at dataNot.
        $data = array_merge($data, $dataNot);

        return $this->query($sql, $data);
    }

    public function first($data, $dataNot = []) {
        /**
        * first($data, $dataNot):
        * - sql WHERE operation, except ire-return lang yung una.
        * Parameters:
        * - $data: yung array ng data na gagamitin.
        * - $dataNot: yung array ng data na HINDI gagamitin.
        * Returns:
        * - $res: (success) query results, naka-array.
        * - (fail): false (ie. may error)
        **/

        # kunin natin yung mga keys ng array.
        $keys = array_keys($data);
        $nkeys = array_keys($dataNot);

        # gawa ng string na gagamitin sa query string.
        # pinagdikit-dikit na mga key:value pairs.
        $sql = "SELECT * FROM $this->table WHERE ";
        foreach ($keys as $key) {
            $sql .= $key." = :".$key." && ";
        }
        foreach ($nkeys as $key) {
            $sql .= $key." != :".$key." && ";
        }

        # tanggalin yung dangling &&.
        $sql = trim($sql, " && ");
        # isama yung limit, offset, at order sa query string.
        $sql .= " ORDER BY $this->orderCol $this->order LIMIT $this->limit OFFSET $this->offset";
        # i-merge yung data at dataNot.
        $data = array_merge($data, $dataNot);
        $res = $this->query($sql, $data);
        if ($res) {
            # return yung unang item sa $res.
            return $res[0];
        }
        return false;
    }

    public function insert($data, $retId = 0) {
        /**
        * insert($data):
        * - sql INSERT operation.
        * Parameters:
        * - $data: yung array ng mga data na ii-insert.
        **/

        # tanggalin muna yung mga pinagbabawalang magalaw na column. (2:40:36)
        if (!empty($this->allowedCols)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedCols)) {
                    # kung wala sa allowedCols, tanggalin yon.
                    unset($data[$key]);
                }
            }
        }

        # kunin yung mga keys ng array.
        $keys = array_keys($data);
        # implode yung mga keys ng array para mailagay sa insert statement.
        $sql = "INSERT INTO $this->table (".implode(", ", $keys).") VALUES (:".implode(", :", $keys).")";
        # run yung query.
        $res = $this->query($sql, $data);
        //show("How ".$res);
        return $res;
    }

    public function update($id, $data, $col = "id") {
        /**
        * update($id, $data, $col):
        * - sql UPDATE statement.
        * Parameters:
        * - $id: yung id ng ia-update.
        * - $data: yung array ng data na ilalagay sa update statement.
        * - $col: yung column na ia-update (default ay id)
        **/

        # tanggalin muna yung mga pinagbabawalang magalaw na column. (2:40:36)
        if (!empty($this->allowedCols)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedCols)) {
                    # kung wala sa allowedCols, tanggalin yon.
                    unset($data[$key]);
                }
            }
        }

        # kunin yung array keys
        $keys = array_keys($data);
        # panimulang sql statement.
        $sql = "UPDATE $this->table SET ";

        # concat yung key: value pair sa sql statement.
        foreach ($keys as $key) {
            $sql .= $key." = :".$key.", ";
        }

        # tanggalin yung dangling comma sa dulo.
        $sql = trim($sql, ", ");
        # iconcat yung dulong part ng statement.
        $sql .= " WHERE $col = :$col";
        # ilagay natin yung $col=$id sa $data.
        $data[$col] = $id;
        # run yung query.
        $res = $this->query($sql, $data);
        return $res;
    }

    public function delete($id, $col = "id") {
        /**
        * delete($id, $col):
        * - sql DELETE statement.
        * Parameters:
        * - $id: yung id (o yung value ng col) na target burahin.
        * - $col: yung column na ita-target (default ay id).
        **/

        # yung data na ipapasa sa query.
        $data[$col] = $id;
        # yung sql statement.
        $sql = "DELETE FROM $this->table WHERE $col = :$col";
        # run yung query.
        $res = $this->query($sql, $data);
        return false;
    }
}