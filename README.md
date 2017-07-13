### 数组转ini文件
将php数组转为ini文件

####
```php
[
	"name" => "yaconf",
	"year" => 2017,
	"features" => [
		"fast",
		"light",
		"plus" => "zero-copy",
		"constant" => "7.1.1"
	]
]
```

转换为

```ini
name="yaconf"
year="2017"
[features]
0="fast"
1="light"
plus="zero-copy"
constant="7.1.1"
```
