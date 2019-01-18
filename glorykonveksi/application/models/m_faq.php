<?php
class m_faq extends CI_Model{
 
    function simpan_faq($pertanyaan,$jawaban){
        $data = array(
                'pertanyaan' => $pertanyaan,
                'jawaban'		=> $jawaban
            );  
        $result= $this->db->insert('tbl_faq',$data);
        return $result;
    }
     
}