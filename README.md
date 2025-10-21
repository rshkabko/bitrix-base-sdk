# SDK для работы с Битрикс/Битрикс24 #

# Deprecated #

Самый главный класс, который нужно подключать для начала работы с Битрикс API

Автоматически подключает такие классы:
- Логирования
- ДБ

## Установка и подключение ##

Пригласить человека в репозиторий, попросить его настроить SSH-доступ - .

В /local/classes/ создать composer.json


```json
{
    "name": "flamix/bitrix",
    "description": "Main composer to Bitrix and Bitrix24",
    "homepage": "https://ru.flamix.software",
    "authors": [
        {
            "name": "Roman Shkabko",
            "email": "r.shkabko@flamix.email",
            "homepage": "https://ru.flamix.software",
            "role": "Developer"
        }
    ],
    "support": {
        "email": "support@flamix.email",
        "docs": "https://bitbucket.org/flamixapi/base"
    },
    "repositories": [
        {
            "type": "git",
            "url": "git@bitbucket.org:flamixapi/base.git"
        }
    ],
    "require": {
        "php": ">=5.3.0 | 7.x",
        "flamix/base": "dev-master",
        "flamix/kassa": "dev-master"
    },
    "autoload":{
        "psr-4":{
            "Local\\":"Local"
        }
    },
    "config": {
        "secure-http": false
    }
}
```

в консоле composer install

Создать папочку /local/classes/Local/ и /local/classes/Events/ и в /local/php_interface/init.php прописать

```php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/local/classes/vendor/autoload.php');
\Flamix\Base\Helpers::registerEvents();
```

## Работа с пользовательскими полями ##

```php
//Вывод имени пользовательского поля
\Local\Basic\UFields::initByCode('UF_TASK_FILIYA')->getName();

//Инициализация
\Local\Basic\UFields::initByCode('UF_TASK_FILIYA');

//Имя
\Local\Basic\UFields::getName();
//Код
\Local\Basic\UFields::getCode();
//Тип (string, date etc)
\Local\Basic\UFields::getType();
//Множественное или нет
\Local\Basic\UFields::isMultiple();
//Берем значения (если список)
\Local\Basic\UFields::getVal();
//Вывод ID значения по его XML_ID
\Local\Basic\UFields::getValID( 'GOROXYV' );
//Значение по умолчанию
\Local\Basic\UFields::getDefault();


\Local\Basic\UFields::initByID(222);
....
```

# Поддержка плагина #

```bash
cd local/classes/vendor/flamix/base
git init
git checkout -b master
git add .
git commit -m "Что ты сделал"
git remote add origin https://rshkabko@bitbucket.org/flamixapi/base.git
git push -u origin --all
```