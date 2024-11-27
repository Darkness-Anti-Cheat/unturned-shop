<?php
include("db.php");

class db_queries extends db_connection
{
    public function insert($table, $values, $message = true) {
        try
        {
            $SQL = $this->odb -> prepare("INSERT INTO `" . $table . "` VALUES(" . implode(", ", array_keys($values)) . ")");
            if($message) 
            {
                echo "<div class='alert alert-success' role='alert'><b>$table</b> has been added...</div>";
            }

            return $SQL -> execute($values);
        }
        catch(PDOException $e)
        {
            if($message) 
            {
                echo "<div class='alert alert-danger' role='alert'>" . $e->getMessage()  . "</div>";
            }
            else
            {
                die($e->getMessage());
            }
        }
    }

    public function update($table, $rows, $values, $message = true) {
        try
        {
            $SQL = $this->odb -> prepare("UPDATE `" . $table . "` SET " . $rows . " WHERE " . str_replace(":", "" ,array_keys($values)[0]) . "=" . array_values($values)[0]);
            
            if($message) 
            {
                echo "<div class='alert alert-success' role='alert'><b>$table</b> has been updated...</div>";
            }

            return $SQL -> execute($values);
        }
        catch(PDOException $e)
        {
            if($message) 
            {
                echo "<div class='alert alert-danger' role='alert'>" . $e->getMessage()  . "</div>";
            }
            else
            {
                die($e->getMessage());
            }
        }
    }

    public function delete($table, $values, $message = true) {
        try
        {
            $SQL = $this->odb -> prepare("DELETE FROM " . $table . " WHERE " . str_replace(":", "" ,array_keys($values)[0]) . "='" . array_values($values)[0] . "'");
            return $SQL -> execute($values);
            if($message) 
            {
                echo "<div class='alert alert-success' role='alert'><b>$table</b> has been deleted...</div>";
            }
        }
        catch(PDOException $e)
        {
            if($message) 
            {
                echo "<div class='alert alert-danger' role='alert'>" . $e->getMessage()  . "</div>";
            }
            else
            {
                die($e->getMessage());
            }
        }
    }

    public function custom_select($syntax = "") 
	{
		parent::__construct();
        try
        {
            $SQL = $this->odb->query($syntax);
            return $SQL;
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }

    public function select($table, $syntax = "") {
        try
        {
            $SQL = $this->odb -> query("SELECT * FROM `" . $table . "` " . $syntax);
            return $SQL;
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }

    function __destruct() 
    {
        return $this->odb = null;    
    }
}

$queries = new db_queries();
