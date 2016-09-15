Порядок установки модуля:

1. Скопируйте папку payment в корневую дирректорию сайта;

2. В файле /phpshop/inc/config.ini добавьте следующие строки:
```
[fondy]
fondy_merchant_id = "1396424";
fondy_secret_key = "test";
fondy_currency = "RUB";
fondy_lang = "ru";
fondy_on_page = "0";
```
Обозначения:

	* merchant_id - идентификатор магазина Fondy;
	* secret_key - секретный ключ;
	* currency - валюта заказов, т.е. в какой валюте будут передаваться суммы в Fondy(указывать ОДНУ валюту);
	* language - язык, на котором будет отображаться мерчант Fondy.
	* fondy_on_page - Включить режим показа на странице, т.е режим без перенаправления(1 - включен, 0 - выключен).

Подробнее https://portal.fondy.eu/ru/info/api/v1.0/2

3. В панели управления магазином (http://имя_вашего_сайта/phpshop/admpanel/)
  - перейти в раздел "Заказы" -> "Способы оплаты" и нажать кнопку "Новая позиция". 
  - В поле "Тип подключения" выбрать "оплата fondy" и заполнить поля "Наименование", "Заголовок сообщения после оплаты", "Cообщения после оплаты".
  
  ![Скриншот][1]
----
  ![Скриншот][2]
----

[1]: https://raw.githubusercontent.com/cloudipsp/phpshop/master/settings.png
[2]: https://raw.githubusercontent.com/cloudipsp/phpshop/master/settings1.png