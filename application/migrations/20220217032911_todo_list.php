<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_todo_list extends CI_Migration {

        public function up()
        {
//create table task_list

                $this->dbforge->add_field(array(
                        'task_id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE,
                                'null' => FALSE
                        ),
                        'task_name' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '255',
                                'null' => FALSE
                        ),
                        'status' => array(
                                'type' => 'tinyint',
                                'constraint' => '1',
                                'null' => FALSE,
                                'default'=>'1',
                                'comment'=>'1-To Do 2-In Progress 3-Done'
                        ),

                        'created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP',

                        
                        'updated_on' => array(
                                'type' => 'datetime',
                                
                                'null' => TRUE,
                                
                        ),

                        
  
                ));

                $this->dbforge->add_key('task_id', TRUE);
                $this->dbforge->create_table('task_list');


//create table task_status

                $this->dbforge->add_field(array(
                        'status_id' => array(
                                'type' => 'INT',
                                'constraint' => 4,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE,
                                'null' => FALSE
                        ),
                        'status_name' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '15',
                                'null' => FALSE
                        ),   
  
                ));
                $this->dbforge->add_key('status_id', TRUE);
                $this->dbforge->create_table('task_status');

//create table users
  
                $this->dbforge->add_field(array(
                        'user_id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE,
                                'null' => FALSE
                        ),
                        'username' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100',
                                'null' => FALSE
                        ),
                         'password' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '255',
                                'null' => FALSE
                        ),
                        'u_name' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100',
                                'null' => TRUE
                        ),

                         'last_login' => array(
                                'type' => 'datetime',
                                'null' => FALSE,
                
                        ),

                        'created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP',

                        
                        'updated_on' => array(
                                'type' => 'datetime',
                                'null' => TRUE,
                        ),

                ));

                $this->dbforge->add_key('user_id', TRUE);
                $this->dbforge->create_table('users');

                $data = array(
                            'user_id' => "1",
                            'username' => "admin",
                            'password' => '$2y$10$fSR.3VlSCxD8APkYjLUMDuqkB6Od7dSdGuOvi4n/hwz/zmRThsUxe',
                            'u_name' => "Admin User",
                            'last_login' => "2022-02-17 01:04:37",
                            'created_on' => "2022-02-17 01:04:37",
                            'updated_on' => "2022-02-17 01:04:37",
                            
                         );
                         //$this->db->insert('user_group', $data); I tried both
                         $this->db->insert('users', $data);



  //create table user_authentication
  
                $this->dbforge->add_field(array(
                     
                        'auth_id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE,
                                'null' => FALSE
                        ),
                        'user_id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'null' => FALSE
                        ),
                         'token' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '255',
                                'null' => FALSE
                        ),

                        'created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP',

                        
                        'updated_on' => array(
                                'type' => 'datetime',
                                'null' => TRUE,
                        ),
                        'expired_on' => array(
                                'type' => 'datetime',
                                'null' => FALSE,
                        ),

                ));

                $this->dbforge->add_key('auth_id', TRUE);
                $this->dbforge->create_table('user_authentication');


        //end of function        
        }

 


        public function down()
        {
           //  $this->dbforge->drop_table('task_status');  
        }
}
