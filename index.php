<?php

class DB
{
    public $conn = null;
     public $result=[];
    private $user; 
    private $host;
    private $pass;
    private $db;
    
    public function __construct()
    {
        $this->user = "root";
        $this->host = "localhost";
        $this->pass = "";
        $this->db = "classes";

        $this->conn = new mysqli($this->host,$this->user,$this->pass,$this->db);

        if($this->conn->connect_error){
            echo "Failed to connect to MySQL: " . $this->conn->connect_error;
            return $this->conn;
        }
    }

    public function insert($table,$params=array())
    {
        // Check to see if the table exists
        if($this->tableExists($table))
        {
        // Seperate $params's Array KEYs and VALUEs and Convert them to String Value
            $table_columns = implode(', ', array_keys($params));
            $table_value = implode("', '", $params);
  
            $sql = "INSERT INTO $table ($table_columns) VALUES ('$table_value')";
            // Make the query to insert to the database
            if($this->conn->query($sql))
            {
                array_push($this->result, $this->conn->insert_id);
                return true; // The data has been inserted
            }
            else
            {
                array_push($this->result, $this->conn->error);
                return false; // The data has not been inserted
            }
  
        }
        else
        {
            return false; // Table does not exist
        }
    }
    public function sqlSelectQuery($sql)
    {
        $query = $this->conn->query($sql);
        if($query)
        {
            $this->result = $query->fetch_all(MYSQLI_ASSOC);
            return true; // Query was successful
        }
        else
        {
        array_push($this->result, $this->conn->error);
        return false; // No rows were returned
        }
    }

    public function select($table,$row='*',$join = null,$where = null,$order=null,$limit=null)
    {

        if($this->tableExists($table))
        {
        // Create query from the variables passed to the function
          $sql = "SELECT $row FROM $table";
            if($join != null)
            {
              echo $sql .= " JOIN $join";  
            }
            if($where != null)
            {
              echo $sql .= " WHERE $where";  
            }
            if($order != null)
            {
              $sql .= " ORDER BY $order";  
            }
            if($limit != null)
            {
              $sql .= " LIMIT $limit";  
            }

            $query = $this->conn->query($sql);
            if($query)
            {
                $this->result = $query->fetch_all(MYSQLI_ASSOC);
                return true; // Query was successful
            }
            else
            {
                array_push($this->result, $this->conn->error);
                return false; // No rows were returned
            }

        }


    }
 
    public function update($table, $params=[],$where=null) // if you don't use where the database table will be update columns; 
    {
        if($this->tableExists($table))
        {
            // Create Array to hold all the columns to update
            $args=[];
            foreach($params as $key => $values)
            {
                $args[] = "$key = '$values'"; // Seperate each column out with it's corresponding value 
            }
            
           echo $sql = "UPDATE `$table` SET " . implode(', ',$args);
            if($where != null)
            {
                $sql .= " WHERE $where";
            }
              // Make query to database
            if($this->conn->query($sql))
            {
                array_push($this->result,$this->conn->affected_rows);
                return true;
            }
            else
            {
                array_push($this->result,$this->conn->error);
                return false;
            }

        }


    }

    public function delete($table,$where=null)
    {

        if($this->tableExists($table))
        {

            $sql ="DELETE FROM `$table`";
            if($where != null)
            {
                $sql .=" WHERE $where";  
            }
            if($this->conn->query($sql))
            {
                array_push($this->result,$this->conn->affected_rows);
                return true;
            }
            else
            {
                array_push($this->result,$this->conn->error);
                return false;
            }
        }

    }
    
    public function tableExists($table)
    { 
        $sql = "SHOW TABLES FROM $this->db LIKE '$table'";
    
        $tbaleshow = $this->conn->query($sql);
        if($tbaleshow)
        {
            return true;
        }
        else
        {
            array_push($this->result,$table." does not exist in this database.");
            return false;
        }
    }

    // Public function to return the data to the user
    public function getResult()
    {
        $val = $this->result;
        $this->result = array();
        return $val;
    }

}


?>