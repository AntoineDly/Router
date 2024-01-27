# AntoineDly\Router

A simple router


### Requirements

- AntoineDly\Router `^1.0` works with PHP 8.2 or above.

### Author

Antoine Delaunay - <antoine.delaunay333@gmail.com> - [Twitter](http://twitter.com/AntDlny)<br />

### License

AntoineDly\Router is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

### Acknowledgements

This library is heavily inspired by [Guzzle/PSR-7](https://github.com/guzzle/psr7)

### Contributing

If you want to contribute, make sure to run those 3 steps before submitting a PR : 

- Run static tests :
```php
tools/phpstan/vendor/bin/phpstan analyse src tests --level=9
```

- Run fixer :
```php
tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src
```

- Run tests :
```php
vendor/bin/phpunit tests
```