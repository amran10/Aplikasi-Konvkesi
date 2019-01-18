<?php
class m_upload extends CI_Model{
 
    function simpan_upload($keterangan,$foto,$set_foto){
        $data = array(
                'keterangan' => $keterangan,
                'foto'		=> $foto,
                'set_foto' => $set_foto
            );  
        $result= $this->db->insert('tbl_galery',$data);
        return $result;
    }
     
}
