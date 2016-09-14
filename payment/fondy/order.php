<?php
/**
 * Обработчик оплаты заказа через Fondy
 */
if (empty($GLOBALS['SysValue']))
    exit(header("Location: /"));

require_once(dirname(__FILE__) . "/FondyLib.php");
$fields = array();
// регистрационная информация
$fields['merchant_id'] = $SysValue['fondy']['fondy_merchant_id']; // идентификатор магазина в системе Fondy
$secret_key = $SysValue['fondy']['fondy_secret_key']; // секретный ключ
$fields['currency'] = $SysValue['fondy']['fondy_currency']; // валюта
$fields['lang'] = $SysValue['fondy']['fondy_lang']; // локализация
//
//параметры магазина
$mrh_ouid = explode("-", $_POST['ouid']);
$fields['order_id'] = $mrh_ouid[0] . $mrh_ouid[1] . FondyForm::ORDER_SEPARATOR . time();     //номер счета

//описание покупки
$fields['order_desc'] = "Order:". $mrh_ouid[0] . $mrh_ouid[1];
$fields['amount'] = round($GLOBALS['SysValue']['other']['total']*100); //сумма покупки

$inv_id = $mrh_ouid[0] . "" . $mrh_ouid[1];
$url = ($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
$success_url = "$url/success/?inv_id=" . $inv_id . '&payment=fondy';

$fields['server_callback_url'] = $success_url;
$fields['response_url'] = $success_url;

$signature =  FondyForm::getSignature($fields, $secret_key);

// вывод HTML страницы с кнопкой для оплаты
if ($SysValue['fondy']['fondy_on_page'] == 0){
	$disp = "
	<div align='center'>
	<head>
	<meta charset='utf-8'>
	</head>
		<form name='tocheckout' method='POST' action='https://api.fondy.eu/api/checkout/redirect/' >
			<input type=hidden name='merchant_id' value='".$fields['merchant_id']."'>
			<input type=hidden name='order_id' value='".$fields['order_id']."'>
			<input type=hidden name='order_desc' value='".$fields['order_desc']."'>
			<input type=hidden name='signature' value='".$signature."'>
			<input type=hidden name='amount' value='".$fields['amount']."'>
			<input type=hidden name='lang' value='".$fields['lang']."'>
			<input type=hidden name='currency' value='".$fields['currency']."'>
			<input type=hidden name='response_url' value='".$fields['response_url']."'>
			<input type=hidden name='server_callback_url' value='".$fields['server_callback_url']."'>
			<input type='submit' id='submit_fondy_payment_form' />
	<script type='text/javascript'>
	document.getElementById('submit_fondy_payment_form').click();
	</script>
		</form>
	</div>";
}else{
	$disp.='<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="https://api.fondy.eu/static_common/v1/checkout/ipsp.js"></script>
	<div id="checkout">
	<div id="checkout_wrapper"></div>
	</div>
	<script>
	var checkoutStyles = {
		"html , body" : {
			"overflow" : "hidden"
		},
		".col.col-shoplogo" : {
			"display" : "none"
		},
		".col.col-language" : {
			"display" : "none"
		},
		".pages-checkout" : {
			"background" : "transparent"
		},
		".col.col-login" : {
			"display" : "none"
		},
		".pages-checkout .page-section-overview" : {
			"background" : "#fff",
			"color" : "#252525",
			"border-bottom" : "1px solid #dfdfdf"
		},
		".col.col-value.order-content" : {
			"color" : "#252525"
		},
		".page-section-footer" : {
			"display" : "none"
		},
		".page-section-tabs" : {
			"display" : "none"
		},

		".page-section-shopinfo" : {
			"display": "none"
		},

		".page-section-overview" : {
			"display": "none"
		},
	}
	function checkoutInit(url, val) {
		$ipsp("checkout").scope(function() {
		this.setCheckoutWrapper("#checkout_wrapper");
		this.addCallback(__DEFAULTCALLBACK__);
		this.setCssStyle(checkoutStyles);
		this.action("show", function(data) {
			$("#checkout_loader").remove();
			$("#checkout").show();
		});
		this.action("hide", function(data) {
			$("#checkout").hide();
		});
		this.action("resize", function(data) {
			$("#checkout_wrapper").width("100%").height(data.height);
		});
		this.loadUrl(url);
		});
	};
var button = $ipsp.get("button");
button.setMerchantId('.$fields['merchant_id'].');
button.setAmount('.$fields['amount'].', "'.$fields['currency'].'", true);
button.setHost("api.fondy.eu");
button.addParam("order_desc","'.$fields['order_desc'].'");
button.addParam("order_id","'.$fields['order_id'].'");
button.addParam("lang","'.$fields['lang'].'");
button.addParam("server_callback_url","'.$fields['server_callback_url'].'");
button.setResponseUrl("'.$fields['response_url'].'");
checkoutInit(button.getUrl());
</script>';
}
?>