# Template
php Template View

```php
interface ViewInterface
{
    public static function render(string $source, array $data = []);

    public static function display(string $source, array $data = []);
}
```
示例代码：tests目录