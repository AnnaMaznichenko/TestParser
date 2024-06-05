Консольное приложение, которое вытаскивает данные из API для конкретного аккаунта и сохраняет в БД в таблицы:

- incomes
- orders
- sales
- stocks

Для этого используются команды:

- php artisan app:parse-income {accountId}
- php artisan app:parse-order {accountId}
- php artisan app:parse-sale {accountId}
- php artisan app:parse-stock {accountId}

Данные свойства “for_pay” сущности sale перед сохранением в БД переведены в копейки и сохранены как integer во избежание возможных проблем, связанных с погрешностями.

Приложение позволяет создавать tokenType, serviceApi, token, company, account и сохранять в соответствующие таблицы.
Для этого используются команды:

- php artisan app:create-tokenType {name}
- php artisan app:create-serviceApi {name} {tokenTypeId}
- php artisan app:create-token {token} {tokenTypeId} {serviceApiId}
- php artisan app:create-company {name}
- php artisan app:create-account {name} {companyId} {tokenId}

Для подключения к БД внутри Docker используется порт из переменной DB_PORT файла .env (можно утановить значение не 3306)
Для перенаправления отладочной информации в консоль, в .env задается значение переменной LOG_CHANNEL=stderr

Для всех существующий аккаунтов в Doсker происходит автоматический запрос на получение и обновление данных дважды в день.
Для этого вызывается команда: app:call-parse
