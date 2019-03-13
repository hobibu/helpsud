<?PHP
	//Построение таблицы
	$result = "";
	$sql = "SELECT * FROM users";
	$query = mysqli_query($link, $sql);
	$str = "";
	$count_rows = mysqli_num_rows($query);
	if($count_rows > 0){
		while ($arr = mysqli_fetch_array($query)){
			$id = $arr['id'];
			$name = $arr['login'];
			$reg_dt = $arr['reg_date'] + 10800;
			$is_admin = $arr['admin'];
			$btn_sel = "<a href='/?menu=select&select_user=".$id."' class='tbl_b_user'>Назначить сотрудником</a>";
			$btn_del = "<a href='/?menu=select&del_user=".$id."' class='tbl_b_user'>Удалить</a>";
			$css_row = "tbl_str_user";
			$css_cell_id = "tbl_cell_user_id";
			$css_cell_name = "tbl_cell_user";
			$css_cell_date = "tbl_cell_user";
			$css_cell_rect = "rect_user";
			$css_cell_status = "tbl_cell_user_status";
			
			if($is_admin == 1){
				$user_stat = "<div class='".$css_cell_status."'><b>Администратор</b></div>";
				$btn_sel = "";
				$btn_del = "";
			}
			else if($is_admin == 2){
				$user_stat = "<div class='".$css_cell_status."'><b>Сотрудник техподдержки</b></div>";
				$btn_sel = "";
			}
			else $user_stat = "<div class='".$css_cell_status."'><b>Пользователь</b></div>";

			//Составление одной строки заявки
			$str .= "<div id='".$id."' class='".$css_row."'><div class='".$css_cell_id."'>ID: <b>".$id."</b></div>";
			$str .= "<div class='".$css_cell_name."'>Имя: <b>".$name."</b></div>";
			$str .= "<div class='".$css_cell_date."'>Регистрация: <b>".date('d.m.Y', $reg_dt)."/".date('H:i', $reg_dt)."</b></div>";
			$str .= "<div class='".$css_cell_rect."'></div>";
			$str .= $btn_del.$btn_sel.$user_stat."</div><br />";
		}
	}
	else $str = "<div class='osi_err'>Пользователей нет!</div>";
	
	//Обработка выбора строк
	if(isset($_GET['select_user'])){
		$sql = "UPDATE users SET admin=2 WHERE id='".$_GET['select_user']."'";
		$rezq = mysqli_query($link, $sql);
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/?menu=select');
	}
	else if(isset($_GET['del_user'])){
		$sql = "DELETE FROM users WHERE id='".$_GET['del_user']."'";
		$rezq = mysqli_query($link, $sql);
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/?menu=select');
	}
	$result .= $str;
?>