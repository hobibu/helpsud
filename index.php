<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
setlocale(LC_ALL, 'ru_RU.utf8');
Header("Content-Type: text/html;charset=UTF-8");
include ('lib/connect.php'); //подключаемся к БД
include ('lib/function_global.php'); //подключаем файл с глобальными функциями
ini_set ("session.use_trans_sid", true);
session_start();

if(isset($_GET['action'])&&($_GET['action'] == "out")) out(); //если передана переменная action, «разавторизируем» пользователя

//проверим, быть может пользователь уже авторизирован.
if(isset($_SESSION['id'])) {
	if(isset($_GET['menu']) && $_GET['menu'] == 'select') {
		include ('users/users.php'); //Подключаем страницу с пользователями
	}
	else {
		include ('que/que.php'); //подключаем файл с главным окном
	}
	
	if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1){
		$menu = "<a id='menu' href='/?menu=select'>Пользователи</a>";
	}
	else {
		$menu = "";
	}
	
	$strout = "<a class='button_out' href='/?action=out'>Выход</a>"."<div class='usr_name'>".$_SESSION['login']."</div><div id='usr_name_rect'></div>";
}
else { //если пользователь не авторизирован, то проверим, была ли нажата кнопка входа на сайт или регистрации
	$strout = "";
	$menu = "";
	if(isset($_POST['log_in'])) {
		$error = enter($link); //функция входа на сайт
		if(count($error) == 0) {//если нет ошибок, авторизируем юзера
			include ('que/que.php'); //подключаем файл с главным окном
			$strout = "<a class='button_out' href='/?action=out'>Выход</a>"."<div class='usr_name'>".$_SESSION['login']."</div><div id='usr_name_rect'></div>";
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/'); //перенаправляем на главную страницу для сброса POST запроса.
		}
		else {
			include ('auth/auth_form.php'); //подключаем файл с формой входа
		}
	}
	else if(isset($_POST['reg'])||isset($_POST['GO'])) {
		include ('registration/reg.php'); //подключаем файл с формой регистрации
	}
	else{
		include ('auth/auth_form.php'); //подключаем файл с формой входа
	}
}

$home = $menu."<a class='home_b' href='http://".$_SERVER['HTTP_HOST']."'>HELPSUD</a><div id='home_b_rect'></div>".$strout;

include ('template/index.html'); //подключаем файл шаблона страницы		
?>