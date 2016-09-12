<?php
require_once(dirname(__FILE__) . "/FondyLib.php");
if (empty($GLOBALS['SysValue']))
    exit(header("Location: /"));

global $SysValue,$link_db;
	// Определение платежной системы по $_GET['payment']
	if (!empty($_REQUEST['payment']))
		if ($_REQUEST['payment'] == 'fondy') {
			$settings['merchant_id'] = $SysValue['fondy']['fondy_merchant_id'];
			$settings['secret_key'] = $SysValue['fondy']['fondy_secret_key'];
			$valid = FondyForm::isPaymentValid($settings, $_POST);
			if ($valid == true ){
				$order_metod = "fondy";	
				$success_function = true; // Выключаем функцию обновления статуса заказа, операция уже выполнена в result.php
				$my_crc = "NoN";
				$crc = "NoN";
				$inv_id = $_GET['inv_id'];
			}else{
				WriteLog($valid);
			}
	}	
	
function WriteLog($MY_LMI_HASH) {
	$handle = fopen("../paymentlog.log", "a+");
	$post = null;
	foreach ($_POST as $k => $v)
	$post.=$k . "=" . $v . "\r\n";
	$str = "
	Fondy Payment Start ------------------
	date=" . date("F j, Y, g:i a") . "
	$post
	MY_LMI_HASH=$MY_LMI_HASH
	REQUEST_URI=" . $_SERVER['REQUEST_URI'] . "
	IP=" . $_SERVER['REMOTE_ADDR'] . "
	Fondy Payment End --------------------
	";
	fwrite($handle, $str);
	fclose($handle);
}
?>