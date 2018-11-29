<?php
class Config_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

	public function get_url($where = array())
    {
        $this->db->select('url');
        $this->db->where($where);
        $query = $this->db->get('config_api');
        return $query->row_array()['url'];
    }

	public function insert_config_qatracker($data = array())
    {
        $inserte = array(
				'app_id' => 1,// 1 = Qatracker
				'client_id' => $data['client'],
				'url' => 'http://'.$data['name'].'-qatracker.adi-internal.com'
			);
		return $this->db->insert('config_api',$inserte);
    }
}
