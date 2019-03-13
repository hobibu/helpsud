<?PHP
if($_SESSION['admin'] == 0){//Если обычный пользователь
	$alrt = "";
	if(isset($_POST['send_m']) && isset($_POST['key_msg'])) {
		$alrt = "Заполните поля правильно!";
		if($_POST['loc_m'] != "" && $_POST['title_m'] != "" && $_POST['descrip_m'] != "") {
				$loc = htmlspecialchars($_POST['loc_m'], ENT_QUOTES);
				$tm = time();
				$tm_edt = time();
				$title = htmlspecialchars($_POST['title_m'], ENT_QUOTES);
				$descript = htmlspecialchars($_POST['descrip_m'], ENT_QUOTES);
				$user_id = 0;
				$m_status = 0;
				$sql = "INSERT INTO mque (q_location,q_date,q_date_edt,q_title,q_description,q_users_name,q_mstatus) VALUES ('".$loc."','".$tm."','".$tm_edt."','".$title."','".$descript."','','".$m_status."')";
				if(!isset($_SESSION['last_id_msg'])){
					if(mysqli_query($link,$sql)) {  //пишем данные в БД
					$_SESSION['last_id_msg'] = $_POST['key_msg'];
					$alrt = "ok";
					}
				}
				else if(isset($_SESSION['last_id_msg']) && ($_POST['key_msg'] != $_SESSION['last_id_msg'])) {
					if(mysqli_query($link,$sql)) {  //пишем данные в БД
					$_SESSION['last_id_msg'] = $_POST['key_msg'];
					$alrt = "ok";
					}
				}
				else $alrt = "Заявка уже отправлена!";
		}
		else $alrt = "Заполните поля!";
	}
	include ("que/que_form.php");
}
else if($_SESSION['admin'] == 1){//Если администратор
		//Построение таблицы
		$result = "";
		$sql = "SELECT * FROM mque ORDER BY q_id DESC";
		$rezq = mysqli_query($link, $sql);
		$str = "";
		$count_rows = mysqli_num_rows($rezq);
		if($count_rows > 0){
			while ($arr = mysqli_fetch_array($rezq)){
				$time = $arr['q_date'] + 10800;
				$id = $arr['q_id'];
				$btn_sel = "<a href='/?select_row=".$id."' class='tbl_b_sel'>Принять</a>";
				$btn_close = "<a href='/?close_row=".$id."' class='tbl_b_cls'>Закрыть</a>";
				$btn_del = "<a href='/?del_row=".$id."' class='tbl_b_del'>Удалить</a>";
				$css_row = "tbl_str_new";
				$css_cell_date = "tbl_cell_new_date";
				$css_cell_loc = "tbl_cell_new_loc";
				$css_cell_td = "tbl_cell_new_td";
				$css_cell_status = "tbl_cell_new_status";
				$css_cell_rect = "rect_new";
				$user_select = "<div class='".$css_cell_status."'></div>";
				if($arr['q_mstatus'] == 1){
					$btn_sel = $btn_close;
					$css_row = "tbl_str_select";
					$css_cell_date = "tbl_cell_select_date";
					$css_cell_loc = "tbl_cell_select_loc";
					$css_cell_td = "tbl_cell_select_td";
					$css_cell_status = "tbl_cell_select_status";
					$css_cell_rect = "rect_select";
					if($arr['q_users_name'] != $_SESSION['login'])$user_select = "<div class='".$css_cell_status."'>Назначена: ".$arr['q_users_name']."</div>";
					else $user_select = "<div class='".$css_cell_status."'></div>";
				}
				else if($arr['q_mstatus'] == 2){
					$btn_sel = $btn_del;
					$css_row = "tbl_str_close";
					$css_cell_date = "tbl_cell_close_date";
					$css_cell_loc = "tbl_cell_close_loc";
					$css_cell_td = "tbl_cell_close_td";
					$css_cell_status = "tbl_cell_close_status";
					$css_cell_rect = "rect_close";
					$user_select = "<div class='".$css_cell_status."'>Закрыта</div>";
				}
				//Составление одной строки заявки
				if(isset($_SESSION['mobile']) && $_SESSION['mobile']) $clk_str = "onclick='disp_event(".$id.",\"<b>".$arr['q_title']."</b><br>".$arr['q_description']."\")'";
				else $clk_str = "";
				$str .= "<div id='".$id."' class='".$css_row."' ".$clk_str."><div class='".$css_cell_date."'>".date('d.m.Y', $time)."<br /><div>".date('H:i', $time)."</div></div>";
				$str .= "<div class='".$css_cell_loc."'>Каб: ".$arr['q_location']."</div>";
				$str .= "<div class='".$css_cell_td."'><b>".$arr['q_title']."</b><br />".$arr['q_description']."</div><div class='".$css_cell_rect."'></div>";
				$str .= $btn_sel.$user_select."</div><br />";
			}
		}
		else $str = "<div class='osi_err'>Заявок нет!</div>";
		//Обработка выбора строки
		if(isset($_GET['select_row'])){
			$tm_new = time();
			$sql = "UPDATE mque SET q_users_name='".$_SESSION['login']."', q_date_edt='".$tm_new."',q_mstatus='1' WHERE q_id='".$_GET['select_row']."'";
			$rezq = mysqli_query($link, $sql);
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
		}
		else if(isset($_GET['close_row'])){
			$tm_new = time();
			$sql = "UPDATE mque SET q_date_edt='".$tm_new."', q_mstatus='2' WHERE q_id='".$_GET['close_row']."'";
			$rezq = mysqli_query($link, $sql);
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
		}
		else if(isset($_GET['del_row'])){
			$sql = "DELETE FROM mque WHERE q_id='".$_GET['del_row']."'";
			$rezq = mysqli_query($link, $sql);
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
		}
		//Уведомления
		$event_str = "<script type='text/javascript' language='javascript' src='lib/s_events.js'></script>";
		$result .= $str.$event_str;
}
else if($_SESSION['admin'] == 2){ //Если сотрудник техподдержки
	//Построение таблицы
	$result = "";
	$sql = "SELECT * FROM mque WHERE q_mstatus<'2' ORDER BY q_id DESC";
	$rezq = mysqli_query($link, $sql);
	$str = "";
	$count_rows = mysqli_num_rows($rezq);
	if($count_rows > 0){
		while ($arr = mysqli_fetch_array($rezq)){
			$time = $arr['q_date'] + 10800;
			$id = $arr['q_id'];
			$btn_sel = "<a href='/?select_row=".$id."' class='tbl_b_sel'>Принять</a>";
			$btn_close = "<a href='/?close_row=".$id."' class='tbl_b_cls'>Закрыть</a>";
			$css_row = "tbl_str_new";
			$css_cell_date = "tbl_cell_new_date";
			$css_cell_loc = "tbl_cell_new_loc";
			$css_cell_td = "tbl_cell_new_td";
			$css_cell_status = "tbl_cell_new_status";
			$css_cell_rect = "rect_new";
			$user_select = "<div class='".$css_cell_status."'></div>";
			if($arr['q_mstatus'] == 1){
				if($arr['q_users_name'] == $_SESSION['login'])$btn_sel = $btn_close;//Ставим кнопку закрыть, если заявка ваша.
				else $btn_sel = "";
				$css_row = "tbl_str_select";
				$css_cell_date = "tbl_cell_select_date";
				$css_cell_loc = "tbl_cell_select_loc";
				$css_cell_td = "tbl_cell_select_td";
				$css_cell_status = "tbl_cell_select_status";
				$css_cell_rect = "rect_select";
				if($arr['q_users_name'] != $_SESSION['login'])$user_select = "<div class='".$css_cell_status."'>Назначена: ".$arr['q_users_name']."</div>";
				else $user_select = "<div class='".$css_cell_status."'></div>";
			}
			//Составление одной строки заявки
			if(isset($_SESSION['mobile']) && $_SESSION['mobile']) $clk_str = "onclick='disp_event(".$id.",\"<b>".$arr['q_title']."</b><br>".$arr['q_description']."\")'";
			else $clk_str = "";
			$str .= "<div id='".$id."' class='".$css_row."' ".$clk_str."><div class='".$css_cell_date."'>".date('d.m.Y', $time)."<br /><div>".date('H:i', $time)."</div></div>";
			$str .= "<div class='".$css_cell_loc."'>Каб: ".$arr['q_location']."</div>";
			$str .= "<div class='".$css_cell_td."'><b>".$arr['q_title']."</b><br />".$arr['q_description']."</div><div class='".$css_cell_rect."'></div>";
			$str .= $btn_sel.$user_select."</div><br />";
		}
	}
	else $str = '<div class="osi_err">Заявок нет!</div>';
	//Обработка выбора строки
	if(isset($_GET['select_row'])){//Если нажали принять заявку
		$tm_new = time();
		$sql = "UPDATE mque SET q_users_name='".$_SESSION['login']."', q_date_edt='".$tm_new."',q_mstatus='1' WHERE q_id='".$_GET['select_row']."'";
		$rezq = mysqli_query($link, $sql);
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
	}
	else if(isset($_GET['close_row'])){//Если нажали закрыть заявку
		$tm_new = time();
		$sql = "UPDATE mque SET q_date_edt='".$tm_new."', q_mstatus='2' WHERE q_id='".$_GET['close_row']."'";
		$rezq = mysqli_query($link, $sql);
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
	}
	//Уведомления
	$event_str = "<script type='text/javascript' language='javascript' src='lib/s_events.js'></script>";
	$result .= $str.$event_str;
}
?>