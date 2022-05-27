<?php
echo( sqlsrv_errors());
require("connect.php");

$query="SELECT * FROM " . $user . '_' . $table;

$params  = explode('&', urldecode($_SERVER['QUERY_STRING']));
$first_param = true;
$keys=array();

foreach($params as $param)
{
	if(!empty($param))
	{
		list($param_name, $param_value) = explode('=', $param, 2);
		if($param_name != 'table' && $param_name != 'user')
		{
			if(!empty($param_value))
			{
				if($first_param)
				{
					$query = $query . ' WHERE [' . $param_name . '] like \'%' . $param_value . '%\'';
					$first_param=false;
				}
				else 
				{
					$query = $query . ' AND [' . $param_name . '] like \'%' . $param_value . '%\'';
				}
			}
			array_push($keys, $param_name);
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<title>Адвокатская контора</title>
</head>
<body>
<h1 class="fw-light fst-italic text-center my-5">Адвокатская контора</h1>
<?php
	$stmt = sqlsrv_query($link, $query);
	if( $stmt === false) {
		die( print_r( sqlsrv_errors(), true) );
	}
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
	if(!empty($row))
		$keys = array_keys($row);
?>
<div class="container">
	<form>
		<div class="row my-3">
			<select class="form-select form-select-sm w-25 mx-2" name="table">
				<option <?php if($table=='Клиент') echo('selected'); ?> value="Клиент">Клиент</option>
				<option <?php if($table=='Адвокат') echo('selected'); ?> value="Адвокат">Адвокат</option>
				<option <?php if($table=='Дела_адвокатов') echo('selected'); ?> value="Дела_адвокатов">Дела_адвокатов</option>
				<option <?php if($table=='Дело') echo('selected'); ?> value="Дело">Дело</option>
			</select>
			<select class="form-select form-select-sm w-25 mx-2" name="user">
				<option <?php if($user == 'director') echo('selected'); ?> value="director">Директор</option>
				<option <?php if($user == 'manager') echo('selected'); ?> value="manager">Менеджер</option>
			</select>
			<button class="btn btn-sm btn-outline-success w-25">Показать</button>
		</div>
	</form>
	<h6 class="fw-bold fst-italic text-start my-2">Строка запроса: <?php echo($query); ?></h6>
	<table class="table">
	<thead>
		<tr>
		<?php
			foreach($keys as $key)
			{
				echo('<th>'. $key . '</th>');
			}
		?>
		<th></th>
		<th></th>
		</tr>
	</thead>
	<tbody>
    <tr>
		<form>
		<?php
			foreach($keys as $key)
			{
				if(!empty($_GET[$key]))
					echo('<td>'. '<input type="text" class="form-control" placeholder="' . $key .'" name="' . $key . '" value="'. $_GET[$key] .'">' . '</td>');
				else
					echo('<td>'. '<input type="text" class="form-control" placeholder="' . $key .'" name="' . $key . '">' . '</td>');

			}
		?>
		<td>
			<input type="hidden" name="table" value="<?php echo($table);?>">
			<input type="hidden" name="user" value="<?php echo($user);?>">
			<a href="index.php?table=<?php echo($table)?>&user=<?php echo($user)?>"> 
		  		<button type="button" class="btn btn-outline-secondary">
				  	<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
 					<path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
					</svg>
                </button> 
			</a>
		</td>
		<td>
			<button type="submit" class="btn btn-outline-info">
				<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
					<path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
				</svg>
			</button>
		</td>
		</form>
	</tr>


		<?php
		do{
			echo('<tr>');
			foreach($keys as $key)
			{
				echo('<td>');
				if(!empty($row[$key])) echo($row[$key]);
				echo('</td>');
			}
			?>
		
		<td>
		  	<a href="edit.php?table=<?php echo($table)?>&user=<?php echo($user)?>&<?php echo($keys[0]."=".$row[$keys[0]])?>"> 
		  		<button type="button" class="btn btn-outline-secondary">
                  	<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
  						<path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"></path>
					</svg>
                </button> 
			</a>
		</td>
		<td>
		  <a href="del.php?table=<?php echo($table)?>&user=<?php echo($user)?>&<?php echo($keys[0]."=".$row[$keys[0]])?>"> 
		  	<button type="button" class="btn btn-outline-danger">
				<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
					<path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
				  </svg>
              </button> 
			</a>
		</td>

			<?php
			echo('</tr>');
		}while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC));

	  ?>

    <tr>
		<form action="http://localhost:8080/sql/add.php" method="get" enctype="multipart/form-data">
			<?php
				foreach($keys as $key)
				{
					echo('<td>'. '<input type="text" class="form-control" placeholder="' . $key .'" name="' . $key .'">' . '</td>');
				}
			?>
			<td>		
				<input type="hidden" name="table" value="<?php echo($table);?>">
				<input type="hidden" name="user" value="<?php echo($user);?>">
			</td>
			<td>
				<button type="submit" class="btn btn-outline-success" action="">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
						<path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
					</svg>
				</button>
			</td>
		</form>
	</tr>
	</tbody>
  </table>
</div>

<?php
	sqlsrv_close($link);
?>
</body>
</html>
