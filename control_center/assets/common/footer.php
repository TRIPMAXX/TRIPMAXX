<footer class="main-footer">
	<div class="pull-right hidden-xs"></div>
	<strong>Copyright &copy; 2018 <a href="#">TRIPMAXX</a>. All rights reserved</strong>
</footer>
<?php
if(isset($_SESSION['SET_FLASH']))
{
	if($_SESSION['SET_TYPE']=='error')
	{
		echo "<script type='text/javascript'>showError('".$_SESSION['SET_FLASH']."');</script>";
	}
	if($_SESSION['SET_TYPE']=='success')
	{
		echo "<script type='text/javascript'>showSuccess('".$_SESSION['SET_FLASH']."');</script>";
	}
}
unset($_SESSION['SET_FLASH']);
unset($_SESSION['SET_TYPE']);
$db=NULL;
?>