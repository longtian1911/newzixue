### 一、php系统函数

strlen：int strlen ( string `$string` )   返回字符串的长度，如果为空则返回0

strtolower ：string strtolower ( string `$string` ) 中所有的字母字符转换为小写并返回。

strtoupper：string strtoupper ( string `$string` ) 将字符串转化为大写

str_replace:字符串替换函数

ucfirst ：将字符串的首字母转换为大写

ucwords ：将字符串中每个单词的首字母转换为大写

strpos： 查找字符串首次出现的位置

strrpos ： 查找字符串最后一次出现的位置

strrev ：反转字符串

str_shuffle:随机打乱字符串

substr：字符串截取

Explode:使用一个字符串分割另一个字符串 拆分后是一个数组

implode：将一个一位数组连接成字符串

sprintf：格式化字符串

### 二、数学函数

ceil：向上取整

floor：向下取整

pow：指数运算

sqrt：平方根

max：最大值

min：最小值

rand：生成随机数

mt_rand：生成随机数

round：四舍五入函数

number_format:将以千位分隔符方式格式化数字(会四舍五入)

fmod：将返回除法的浮点数余数

### 三、日期时间函数

date：格式化一个本地时间（需要对时区进行设置）

date_default_timezone_set：设置默认时区 也可以在php.ini  date.timezone 修改

date_default_timezone_get:获取默认时区

time：获取时间戳

strtotime：返回各个日期的时间戳，相对比time函数好用

microtime：获取更加精准的时间戳，有一个可选的布尔参数值

uniqid：生成唯一id

getdate：获取日期时间函数