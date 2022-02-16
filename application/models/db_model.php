<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class db_model extends CI_Model {

        function __construct() {
            $this->load->library('session');
            $this->load->database();
            parent::__construct();
            date_default_timezone_set('Asia/Colombo');
        }

    // Variables used for authentication. To be sent in header data.
    var $client_service = "frontend-client";
    var $auth_key       = "todo-api-surge";


    public function check_auth_client(){
        //function to authenticate client using the header data sent. 

        $client_service = $this->input->get_request_header('Client-Service', TRUE);
        $auth_key  = $this->input->get_request_header('Auth-Key', TRUE);
        
        if($client_service == $this->client_service && $auth_key == $this->auth_key){
            return true;
        } else {
            return json_output(401,array('status' => 401,'message' => 'Unauthorized!'));
        }
    }

      public function login($username,$password){
       
       // login function
      //  $f="SurgeAdmin";
//$p=password_hash($f,PASSWORD_DEFAULT);echo $p;exit;
        $q  = $this->db->select('password,user_id')->from('users')->where('username',$username)->get()->row();
    

        if($q == ""){
            return array('status' => 401,'message' => 'Unauthorized! Wrong Username');
        } 
        else {

            //password to be hashed using password_hash() and stored in db
            $hashed_password = $q->password;
            $id              = $q->user_id;
          
          //compare entered password and password obtained from db 
          if(password_verify($password, $hashed_password)){
               
               $last_login = date('Y-m-d H:i:s');

               //generate random token. using password_hash for complexity.
               $token = password_hash(substr( md5(rand()), 0, 7),PASSWORD_DEFAULT);

               //set token expiry time
               $expired_on = date("Y-m-d H:i:s", strtotime('+6 hours'));

               $this->db->trans_start();
               $this->db->where('user_id',$id)->update('users',array('last_login' => $last_login));

               $data=array(
                'user_id' => $id,
                'token' => $token,
                'created_on' => date('Y-m-d H:i:s'),
                'expired_on' => $expired_on
              );

               $this->db->insert('user_authentication',$data);

               if ($this->db->trans_status() === FALSE){

                  $this->db->trans_rollback();
                  return array('status' => 500,'message' => 'Internal server error.');
               } 
               else {
                  $this->db->trans_commit();
                  return array('status' => 200,'message' => 'Successfully login.','id' => $id, 'token' => $token);
               }
            } 
            else {

               return array('status' => 401,'message' => 'Unauthorized! Wrong Password.');
            }
        }
    }

    public function logout(){

       // logout function

        $user_id  = $this->input->get_request_header('user_id', TRUE);
        $token     = $this->input->get_request_header('token', TRUE);

        $this->db->where('user_id',$user_id)->where('token',$token)->delete('user_authentication');

        $query=$this->db->affected_rows();
       
       if($query) {
            return array('status' => 200,'message' => 'Successfully Logged Out.');
       }
       else{
            return array('status' => 400,'message' => 'Provide User ID and Token.');
       }
    }

    public function auth(){

    //to check the expiry of token. update token expiry time.

        $user_id  = $this->input->get_request_header('User_id', TRUE);
        $token     = $this->input->get_request_header('Token', TRUE);
       
        $q  = $this->db->select('expired_on')->from('user_authentication')->where('user_id',$user_id)->where('token',$token)->get()->row();

        if($q == ""){

            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        } 
        else {

            if($q->expired_on < date('Y-m-d H:i:s')){

                return json_output(401,array('status' => 401,'message' => 'Your session has been expired.'));
            } 
            else {
                //update token expiration time

                $updated_on = date('Y-m-d H:i:s');
                $expired_on = date("Y-m-d H:i:s", strtotime('+6 hours'));

                $this->db->where('user_id',$user_id)->where('token',$token)->update('user_authentication',array('expired_on' => $expired_on,'updated_on' => $updated_on));

                return array('status' => 200,'message' => 'Authorized.');
            }
        }
    }


    public function get_all_tasks($id){
    //function to get task data. pass id to get specific task data     
        
        $this->db->select('task_id,task_name,ts.status_name as status,created_on,updated_on');
        $this->db->from('task_list tl');
        $this->db->join('task_status ts','tl.status=ts.status_id','left');

        if($id!=NULL)
        {
          $this->db->where('task_id',$id);
        }

        $this->db->order_by('task_id','ACS');
        $data= $this->db->get()->result();

        return $data;
         
    }

    public function create_task($task_name,$status){
        //function to create todo task
       
       //status stored to db in number format 
        if($status=="todo"){

            $status_code=1;
           
        }
       if($status=="inprogress"){
            $status_code=2;
           
        }
        if($status=="done"){
            $status_code=3;
           
        }

        $data=array(
            'task_name'=>$task_name,
            'status'=>$status_code
        );

        $this->db->insert('task_list',$data);
        return array('status' => 201,'message' => 'Task created successfully!');
    }


  
    public function update_task($id,$task_name,$updated_on){
    //function to update task name
       
        $data=array(
            'task_name'=>$task_name,
            //'status'=>$status_code,
            'updated_on'=>$updated_on
        );

        $this->db->where('task_id',$id)->update('task_list',$data);
        return array('status' => 200,'message' => 'Task Name updated successfully!');
    }


    public function update_task_status($id,$status,$updated_on){
          //function to update task status

    $status_code=Null;

       if($status=="todo"){

            $status_code=1;
           
        }

       if($status=="inprogress"){
            $status_code=2;
           
        }
       if($status=="done"){
            $status_code=3;
           
        }

        if($status_code!=Null){
                $data=array(
                    'status'=>$status_code,
                    'updated_on'=>$updated_on
                );

                $this->db->where('task_id',$id)->update('task_list',$data);
                return array('status' => 200,'message' => 'Task Status updated successfully!');
            }

        else{
            return array('status' => 400,'message' => 'Status Must be any of the given values: todo, inprogress or done');
        }

    }


    public function delete_task($id){
    //function to delete tasks by ID
      
        $this->db->where('task_id',$id)->delete('task_list');
        $query=$this->db->affected_rows();
       
       if($query) {

        return array('status' => 200,'message' => 'Task deleted successfully!');
        }
        else{
            return array('status' => 400,'message' => 'Record not found!');
        }
    }


//end of file
}