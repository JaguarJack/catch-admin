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
- [文档地址](https://github.com/catch-admin/document)[个人精力实在有限,希望可以小伙伴们可以一起维护文档]
## 预览
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wucNXq.md.png">
</p>
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wucm6I.md.png">
</p>
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wucZpd.md.png">
</p>
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wuce1A.md.png">
</p>
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wucnXt.md.png">
</p>
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wucKnP.md.png">
</p>
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wuc3tg.md.png">
</p>
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wucM0f.md.png">
</p>
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wucQ78.md.png">
</p>
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wuc1AS.md.png">
</p>
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wuc8hQ.md.png">
</p>
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wucY1s.md.png">
</p>
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wucJpj.md.png">
</p>
<p align="center">
    <img src="https://s1.ax1x.com/2020/09/07/wuctcn.md.png">
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

#### 下载项目
- 通过 Git 下载(推荐)
```shell
git clone https://gitee.com/jaguarjack/catchAdmin && cd catchAdmin

curl -sS https://install.phpcomposer.com/installer | php

composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

composer install

```
- composer 安装
```shell
composer create-project jaguarjack/catchadmin:dev-master
```

#### 安装
下载完成之后通过命令来进行安装, 一键安装 🚀
```shell
 php think catch:install 
```

## 体验地址

[体验地址](http://vue.catchadmin.com)
- 账号: admin@gmail.com
- 密码: admin

[catchadmin 文档地址](http://doc.catchadmin.com)

### 系列文章
如果是刚开始使用 thinkphp6, 以下文章可能会对你有些许帮助，文章基于 RC3 版本。整体架构是不变的。
- [Tp6 启动分析](https://www.kancloud.cn/akasishikelu/thinkphp6/1129385)
- [Tp6 Request 解析](https://www.kancloud.cn/akasishikelu/thinkphp6/1134496)
- [TP6 应用初始化](https://www.kancloud.cn/akasishikelu/thinkphp6/1130427)
- [Tp6 中间件分析](https://www.kancloud.cn/akasishikelu/thinkphp6/1136616)
- [Tp6 请求流程](https://www.kancloud.cn/akasishikelu/thinkphp6/1136608)

### Donate
如果你觉得项目对你有帮助，可以请作者喝杯咖啡☕️！鼓励下
<img src="https://cdn.learnku.com/uploads/images/202008/11/18206/e6qAAM8Bod.jpg!large">

### Talking
- [论坛讨论](http://bbs.catchadmin.com)
- 可以提 `ISSUE`，请按照 `issue` 模板提问
- 加入 Q 群 `302266230` 讨论以及反馈一些问题。
    - 加群需要付费，所以请使用能支持群费的客户端。(不喜勿喷，过滤一部分不看文档和 TP 框架文档并且衣来伸手饭来张口的用户)
    - 不建议你付费入群，认真阅读文档可以解决所有问题
    - 更愿意以 `ISSUE` 的方式提问
    - 付费入群，群里的各位也是没有义务回答各种各样的基础问题。请 GOOGLE。

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
