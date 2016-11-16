# Компонент для Yii2 для работы с API сервиса MoiZvonki #

# Установка #

```
composer require iiispikeriii/yii2-moizvonki:"@dev"
```

# Настройка компонента для работы с API MoiZvonki #

Для работы необходимо добавить в конфиг компонент

```php
'components' => [    
    ...    
    'moizvonki' => [    
        'class' => 'iiispikeriii\moizvonki\MoizvonkiComponent',    
        'app_name' => 'xxxxxxx',    
        'user_name' => 'xxx@xxx.xx',    
        'api_url' => 'xxx.moizvonki.ru',    
        'api_key' => 'xxx',    
     ],    
     ...    
```

```php
'log' => [    
    'targets' => [    
        [    
          'class' => 'yii\log\FileTarget',    
          'levels' => ['warning'],    
          'categories' => ['moizvonkiError'],    
          'logVars' => ['_GET', '_POST', ],    
          'logFile' => '@app/runtime/logs/moizvonki/errors.log',    
          'maxFileSize' => 1024 * 2,    
          'maxLogFiles' => 5,    
        ]    
    ...    
```

# Использование #
  
```php
Yii::$app->moizvonki->getCrmEvent(strtotime('2016-11-10'))    
```