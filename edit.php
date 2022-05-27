<?php



require("connect.php");



if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $q="UPDATE ". $_POST['table'] . " SET ";
    $keys=array_keys($_POST);
    $id_key=$keys[2];
    $id_value=$_POST[$keys[2]];
    for($i=3;$i<count($_POST);$i++){
        if($i<count($_POST)-1)
            $q=$q . " [" . $keys[$i] . "] = '" . $_POST[$keys[$i]]."' , ";
        else
            $q=$q . " [" . $keys[$i] . "] = '" . $_POST[$keys[$i]]."' WHERE [". $id_key . "] = '" . $id_value . "'";
    }
    $stmt=sqlsrv_prepare($link,$q);
    if( !$stmt ) {
	echo("Запрос не был выполнен!");
	echo("<br/>");
    echo ($q);
    echo("<br/>");
	echo('<a href="index.php?table='. $_POST['table'].'&user='.$_POST['user'].'">Вернуться</a>');
	echo("<br/>");
    die( print_r( sqlsrv_errors(), true));
    }

    if( sqlsrv_execute( $stmt ) === false ) {
	echo("Запрос не был выполнен!");
	echo("<br/>");
    echo ($q);
    echo("<br/>");
	echo('<a href="index.php?table='. $_POST['table'].'&user='.$_POST['user'].'">Вернуться</a>');
	echo("<br/>");
	die( print_r( sqlsrv_errors(), true));
    }      
    header('Location: index.php?table='. $_POST['table'].'&user='.$_POST['user']);
    die();
}
else{



    if($_GET['table']=="Дела_адвокатов"&&$_GET['user']=="director"){
        $q="SELECT ". urldecode("Номер") ." From " . urldecode("Дело") ;
        $stmt=sqlsrv_prepare($link,$q);
        if( sqlsrv_execute( $stmt ) === false ) {
            echo("Запрос не был выполнен!");
            echo("<br/>");
            echo ($q);
            echo("<br/>");
            echo('<a href="index.php?table='. $_GET['table'].'&user='.$_GET['user'].'">Вернуться</a>');
            echo("<br/>");
            die( print_r( sqlsrv_errors(), true));
            }  
        else{
            $nomera_del=array();
            while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) 
                array_push($nomera_del,$row["Номер"]);
        } 
    }

    if($_GET['table']=="Дела_адвокатов"&&$_GET['user']=="director"){
        $q="SELECT ". urldecode("Id") ." From " . urldecode("Адвокат") ;
        $stmt=sqlsrv_prepare($link,$q);
        if( sqlsrv_execute( $stmt ) === false ) {
            echo("Запрос не был выполнен!");
            echo("<br/>");
            echo ($q);
            echo("<br/>");
            echo('<a href="index.php?table='. $_GET['table'].'&user='.$_GET['user'].'">Вернуться</a>');
            echo("<br/>");
            die( print_r( sqlsrv_errors(), true));
            }  
        else{
            $id_advokatov=array();
            while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) 
                array_push($id_advokatov,$row["Id"]);
        } 
    }



    $q="SELECT TOP 1 * FROM " . $_GET['table'] . " WHERE ";
    $id_key="";
    $id_value="";

    foreach($_GET as $key => $value)
    {
        if($key != 'table' && $key != 'user' && !empty($value))
        {
            $q = $q . $key . " = " . $value;
            $id_key=$key;
            $id_value=$value;
        }
    }

    $stmt=sqlsrv_prepare($link,$q);
    if( !$stmt ) {
	echo("Запрос не был выполнен!");
	echo("<br/>");
    echo ($q);
    echo("<br/>");
	echo('<a href="index.php?table='. $_GET['table'].'&user='.$_GET['user'].'">Вернуться</a>');
	echo("<br/>");
    die( print_r( sqlsrv_errors(), true));
    }

    if( sqlsrv_execute( $stmt ) === false ) {
	echo("Запрос не был выполнен!");
	echo("<br/>");
    echo ($q);
    echo("<br/>");
	echo('<a href="index.php?table='. $_GET['table'].'&user='.$_GET['user'].'">Вернуться</a>');
	echo("<br/>");
	die( print_r( sqlsrv_errors(), true));
    }      

    if(!sqlsrv_has_rows($stmt)){
	echo("Запрос не был выполнен! Строка не найдена!");
	echo("<br/>");
    echo ($q);
    echo("<br/>");
	echo('<a href="index.php?table='. $_GET['table'].'&user='.$_GET['user'].'">Вернуться</a>');
	echo("<br/>");
	die( print_r( sqlsrv_errors(), true));
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Edit</title>
</head>
<body>
    <h1 class="fw-light fst-italic text-center my-5">Изменение строки 
        <?php echo($id_key . " = " . $id_value . " из таблицы " . $_GET['table']); ?>
    </h1>
    <div class="container">
    <?php
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
	if(!empty($row))
	{	
        $keys = array_keys($row);
        echo('<form method="post" action="edit.php">');
        echo('<input type="hidden" name="table" value="'.$_GET['table'].'">');
        echo('<input type="hidden" name="user" value="'.$_GET['user'].'">');
        echo('<input type="hidden" name="'.$id_key.'" value="'.$id_value.'">');
        foreach($keys as $key)
        {
            if(!($key=="Номер_дела"||$key=="Id_Адвоката"))
            {
            echo('<div class="mb-3 row">');
            echo('<label for="inputPassword" class="col-sm-2 col-form-label">'. $key .'</label>');
            echo('<div class="col-sm-10">');
            echo('<input type="text" class="form-control" id="inputPassword" name="'. $key .'" value="'.$row[$key].'">');
            echo('</div>');
            echo('</div>');
        }else{
            if($key=="Номер_дела"&&!empty($nomera_del))
            {
                echo('<div class="mb-3 row">');
                echo('<label for="inputPassword" class="col-sm-2 col-form-label">'. $key .'</label>');
                echo('<div class="col-sm-10">');
                echo('<select class="form-select" name="'. $key .'" id="inputPassword">');
                echo('<option selected value="'.$row[$key].'">'.$row[$key].'</option>');
                foreach($nomera_del as $num){
                    echo('<option value="'.$num.'">'.$num.'</option>');
                }
                echo('</select>');
                echo('</div>');
                echo('</div>');
            }
            if($key=="Id_Адвоката"&&!empty($id_advokatov))
            {
                echo('<div class="mb-3 row">');
                echo('<label for="inputPassword" class="col-sm-2 col-form-label">'. $key .'</label>');
                echo('<div class="col-sm-10">');
                echo('<select class="form-select" name="'. $key .'" id="inputPassword">');
                echo('<option selected value="'.$row[$key].'">'.$row[$key].'</option>');
                foreach($id_advokatov as $id_advokata){
                    echo('<option value="'.$id_advokata.'">'.$id_advokata.'</option>');
                }
                echo('</select>');
                echo('</div>');
                echo('</div>');
            }

        }
    }
        echo('<input type="submit" class="btn btn-success" value="Edit"/>');
        echo('</form>');
    }
    ?>
    </div>
</body>
</html>
<?php } ?>