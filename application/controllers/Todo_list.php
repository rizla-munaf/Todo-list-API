<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Todo_list extends CI_Controller {

	 public function __construct() {
            
            parent::__construct();
            
            $this->load->helper('url');
            $this->load->model('db_model');
            date_default_timezone_set('Asia/Colombo');
    }


	public function login(){
	
	//Login function. 

		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad Request!'));
		} 
		else {

			//Authenticate based on header data $client_service and $auth_key

			$check_auth_client = $this->db_model->check_auth_client();
			
			if($check_auth_client == true){
				
				//$params = $_REQUEST;
		         $params=json_decode(file_get_contents('php://input'), TRUE);
		        
		        $username = $params['username'];
		        $password = $params['password'];

		        	
		        $response = $this->db_model->login($username,$password);
			
				json_output($response['status'],$response);
			}

		}
	}

	public function logout(){

   //Logout function. 

		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'POST'){

			json_output(400,array('status' => 400,'message' => 'Bad request.'));

		} 
		else {

			//Authenticate based on header data $client_service and $auth_key
			$check_auth_client = $this->db_model->check_auth_client();

			if($check_auth_client == true){

		        $response = $this->db_model->logout();
				json_output($response['status'],$response);

			}
		}
	}


	public function get_all_tasks(){

	//function to retrieve all tasks

		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} 
		else {

			//Authenticate based on header data $client_service and $auth_key
			$check_auth_client = $this->db_model->check_auth_client();
			
			if($check_auth_client == true){
				
				//check if the token expired
		        $response = $this->db_model->auth();
		       
		        if($response['status'] == 200){
		        	//pass null to get all task details. Pass ID to get specific task details
		        	$resp = $this->db_model->get_all_tasks('');

		        	if($resp!=NULL){

	    				json_output($response['status'],$resp);

	    			}
		    		else{

		    			json_output($response['status'],'No Tasks Available');
		    		}
		        }
			}
		}
	}




	public function get_task_by_id($id){

		//function to retrieve details of particular task selected by ID

		$method = $_SERVER['REQUEST_METHOD'];

		//Check whether the id is passed in the URL. 
		if($method != 'GET' || $this->uri->segment(2) == '' || is_numeric($this->uri->segment(2)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} 
		else {

			//Authenticate based on header data $client_service and $auth_key
			$check_auth_client = $this->db_model->check_auth_client();

			if($check_auth_client == true){

				//check if the token expired
		        $response = $this->db_model->auth();

		        if($response['status'] == 200){

		        	//pass id to the function to get Task by ID, Pass Null to retrive all task details
		        	$resp = $this->db_model->get_all_tasks($id);

					if($resp!=NULL){

	    				json_output($response['status'],$resp);

	    			}
		    		else{
		    			
		    			json_output($response['status'],'No Task Available on That ID');
		    		}
		        }
		        else{
		        	json_output($response['status'],$response['message']);
		        }
			}
		}
	}


	public function create_task(){

		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} 
		else {

			//Authenticate based on header data $client_service and $auth_key
			$check_auth_client = $this->db_model->check_auth_client();

			if($check_auth_client == true){

				//check if the token expired
		        $response = $this->db_model->auth();

		        $respStatus = $response['status'];

		        if($response['status'] == 200){

		        	//obtain user input through form data
		        	$task_name=$this->input->post('task_name');
		        	$status=$this->input->post('status');
					
					
					if ($task_name == "" || $status == "") {
						$respStatus = 400;
						$resp = array('status' => 400,'message' =>  'Task and Status must be filled!');
					} 
					else {
		        		$resp = $this->db_model->create_task($task_name,$status);
					}
					json_output($respStatus,$resp);
		        }
			}
		}
	}

	public function update_task($id){

    //function used only to change task name

		$method = $_SERVER['REQUEST_METHOD'];

		//Check whether the id is passed in the URL. 
		if($method != 'PUT' || $this->uri->segment(2) == '' || is_numeric($this->uri->segment(2)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} 
		else {

			//Authenticate based on header data $client_service and $auth_key
			$check_auth_client = $this->db_model->check_auth_client();
			
			if($check_auth_client == true){

				//check if the token expired
		        $response = $this->db_model->auth();

		        $respStatus = $response['status'];

		        if($response['status'] == 200){

		        //input obtained from user as JSON 

		        $data=json_decode(file_get_contents('php://input'), TRUE);

					$task_name=$data['task_name'];
		        	//$status=$this->input->post('status');
					$updated_on = date('Y-m-d H:i:s');

					if ($task_name == "") {
						$respStatus = 400;
						$resp = array('status' => 400,'message' =>  'Task Name must be filled!');
					} else {
		        		$resp = $this->db_model->update_task($id,$task_name,$updated_on);
					}
					json_output($respStatus,$resp);
		        }
			}
		}
	}

public function update_status($id){

 //function to change task status only

		$method = $_SERVER['REQUEST_METHOD'];

		//Check whether the id is passed in the URL. 
		if($method != 'PUT' || $this->uri->segment(2) == '' || is_numeric($this->uri->segment(2)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} 
		else {

			//Authenticate based on header data $client_service and $auth_key
			$check_auth_client = $this->db_model->check_auth_client();

			if($check_auth_client == true){

				//check if the token expired
		        $response = $this->db_model->auth();

		        $respStatus = $response['status'];

		        if($response['status'] == 200){

                   //input obtained from user as JSON 
					$data=json_decode(file_get_contents('php://input'), TRUE);

					$status=$data['status'];
		        	
					$updated_on = date('Y-m-d H:i:s');

					if ($status == "") {
						$respStatus = 400;
						$resp = array('status' => 400,'message' =>  'Status must be filled!');
					} 
					else {
		        		$resp = $this->db_model->update_task_status($id,$status,$updated_on);
					}
					json_output($respStatus,$resp);
		        }
			}
		}
	}


	public function delete_task($id){
		$method = $_SERVER['REQUEST_METHOD'];

		//Check whether the id is passed in the URL. 
		if($method != 'DELETE' || $this->uri->segment(2) == '' || is_numeric($this->uri->segment(2)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {

			//Authenticate based on header data $client_service and $auth_key
			$check_auth_client = $this->db_model->check_auth_client();

			if($check_auth_client == true){
		        $response = $this->db_model->auth();
		        if($response['status'] == 200){
		        	$resp = $this->db_model->delete_task($id);
					json_output($response['status'],$resp);
		        }
			}
		}
	}




//end of file
}
