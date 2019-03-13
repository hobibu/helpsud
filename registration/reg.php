<?php
 $regged = false;
//проверим, быть может пользователь уже авторизирован. Если это так, перенаправим его на главную страницу сайта
if(isset($_SESSION['id'])) {
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
}
else {
	if(isset($_POST['GO'])) { //если была нажата кнопка регистрации, проверим данные на корректность и, если данные введены и введены правильно, добавим запись с новым пользователем в БД
		$error = registrationCorrect($link); //записываем в переменную результат работы функции registrationCorrect()
		if(count($error) == 0) { //если ошибок нет, запишем данные в базу данных
			$login = htmlspecialchars($_POST['login']);
			$password = $_POST['password'];
			$salt = mt_rand(100, 999);
			$tm = time();
			$password = md5(md5($password).$salt);
			$sql = "INSERT INTO users (login,password,salt,reg_date) VALUES ('".$login."','".$password."','".$salt."','".$tm."')"; 
			if(mysqli_query($link,$sql)) {//пишем данные в БД
				$regged = true;
				include ("./auth/auth_form.php"); //подключаем шаблон авторизации в случае успешной регистрации
			}
		}
		else {
			$regged = false;
			include_once ("registration_form.php"); //подключаем шаблон в случае некорректности данных
		}
	} 
	else {
		include_once ("registration_form.php"); //подключаем шаблон в случае если кнопка регистрации нажата не была, то есть, пользователь только перешёл на страницу регистрации
	}
}
?>