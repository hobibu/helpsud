<?PHP
include ('../lib/connect.php'); //подключаемся к БД
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

// Функция отправки сообщения
/*function sendMsg($id, $msg){
  echo "id: $id" . PHP_EOL;
  echo "data: $msg" . PHP_EOL;
  echo PHP_EOL;
  ob_flush();
  flush();
}*/

function sendMsg($msg){
  echo "data: $msg" . PHP_EOL;
  echo PHP_EOL;
  ob_flush();
  flush();
}

$time_now = time();
$stmt = mysqli_prepare($link,"SELECT * FROM mque WHERE q_date_edt>?");
mysqli_stmt_bind_param($stmt, "s", $time_now);

// Запускаем бесконечный цикл
while(true){
	$new_mes = false;
	mysqli_stmt_execute($stmt);
	$num = mysqli_num_rows(mysqli_stmt_get_result($stmt));
	if($num > 0)
	{
		$new_mes = true;
	}
	//$serverTime = time();
	//sendMsg($serverTime, ' '.date("h:i:s", time()).' Новых сообщений F: '.$num);
	sleep(1);
	if($new_mes)
	{
		  sendMsg('Новые заявки - '.$num);
		  sleep(1);
	}
}
?>