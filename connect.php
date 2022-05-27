<?php
if(!empty($_GET['user']) && $_GET['user'] == 'director' || !empty($_POST['user']) && $_POST['user'] == 'director'){
	require("connect_director.php");
	$user = 'director';
}
else {
	require("connect_manager.php");
	$user = 'manager';
}

if(!empty($_GET['table']) && $_GET['table'] !== null){
	$table = $_GET['table'];
}
else if(!empty($_POST['table']) && $_POST['table'] !== null){
	$table = $_POST['table'];
}
else {
	$table = 'Клиент';
}