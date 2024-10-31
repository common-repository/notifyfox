<?php

class Model
{
    /**
     * @param object $db A PDO database connection
     */
    function __construct()
    {
         global $wpdb;
            try {
                $this->db = $wpdb;
                $this->nf_table_name = $this->db->prefix.'nf_pluginconfig';
            } catch (PDOException $e) {
               exit('Database connection could not be established.');
            }
    }

    public function nf_getData(){
            $nf_query = "SELECT * FROM $this->nf_table_name";
            return $this->db->get_row($nf_query);
    }


    public function nf_updateData($nf_c_apiusername,$nf_c_apipassword,$nf_c_client_id,$nf_c_client_secret,$nf_p_id,$nf_p_type){
            $nf_query = "UPDATE $this->nf_table_name SET nf_c_apiusername = '$nf_c_apiusername', 
                      nf_c_apipassword = '$nf_c_apipassword',nf_c_client_id = '$nf_c_client_id', 
                      nf_c_client_secret = '$nf_c_client_secret', nf_p_id = '$nf_p_id', 
                      nf_p_type = '$nf_p_type' 
                      WHERE nf_c_id = 1;";
           
            $this->db->query($nf_query);
            return true;
    }

    public function nf_insertData(){
            $nf_query = "INSERT INTO $this->nf_table_name (nf_c_id,nf_c_client_id,
                     nf_c_client_secret,nf_c_apiusername,
                     nf_c_apipassword,nf_p_id,nf_p_type)
                     VALUES (1,'','','','','',0);
                     ";
            $this->db->query($nf_query);
            return true;
    }

     public function nf_activatePlugin(){
          $nf_query = "DROP TABLE IF EXISTS $this->nf_table_name; CREATE TABLE $this->nf_table_name (
                            nf_c_id INT unsigned NOT NULL AUTO_INCREMENT,
                            nf_c_client_id VARCHAR(255) NULL,
                            nf_c_client_secret VARCHAR(255) NULL,
                            nf_c_apiusername VARCHAR(255) NULL,
                            nf_c_apipassword VARCHAR(255) NULL,
                            nf_p_id VARCHAR(255) NOT NULL,
                            nf_p_type TINYINT(3) NOT NULL DEFAULT '0',
                            PRIMARY KEY (nf_c_id)
                      );";
          $this->db->query($nf_query);
          return 1;
    }

    public function nf_deactivatePlugin(){
        $nf_query = "DROP TABLE IF EXISTS $this->nf_table_name;";
        $this->db->query($nf_query);
        return 1;
    }

    public function nf_checkTable(){
            if ($this->db->get_var("SHOW TABLES LIKE '$this->nf_table_name'") != $this->nf_table_name) {
                 //table not in database. Create new table
                $nf_query = "CREATE TABLE $this->nf_table_name (
                                  nf_c_id INT unsigned NOT NULL AUTO_INCREMENT,
                                  nf_c_client_id VARCHAR(255) NULL,
                                  nf_c_client_secret VARCHAR(255) NULL,
                                  nf_c_apiusername VARCHAR(255) NULL,
                                  nf_c_apipassword VARCHAR(255) NULL,
                                  nf_p_id VARCHAR(255) NOT NULL,
                                  nf_p_type TINYINT(3) NOT NULL DEFAULT '0',
                                  PRIMARY KEY (nf_c_id)
                            );";
                $this->db->query($nf_query);
                 return 1;
             } else
                 return 1;
    }
    public function nf_checkPlugin(){
        if($this->nf_checkTable()){
           if(empty($this->nf_getData())){
                $this->nf_insertData();    
           }
           return  $this->nf_getData();  
        }
    }
    
}
