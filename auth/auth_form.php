<?PHP
$str = '';
if(isset($error[0])&&($error[0] != ''))
{
$str = '<div class="osi_err">'.$error[0].'</div>';
}
else if(isset($regged)&&($regged == true)) 
{
	$str = '<div class="osi_reg">Пользователь зарегистрирован!</div>';
}
$result = '
	<div class="os">'.$str.'
	<form method="post" action="index.php" class="osf">
	<div>
		<input id="login" type="text" maxlength="25" name="login" placeholder=" Логин" class="osp" /><br />
	</div>
	<div>
		<input id="pass" type="password" maxlength="32" name="password" placeholder=" Пароль" class="osp" /><br />
	</div>
	<div class="v_align_button">
		<div>
			<input type="submit" name="log_in" value="Войти" class="osb"/>
		</div>
		<div>
			<input type="submit" name="reg" value="Регистрация" class="osb"/>
		</div>
	</div>
	</form>
	</div>';

?>