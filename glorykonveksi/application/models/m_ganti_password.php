<?php
class m_ganti_password extends CI_Model{
 
    function simpan_password($password,$new_password){
        $data = array(
                'password' => $password,
                'new_password'   => $new_password
            );  
        $result= $this->db->insert('rec_user_web',$data);
        return $result;
    }
     
}