<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

/**
 * Rest API
 * 
 * @package     CI
 * @subpackage  Controller
 * @author      wilron8@gmail.com
 * 
 */

class Api extends RestController {

    public $header;
    
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();  

        // Get the headers      
        $this->header = getallheaders();

        // Load the models
        $this->load->model(['Customer_model','Users_auth_model']);

        // Check if token is present in header
        if( !isset($this->header['token']) ){
            $this->response( [
                    'status' => false,
                    'message' => 'Invalid API key.'
                ], 403 );

        }

        // Get the existing token in our db
        $data['users_id'] = 1;
        $user_auth = $this->Users_auth_model->checkRecord($data);

        // Check if token from header is match in our db 
        if( $user_auth ){

            if( $user_auth->token !== $this->header['token'] ){
                $this->response( [
                    'status' => false,
                    'message' => 'API key is not matched.'
                ], 400 );
            }
        }

         
    }

    /**
     * Get endpoint
     * 
     * @return void
     * 
     */

    public function index_get()
    {
        $id = $this->get('id');   

        if ( $id === null ) {
            $customers = $this->Customer_model->showRecords();
            // Customers data

            if ( $customers ){                
                $this->response( $customers, 200 );
            } else {                
                $this->response( [
                    'status' => false,
                    'message' => 'No customers found.'
                ], 404 );
            }

        } else {
            $datas['id'] = $id;
            $get_customer = $this->Customer_model->checkRecord($datas);

            if ( $get_customer ) {
                $this->response( $get_customer, 200 );

            } else {
                $this->response( [
                    'status' => false,
                    'message' => 'No customer found.'
                ], 404 );

            }
        }        

    }

    /**
     * Post endpoint
     * 
     * @return void
     * 
     */

    public function index_post()
    {
       
        $post_data = $this->post();
        $datas = [];

        // Need to sure that we are passing a data
        if( count($post_data) > 0 ){

            // Loop the data
            foreach ($post_data as $key => $val) {
                // Field id is auto increment, we need to remove it
                if( $key != 'id' ){
                    $datas[$key] = $val;
                }               
            }

            $insert_customer = $this->Customer_model->insertRecord($datas);

            if( $insert_customer ){
                
                $datas['id'] = $insert_customer;
                $get_customer = $this->Customer_model->checkRecord($datas);
               
                $salesID = $get_customer->id.$get_customer->firstName.$get_customer->lastName.$get_customer->creationDate;
                $dt['salesID'] = $salesID;
                $where_fields = ['id' => $datas['id'] ];

                $this->Customer_model->updateRecord($dt, $where_fields);

                $this->response( [
                        'status' => true,
                        'message' => 'Customer Added Successfully.'
                ], 200 );
              

            } else {

                 // Show error if no request
                $this->response( [
                    'status' => false,
                    'message' => 'There was a problem when trying to insert record.'
                ], 400 );
            }
            

        } else {

            // Show error if no request
            $this->response( [
                    'status' => false,
                    'message' => 'Invalid request.'
                ], 400 );
        }
      
         

    }


    /**
     * Put endpoint
     * 
     * @return void
     * 
     */

    public function index_put()
    {
        
        $put_data = $this->put();
        $id = $this->put('id');
        $datas = [];

        if( $id === null ){

            $this->response( [
                    'status' => false,
                    'message' => 'Missing required parameter id.'
                ], 400 );

        }       
        

        // Need to sure that we are passing a data
        if( count($put_data) > 0 ){

            // Loop the data
            foreach ($put_data as $key => $val) {
                // Field id is auto increment, we need to remove it
                if( $key != 'id' ){
                    $datas[$key] = $val;
                }               
            }

            $dt = $datas;
            $where_fields = ['id' => $id ];
            $update_customer = $this->Customer_model->updateRecord($dt, $where_fields);

            if($update_customer){

                $this->response( [
                        'status' => true,
                        'message' => 'Customer id #'.$id.' Updated Successfully.'
                ], 200 );
            } else {
                // Show error if no request
                $this->response( [
                    'status' => false,
                    'message' => 'There was a problem when trying to update record.'
                ], 400 );
            }            

        } else {

            // Show error if no request
            $this->response( [
                    'status' => false,
                    'message' => 'Invalid request.'
                ], 400 );
        }

    }

    /**
     * Delete endpoint
     * 
     * @return void
     * 
     */

    public function index_delete()
    {

        $id = $this->delete('id');
        $datas = [];

        if($id === null){
            $this->response( [
                    'status' => false,
                    'message' => 'Missing required parameter id.'
                ], 400 );
        }

        $where_fields = ['id' => $id ];
        $delete_customer = $this->Customer_model->deleteRecord($where_fields);

        if($delete_customer){

            $this->response( [
                        'status' => true,
                        'message' => 'Customer id #'.$id.' deleted Successfully.'
                ], 200 );
        } else {
                // Show error if no request
            $this->response( [
                    'status' => false,
                    'message' => 'There was a problem when trying to delete record.'
                ], 400 );
        } 

    }

}