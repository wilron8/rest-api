<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customer
 * 
 * @package     CI
 * @subpackage  Model
 * @author      wilron8@gmail.com
 * 
 */

class Customer_model extends CI_Model {
		
	public $table_name = 'customers';

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

	/**
     * Create record
     *
     * @param array $data - dynamic sets of keys and values 
     * 
     * @return integer/boolean
     * 
     */		

    public function insertRecord($data) {
		$result = $this->db->insert($this->table_name, $data);
		return ($result) ? $this->db->insert_id() : FALSE;
   	}

   	/**
     * Modify record
     *
     * @param array $data - dynamic sets of keys and values 
     * @param array $whereflds - contains array key fields and values
     * 
     * @return boolean
     * 
     */	

   	public function updateRecord($data, $whereflds) {
   		$check_rec = $this->db->get_where($this->table_name, $whereflds);		
		if($check_rec->num_rows() > 0){
			$this->db->where($whereflds);
			$result = $this->db->update($this->table_name, $data);
			return $result ? TRUE : FALSE;
		} else {
			return FALSE;
		}
	}

	/**
     * Delete record
     *
     * @param array $data - dynamic sets of keys and values 
     * 
     * @return boolean
     * 
     */
				
	public function deleteRecord($data){

		$check_rec = $this->db->get_where($this->table_name, $data);		
		if($check_rec->num_rows() > 0){
			$result = $this->db->delete($this->table_name, $data);
			return $result ? TRUE : FALSE;
		} else {
			return FALSE;
		}
	}

	/**
     * Show all records
     *
     * @param string $fieldnames - list of column names in a table
     * @param string $wherefields - list of conditions that will filter the records
     * @param string $orderby - Sorting by field in either ASC or DESC
     * 
     * @return array/boolean
     * 
     */	

	public function showRecords($fieldnames = "*",$wherefields = "",$orderby = ""){
						
		$query = $this->db->query("SELECT $fieldnames FROM ".$this->table_name." $wherefields $orderby;");
		if($query->num_rows()>0){
			foreach ($query->result() as $row):
				$data[] = $row;
			endforeach;	
			return $data;
		} else {
			return FALSE;
		}
		
	}

}