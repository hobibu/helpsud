<?PHP
$str = '';
if(isset($error[0])&&($error[0] != ''))
{
$str = '<div class="osi_err">'.$error[0].'</div>';
}
$result = '
	<div class="os">'.$str.'
	<form method="post" action="index.php" class="osf">
	<div>
		<input id="login" type="text" maxlength="25" name="login" placeholder=" Придумайте логин" class="osp" /><br />
	</div>
	<div>
		<input id="pass" type="password" maxlength="32"  name="password" placeholder=" Придумайте пароль" class="osp" /><br />
	</div>
	<div>
	<input id="re_pass" type="password" maxlength="32" name="password2" placeholder=" Повторите пароль" class="osp" /><br />
	</div>
	<div class="osc">
		<label>
		<input id="no_xyz" type="checkbox" name="lic" value="ok" /> Согласен с правилами регистрации.<br />
		</label><br />
	</div>
	<div class="v_align_reg_button">
		<input type="submit" name="GO" value="Регистрация" class="osb"/>
	</div>
	</form>
	</div>';
?>