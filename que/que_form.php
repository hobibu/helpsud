<?PHP
$str = "";
$key = mt_rand(10000,99999);

if($alrt == "ok") 
{
	$str = "<div class='osi_reg'>Заявка отправлена!</div>";
}
else if($alrt != "" )
{
	$str = "<div class='osi_err'>".$alrt."</div>";
}

$result = 
	"<div class='os'>".$str."<form method='post' action='index.php' class='osf'>
	<input type='hidden' name='key_msg' value='".$key."' />
	<div class='osn'>
		ЗАЯВКА
	</div>
	<div>
		<input id='loc_m' type='text' maxlength='5' name='loc_m' placeholder=' Кабинет' class='os_add_t' /><br />
	</div>
	<div>
		<input id='title_m' type='text' maxlength='100' name='title_m' placeholder=' Проблема' class='os_add_t' /><br />
	</div>
	<div>
		<textarea name='descrip_m' maxlength='300'  placeholder=' Описание' class='os_add_d'></textarea><br />
	</div>
	<div class='v_align_reg_button'>
		<input type='submit' name='send_m' value='Отправить' class='osb'/>
	</div>
	</form>
	</div>";
?>