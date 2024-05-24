Консольное приложение, которое вытаскивает данные из API и сохраняет в БД в таблицы:

- incomes
- orders
- sales
- stocks

Для этого используются команды:

- php artisan app:parse-income
- php artisan app:parse-order
- php artisan app:parse-sale
- php artisan app:parse-stock

Доступы к БД:

- DB_CONNECTION=mysql
- DB_HOST=141.8.192.46
- DB_PORT=3306
- DB_DATABASE=a0987119_TestParser
- DB_USERNAME=a0987119_AnnaMaznichenko
- DB_PASSWORD=Czs1hkVE


Поскольку неизвестны временные границы данных по сущностям income, order, sale, было сделано допущение и данные запрашиваются за последние 10 лет от текущей даты.

Данные свойства “for_pay” сущности sale перед сохранением в БД переведены в копейки и сохранены как integer во избежание возможных проблем, связанных с погрешностями.
