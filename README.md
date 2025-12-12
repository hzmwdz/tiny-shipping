# TinyShipping

## 安装

```bash
composer require hzmwdz/tiny-shipping
```

## 发布配置

```bash
php artisan vendor:publish --tag="tiny-shipping-config"
```

## 发布数据

```bash
php artisan vendor:publish --tag="tiny-shipping-database"
```

## 发布翻译

```bash
php artisan vendor:publish --tag="tiny-shipping-translations"
```

## 示例

```php
$data = [
    'shipping_carrier_id' => 1,
    'weight_kg' => 8,
    'region_level_1_id' => 3501,
    'region_level_2_id' => 3502,
    'region_level_3_id' => 3506,
];

$action = new CalculateShippingAction();

$cost = $action->execute($data);

var_dump($cost); // 36
```

## License

The MIT License (MIT)
