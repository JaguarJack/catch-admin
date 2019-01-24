# think-admin
# ENV
- php >= 7.1.3
- mysql >= 5.5

# install
- curl -sS http://install.phpcomposer.com/installer | php
- composer config -g repo.packagist composer https://packagist.laravel-china.org
- composer update 
- 修改根目录下 .env.emp .env
- .env 配置数据库信息
- php think migrate:run
- php think seed:run

# Use
- 配置虚拟域名 OR 在根目录下执行 php think run
- yourUrl/login
- 默认用户名 admin 密码 admin

# nginx 配置
```
server {
        listen       端口;
        server_name  域名;

        access_log  logs/wenwen.access.log;

        root 项目目录/public;
        index index.php index.html index.htm;

        location / {
            index  index.php index.html index.htm;

            if (!-e $request_filename) {
                rewrite ^(.*)$ /index.php?s=$1 last;
                break;
            }
        }

        #error_page  404              /404.html;
        error_page   500 502 503 504  /50x.html;
        location = /50x.html {
            root   html;
        }

        location ~ \.php$ {
            root           项目目录/public;
            fastcgi_pass   phpfastcgi;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            include        fastcgi_params;
        }

        location ^~ /data {
                deny all;
        }
    }

```
# Problem
> SQLSTATE[42000]: Syntax error or access violation: 1067 Invalid default value for 'updated_at'

设置 sql_mode;
```
show variables like 'sql_mode' ; 
```
> remove 'NO_ZERO_IN_DATE,NO_ZERO_DATE'
```
SET GLOBAL sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'
```
# Test Address
<a href="http://view.njphper.com" target="__BLANK">测试地址</a>
- 账号：test
- 密码: 123456

# Talking
- 可以提 ISSUE，请按照 issue 模板提问
- 欢迎进入 Q 群，可以及时反馈一些问题。
- ![输入图片说明](https://images.gitee.com/uploads/images/2018/1219/110300_0257b6c0_810218.jpeg "微信图片_20181219105915.jpg")

仅供学习
# License
```
MIT License

Copyright (c) 2018 dkzwm

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```