<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Users Authentication
 * 
 * @package     CI
 * @subpackage  Model
 * @author      wilron8@gmail.com
 * 
 */
class Users_auth_model extends CI_Model {
		
	public $table_name = 'users_authentication';

	public function __construct() {
  
	    parent::__construct();

	}

	/**
     * Get record
     *
     * @param array $data - dynamic sets of keys and values 
     * 
     * @return array/boolean
     * 
     */
	public function checkRecord($data) {
		$query = $this->db->get_where($this->table_name, $data);
		$result = ($query->num_rows() > 0) ? $query->row() : FALSE;  
		return $result;
		$query->free_result();
	}

}