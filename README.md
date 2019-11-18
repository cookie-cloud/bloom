一 、使用

```
require_once __DIR__ . '/autoload.php';

use Bloom\BloomFilterRedis;

$key = 'test';

$hash_func = ['BKDRHash', 'DJBHash', 'JSHash'];

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->auth('1234');

$bloom = new BloomFilterRedis($key, $hash_func, $redis);

$bloom->add('abc');
$bloom->add('bac');
$bloom->add('cba');

var_dump($bloom->exists('abc'));
var_dump($bloom->exists('bac'));
var_dump($bloom->exists('cab'));
```
