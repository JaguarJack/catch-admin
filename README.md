## About CatchAdmin


## 安装
```shell
yarn install

yarn dev
```

```
composer install

php artisan serve
```
## 规范
### PHP
使用 fixer 进行代码检查, 具体请查看根目录下 `.php-cs-fixer.dist.php` 文件的规范，还需要进行以下两步骤
```shell
mkdir path
```
```shell
composer require --working-dir=path friendsofphp/php-cs-fixer                                                                                      
```
安装完成之后可以使用
```shell
composer cs
```
进行代码格式化，这个命令会直接修改文件完成修正，如果只需要查看格式是否正确,那么使用
```shell
composer cs-diff
```
会列出不符合的代码格式


