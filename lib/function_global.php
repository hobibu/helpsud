<?php
function registrationCorrect($lin) {
	$error = array(); //массив для ошибок
	if($_POST['login'] == "" || $_POST['password'] == "" || $_POST['password2'] == ""){	//не пусто ли поле логина, пароля, подтверждения
		$error[] = "Заполните все поля!"; 			
		return $error; 
	}
	else if(!isset($_POST['lic'])||($_POST['lic'] != "ok")){//приняты ли правила
		$error[] = "Поставьте согласие с правилами!"; 			
		return $error;
	}
	else if(!preg_match('/^([a-zA-Z0-9])(\w|-|_)+([a-z0-9])$/is', $_POST['login'])){ // соответствует ли логин регулярному выражению
		$error[] = "Некорректный логин!"; 			
		return $error;
	}
	else if(strlen($_POST['password']) < 5){ //не меньше ли 5 символов длина пароля
		$error[] = "Пароль меньше 5 символов!"; 			
		return $error;
	}
 	else if($_POST['password'] != $_POST['password2']){ //равен ли пароль его подтверждению
		$error[] = "Подтверждение пароля не верно!"; 			
		return $error;
	}
	$login = $_POST['login'];
	if($rez = mysqli_query($lin, "SELECT * FROM users WHERE login = '$login'"))
	{
		$row_cnt = mysqli_num_rows($rez);
		mysqli_free_result($rez);
		if($row_cnt > 0){ // проверка на существование в БД такого же логина
			$error[] = "Такой пользователь зарегистрирован!"; 			
			return $error;
		}
	}
	return $error;
}

function enter($lin){ 
	$error = array(); //массив для ошибок 	
	if($_POST['login'] != "" && $_POST['password'] != "") { //если поля заполнены 	
		if(!preg_match('/^([a-zA-Z0-9])(\w|-|_)+([a-z0-9])$/is', $_POST['login'])) { // соответствует ли логин регулярному выражению
			$error[] = "Некорректный логин!"; 			
			return $error;	
		}		
		$login = $_POST['login']; 
		$password = $_POST['password'];
		$sql = "SELECT * FROM users WHERE login='$login'";
		$rez = mysqli_query($lin, $sql); //запрашиваем строку из БД с логином, введённым пользователем 		
		if(mysqli_num_rows($rez) == 1) { //если нашлась одна строка, значит такой юзер существует в БД 					
			$row = mysqli_fetch_assoc($rez); 			
			if(md5(md5($password).$row['salt']) == $row['password']) {//сравниваем хэшированный пароль из БД с хэшированными паролем, введённым пользователем и солью 
				//создаём переменную сессии					
				$_SESSION['id'] = $row['id'];	    //записываем в сессию id пользователя 
				$_SESSION['login'] = $row['login']; //записываем в сессию login пользователя 
				$_SESSION['admin'] = $row['admin']; //записываем в сессию роль пользователя 
				return $error; 			
			} 			
			else {//если пароли не совпали 			 				
				$error[] = "Неверный пароль!"; 										
				return $error; 			
			} 		
		} 		
		else { //если такого пользователя не найдено в БД 		 			
			$error[] = "Неверный логин!"; 			
			return $error; 		
		} 	
	} 	
	else { 		
		$error[] = "Заполните все поля!"; 				
		return $error; 	
	} 
}

function out(){ 	
	unset($_SESSION['id']); //удаляем переменную сессии 	
	unset($_SESSION['login']); //удаляем переменную сессии	
	unset($_SESSION['admin']); //удаляем переменную сессии
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/'); //перенаправляем на главную страницу сайта 
}

?>