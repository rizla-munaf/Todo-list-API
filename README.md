# Todo-list-API

# Setup
Download the project folders, change the config and insert database details to database.php file ("application/config") and import MySQL database (todo_list.sql).
Migrtion script can be found under ("application/migrations")

POSTMAN , Insomnia or other preferred tools can be used to simulate frontend.

# MVC structure
Model : "db_model"
Views : "None"
Controller: "Todo_list", "Migrate" 

# Testing API
The API needs to be tested by including header Content-Type,Client-Service & Auth-Key with each request

And for all end points except login, user ID and Token needs to be inserted in header data. values for both will be generated after successfull login. 


# List of the API endpoints:

	curl --request POST \
  --url http://localhost/to_do_list/index.php/login \
  --header 'Auth-Key: todo-api-surge' \
  --header 'Client-Service: frontend-client' \
  --header 'Content-Type: application/json' \
  --data '{ "username" : "admin", "password" : "Admin123$"}'
	
	curl --request GET \
  --url http://localhost/to_do_list/index.php/get_all_tasks \
  --header 'Auth-Key: todo-api-surge' \
  --header 'Client-Service: frontend-client' \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --header 'Token: $2y$10$04TbRKs312MIQoimTJ7PbeKFZulnVsjvkUlPvwULas90bHk.qkSNS' \
  --header 'User_id: 1'
	
	curl --request GET \
  --url http://localhost/to_do_list/index.php/get_task_by_id/1 \
  --header 'Auth-Key: todo-api-surge' \
  --header 'Client-Service: frontend-client' \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --header 'Token: $2y$10$04TbRKs312MIQoimTJ7PbeKFZulnVsjvkUlPvwULas90bHk.qkSNS' \
  --header 'User_id: 1'
	
	curl --request POST \
  --url http://localhost/to_do_list/index.php/create_task \
  --header 'Auth-Key: todo-api-surge' \
  --header 'Client-Service: frontend-client' \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --header 'Token: $2y$10$04TbRKs312MIQoimTJ7PbeKFZulnVsjvkUlPvwULas90bHk.qkSNS' \
  --header 'User_id: 1' \
  --data 'task_name=My New Task' \
  --data status=todo
	
	curl --request DELETE \
  --url http://localhost/to_do_list/index.php/delete_task/5 \
  --header 'Auth-Key: todo-api-surge' \
  --header 'Client-Service: frontend-client' \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --header 'Token: $2y$10$04TbRKs312MIQoimTJ7PbeKFZulnVsjvkUlPvwULas90bHk.qkSNS' \
  --header 'User_id: 1'
	
	curl --request PUT \
  --url http://localhost/to_do_list/index.php/update_task/4 \
  --header 'Auth-Key: todo-api-surge' \
  --header 'Client-Service: frontend-client' \
  --header 'Content-Type: application/json' \
  --header 'Token: $2y$10$04TbRKs312MIQoimTJ7PbeKFZulnVsjvkUlPvwULas90bHk.qkSNS' \
  --header 'User_id: 1' \
  --data '{"task_name":"Updated Todo Task"}'
	
	curl --request PUT \
  --url http://localhost/to_do_list/index.php/update_status/4 \
  --header 'Auth-Key: todo-api-surge' \
  --header 'Client-Service: frontend-client' \
  --header 'Content-Type: application/json' \
  --header 'Token: $2y$10$04TbRKs312MIQoimTJ7PbeKFZulnVsjvkUlPvwULas90bHk.qkSNS' \
  --header 'User_id: 1' \
  --data '{"status":"inprogress"}'
	
	
	curl --request POST \
  --url http://localhost/to_do_list/index.php/logout \
  --header 'Auth-Key: todo-api-surge' \
  --header 'Client-Service: frontend-client' \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --header 'token: $2y$10$04TbRKs312MIQoimTJ7PbeKFZulnVsjvkUlPvwULas90bHk.qkSNS' \
  --header 'user_id: 1'
  
  	curl --request GET \
  --url http://localhost/to_do_list/index.php/Migrate
