csv2json
=========
It's a simple liblary to convert csv to json string.

Simple usage
---------
```php
    $csv = file_get_content('example.csv');
    require_once('csv2json.php');
    $obj = new csv2json();
    $json = $obj->getJson($csv);
```
You can change confing by typing:
```php
    $obj->setValue('fieldSeparator', ';');
```
Or by giving config array in constructior:
```php
    $configArray = array(
        'fieldsSeparator'   =>  ';',
        'firstRowAsKey'     =>  false
    );
    $obj = new csv2json($configArray);
```
Avalible config values:
+   (string)fieldSeparator - field separator - default ','
+   (string)rowSeparator - row separator - default ';'
+   (string)avoidFieldsNumber - fields that will be omitted when generating, separated by comma - default 'none'
+   (boolean)firstRowAsKey - if true first csv line will be used as values keys - default 'true'
