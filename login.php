<?php
      
	session_start();
		
	if((!isset($_POST['login'])) || (!isset($_POST['pass']))){
		$_SESSION['error'] = true;
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";
	require_once "dbinfo.php";

	$connection = new mysqli($host, $db_user, $db_pass, $db_name);
        
	if ($connection->connect_errno!=0){
		echo "Error: ".$connection->connect_errno;
	}
	else
	{
                $connection -> query ('SET NAMES utf8');
                $connection -> query ('SET CHARACTER_SET utf8_unicode_ci');
		
                $login = $_POST['login'];
		$pass = $_POST['pass'];

		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		$pass = htmlentities($pass, ENT_QUOTES, "UTF-8");

                $hash_pass = md5($pass);  

		if ($result = $connection->query(
		sprintf("SELECT * FROM $db_users_tab WHERE $db_users_login='%s' AND $db_users_pass='%s'",
		mysqli_real_escape_string($connection, $login),
		mysqli_real_escape_string($connection, $hash_pass)))); 
                {
			if ($result->num_rows == 1)
			{
				$_SESSION['online'] = true;
				$row = $result->fetch_assoc();
				$_SESSION['id'] = $row[$db_users_id];
				$_SESSION['fname'] = $row[$db_users_fname];
				$_SESSION['lname'] = $row[$db_users_lname];
				$_SESSION['function'] = $row[$db_users_function];
				$_SESSION['connection'] = $connection;
				
				unset($_SESSION['error']);
				$result->free_result();
                                            
				header('Location: index.php');
			}
			else
			{      
				$_SESSION['error'] = '<span style="color:red; position: absolute;top: 200px;">Nieprawidłowy login lub hasło!</span>';
				header('Location: index.php');
			}
		}
	}
        
	$connection->close();	
?>