
# key-value User option for Laravel

 * Simple key-value storage for Users (Laravel)
 
## Install
```composer require kagatan/user-options```

## Usage

```php

use Kagatan\UserOption\UserOption;

$user_id = 777;
$key = 'test_key';
$value = 'test_value';

$data = [
  'test_key_a_1' => 'test_value_a_1',
  'test_key_a_2' => 'test_value_a_2',
  'test_key_b_1' => 'test_value_b_1',
  'test_key_b_2' => 'test_value_b_2'
];

// Set key => value for User
UserOption::set($user_id, $key, $value);

// Get value by key for User
$option = UserOption::get($user_id, $key);
dump($option);

// Remove by key for User
UserOption::remove($user_id, $key);

// Set collection [key => value] for User
UserOption::set($user_id, $data);

// Get all options for User
$options = UserOption::getAll($user_id);
dump($options);

// Get options by condition for User
$options = UserOption::getAll($user_id, 'test_key_a%');
dump($options);

```
