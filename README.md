# think-admin

# install
- composer config -g repo.packagist composer https://packagist.laravel-china.org
- composer update
- 配置 config/database.php 数据库配置
- php think migrate:run
- php think seeds:run

# Use
- 配置虚拟域名 OR 在根目录下执行 php think run
- yourUrl/login
- 默认用户名 admin 密码 admin
