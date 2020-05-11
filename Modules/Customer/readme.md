# Module: Customer

## Install

- Change `App\Users` model by this code:

```php
protected $fillable = [
    'name',
    'last_name',
    'email',
    'password',
    'mobile',
    'address',
];
```