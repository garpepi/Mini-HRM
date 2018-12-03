<?php
class Client_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

	public function get_client($where = array())
    {
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('client');
        return $query->result_array();
    }
	public function get_client_name($id = '')
    {
        $this->db->select('*');
        $this->db->where(array('id' => $id));
        $query = $this->db->get('client');
        return $query->row_array();
    }
    public function get_div($where = array())
      {
          $this->db->select('*');
          $this->db->where($where);
          $query = $this->db->get('client');
          return $query->result_array();
      }
    public function insert_div($data = array())
      {
		$data['user_c'] = $this->session->userdata('logged_in_data')['id'];
		return $this->db->insert('client',array('name' => $data['name'], 'user_c'=> $data['user_c']));
      }

  	public function update_div($id = 0, $data = array())
      {
  		$data['user_m'] = $this->session->userdata('logged_in_data')['id'];
          $this->db->where(array('id' => $id));
          return$this->db->update('client',$data);
      }


}
