<?php    
class Model_xls extends CI_Model{
    function export_mobil(){
        $query = $this->db->query("SELECT * FROM tbl_mobil");
         
        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }
}