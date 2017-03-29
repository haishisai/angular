<?php
require_once 'PdoMySQL.class.php';
$db = new PdoMySQL();
session_start();
// 登入
if(isset($_POST['username'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$username = str_replace('\'','', $username);
	$password = str_replace('\'','', $password);
	$sql = "select * from anto_school where name='{$username}' and password='{$password}';";
	$res = $db->getAll($sql);
	if(empty($res)){
		echo 0;
		return false;
	}else{
		//查询员工姓名和工号
		$sql = "select * from anto_school where name='{$username}';";
		$res = $db->getAll($sql);
		foreach($res as $value);
		$username = $value['name'];		
		$_SESSION['username'] = $username;
		echo "ok" ;
		return false;
		//写入日志！！！
	}	
	return false;
}
// 注册
if(isset($_POST['new_user'])){
	$new_user = $_POST['new_user'];
	$password = $_POST['password'];
	$re_password = $_POST['re_password'];
	$user_email= $_POST['user_email'];
	$sql = "insert into anto_school (name,password,user_email) values ('{$new_user}','{$password}','{$user_email}');";
	$res = $db->execute($sql);
	if($res==1){
		echo "ok";
	}
	return false;
}
// 检测-用户名是否已存在
if(isset($_POST['check_newuser'])){
	$new_user = $_POST['check_newuser'];
	$sql = "select * from anto_school where name='{$new_user}';";
	$res = $db->getAll($sql);
	if(empty($res)){
		echo "ok";
		return false;
	}else{
		echo 0;
		return false;
	}
}
// 检测-是否登录
if(isset($_POST['login_check'])){
	if(empty($_SESSION['username'])){
		echo 0;
		return false;
	}else{
		echo $_SESSION['username'];
		return false;
	}
}
// 退出
if(isset($_POST['logout'])){
	session_destroy();
}















?>