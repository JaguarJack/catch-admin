<p align="center">
    <img src="https://cdn.learnku.com/uploads/images/202005/17/18206/zSuf7Ce5kM.png!large">
</p>


<p align="center"><code>CatchAdmin</code>是一款基于<a href="http://www.thinkphp.cn/" target="_blank">thinkphp framework</a>和
<a href="https://pro.loacg.com/docs/getting-started">ant degisn pro vue</a>二次开发而成的后台管理系统，采用了目前趋势的前后端分离开发模式，后端仅需要提供简洁的 API 数据结构，前端负责呈现数据。目前前端采用数据驱动，大大提高了开发效率。这不仅仅是一个项目，更是后端更新技术栈的一次实践</p>

<p align="center">
<a href="http://doc.catchadmin.com/">文档</a> |
<a href="http://vue.catchadmin.com">演示地址</a> |
<a href="http://apidoc.catchadmin.com">接口文档</a> |
<a href="https://gitee.com/jaguarjack/catchAdmin">项目源码</a> |
<a href="https://www.kancloud.cn/akasishikelu/thinkphp6">看云分析</a> 
<a href="#extensions">扩展</a>
</p>

<p align="center">
    <a href="https://gitee.com/jaguarjack/catchAdmin" target="_blank">
        <img src="https://svg.hamm.cn/gitee.svg?type=star&user=jaguarjack&project=catchAdmin"/>
    </a >
    <a href="https://gitee.com/jaguarjack/catchAdmin" target="_blank">
        <img src="https://svg.hamm.cn/gitee.svg?type=fork&user=jaguarjack&project=catchAdmin"/>
    </a >
    <img src="https://svg.hamm.cn/badge.svg?key=Base&value=ThinkPHP6"/>
    <img src="https://svg.hamm.cn/badge.svg?key=Data&value=MySQL5.5"/>
    <img src="https://svg.hamm.cn/badge.svg?key=Runtime&value=PHP7.1"/>
    <img src="https://svg.hamm.cn/badge.svg?key=License&value=Apache-2.0"/>
</p >

## 项目地址
- [github 地址](https://github.com/yanwenwu/catch-admin)
- [gitee 地址](https://gitee.com/jaguarjack/catchAdmin)
- [前端 Vue 项目地址](https://github.com/yanwenwu/catch-admin-vue)

## 预览
<p align="center">
    <img src="https://cdn.learnku.com/uploads/images/202005/17/18206/0ECPy72zUZ.png!large">
</p>
<p align="center">
    <img src="https://cdn.learnku.com/uploads/images/202005/17/18206/ngzSU0A9SI.png!large">
</p>

## 环境要求
- php7.1+ (需以下扩展)
    - [x] mbstring
    - [x] json
    - [x] openssl
    - [x] xml
    - [x] pdo
- nginx
- mysql

### 如何安装
> 安装之前请确保已安装 Composer
```shell
git clone https://gitee.com/jaguarjack/catchAdmin && cd catchAdmin

curl -sS https://install.phpcomposer.com/installer | php

composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

composer install

php think catch:install 

```


## 体验地址

[体验地址](http://vue.catchadmin.com)
- 账号: test@catch.com 
- 密码: 123456

[catchadmin 文档地址](http://doc.catchadmin.com)

[catchadmin 接口文档地址](http://apidoc.catchadmin.com)
- 账号：test@catch.com
- 密码：123456

> 请大家不要随意添加数据，因为没有意义，只看 `catchadmin` 的文档就可以了。
如果有太多脏数据的话，我会关闭该账号。

### 系列文章
如果是刚开始使用 thinkphp6, 以下文章可能会对你有些许帮助，文章基于 RC3 版本。整体架构是不变的。
- [Tp6 启动分析](https://www.kancloud.cn/akasishikelu/thinkphp6/1129385)
- [Tp6 Request 解析](https://www.kancloud.cn/akasishikelu/thinkphp6/1134496)
- [TP6 应用初始化](https://www.kancloud.cn/akasishikelu/thinkphp6/1130427)
- [Tp6 中间件分析](https://www.kancloud.cn/akasishikelu/thinkphp6/1136616)
- [Tp6 请求流程](https://www.kancloud.cn/akasishikelu/thinkphp6/1136608)

### Talking
- [论坛讨论](http://bbs.catchadmin.com)
- 可以提 `ISSUE`，请按照 `issue` 模板提问
- 欢迎进入 Q 群 `302266230` 讨论以及反馈一些问题。

### Thanks
> 排名部分先后

- [top-think/think](https://github.com/top-think/think)
- [ant-design-pro-vue](https://github.com/sendya/ant-design-pro-vue)
- [thans/tp-jwt-auth](https://packagist.org/packages/thans/tp-jwt-auth)
- [workerman/workerman](https://github.com/walkor/Workerman)
- [jaguarjack/think-filesystem-cloud](https://github.com/yanwenwu/think-filesystem-cloud)
- [overtrue/wechat](https://github.com/overtrue/wechat)
- [jaguarjack/migration-generator](https://github.com/yanwenwu/migration-generator)
- [phpoffice/phpspreadsheet](https://github.com/PHPOffice/PhpSpreadsheet)
