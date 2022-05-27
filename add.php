<?php

require("connect.php");

$q="INSERT INTO " . $_GET['table'] . " VALUES (";

foreach($_GET as $key => $value)
{
	echo($key . " => " . $value);
	echo("<br/>");
	if($key != 'table' && $key != 'user')
	{
		if(!($_GET['table']=="Клиент" && $key == 'Id'))
		{
			if(empty($value))
				$q = $q . "NULL,";
			else 
				$q = $q."'$value',";
		}
	}
}
$q=substr($q,0, -1) . ')';

echo ($q);
echo("<br/>");

$stmt=sqlsrv_prepare($link,$q);
if( !$stmt ) {
	echo("Запрос не был выполнен!");
	echo("<br/>");
	echo('<a href="index.php?table='. $_GET['table'].'&user='.$_GET['user'].'">Вернуться</a>');
	echo("<br/>");
    die( print_r( sqlsrv_errors(), true));
}

if( sqlsrv_execute( $stmt ) === false ) {
	echo("Запрос не был выполнен!");
	echo("<br/>");
	echo('<a href="index.php?table='. $_GET['table'].'&user='.$_GET['user'].'">Вернуться</a>');
	echo("<br/>");
	die( print_r( sqlsrv_errors(), true));
}

if(sqlsrv_rows_affected($stmt)==0)
{
	echo("Запрос не был выполнен! Ни одна строка не была добавлена");
	print_r( sqlsrv_errors(), true);
	sqlsrv_close($link);
	echo('<a href="index.php?table='. $_GET['table'].'&user='.$_GET['user'].'"');
}
else{
	header('Location: http://localhost:8080/sql/index.php?table='. $_GET['table'].'&user='.$_GET['user']);
	die(); 
}