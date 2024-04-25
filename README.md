## 介绍
`CatchAdmin`是一款基于[Laravel](https://laravel.com)和[Element Plus](https://element-plus.org)二次开发而成后台管理系统。`Laravel` 社区也有许多非常优秀的后台管理系统，例如 `Nova`, 官方出品，当然是收费的，免费的有基于 `Livewire` 的 `Filament`，还有不得不说的 `Laravel Admin`。`CatchAdmin` 还是采用传统的前后端分离策略，`Laravel` 框架仅仅作为 `Api` 输出。将管理系统模块之间的耦合降到了最低限度。每个模块之间都有独立的控制器，路由，模型，数据表。在开发上尽可能将模块之间的影响降到最低，降低了开发上的难度。基于 `CatchAdmin `可以开发 `CMS`，`CRM`，`OA` 等 等系统。也封装了很多实用的工具，提升开发体验。

[中文](./README.md)|[英文](./README-en.md)
## 其他版本
- [tp8 新版本](https://gitee.com/catchamin/catchadmin-tp)
- [webman 高性能版本](https://gitee.com/catchamin/catchadmin-webman)

## 新功能
[动态表单](https://catchadmin.com/docs/3.0/front/catch-form)
[动态表格](https://catchadmin.com/docs/3.0/front/catch-table)

## 专业版
[专业版本官方地址](https://license.catchadmin.com)

首先感谢一直以来对 `CatchAdmin` 开源项目的支持和使用。作为一名开源工作者，我一直致力于开发出功能强大且易于使用的后台管理系统，以帮助您简化业务流程和提升工作效率。然而，由于某些原因，我不得不做出一些调整。为了能够继续开发和维护这个项目，我将推出一款付费的后台管理系统，以确保我能够持续为您提供高质量的服务和支持。

专业版本不会在开源版本做一些破坏性变更，所以当您从开源版本切换到专业版本，不会有任何开发心智负担。但是使用专业版本会有新的组件来配合您的工作。

我深信，付费后台管理系统将为您带来更多的价值和便利，帮助您提升工作效率

## ⚠️Thinkphp 用户注意
由于新版本使用 `Laravel` 开发，所以请使用 `thinkphp` 分支或者 tag2.6.2，thinkphp 版本已经非常稳定了。

## 功能
- [x] 用户管理 后台用户管理
- [x] 部门管理 配置公司的部门结构，支持树形结构
- [x] 岗位管理 配置后台用户的职务
- [x] 菜单管理 配置系统菜单，按钮等等
- [x] 角色管理 配置用户担当的角色，分配权限
- [x] 操作日志 后台用户操作记录
- [x] 登录日志 后台系统用户的登录记录
- [x] 代码生成 生成 API 端的 CURD 操作
- [x] Schema 管理 生成表结构 
- [x] 模块管理 系统模块管理


## 讨论
- 可以提 `ISSUE`，请按照 `issue` 模板提问
- 加入 Q 群 `302266230` 暗号 `catchadmin`。
- 加微信入群，新建🆕

<img src="wechat.png" width="300"/>

## 额外模块
- [CMS 模块](https://github.com/catch-admin/cms)

## 项目地址
- [github catchadmin](https://github.com/jaguarjack/catch-admin)
## 文档地址
- [文档地址](https://catchadmin.com/docs/3.0/intro)
## 预览

![zRrjNd.png](https://i.imgtg.com/2023/02/16/dASpg.png)
![zRsAEQ.png](https://i.imgtg.com/2023/02/16/dAsKK.png)
![zRsUv6.png](https://i.imgtg.com/2023/02/16/dA0fB.png)
![zRsV4s.png](https://i.imgtg.com/2023/02/16/dAd5s.png)

## 体验地址
[demo 地址](https://v3.catchadmin.com)
- 账户: `catch@admin.com`
- 密码: `catchadmin`

## 视频教程(😂记得一键三连哦)
- [catchadmin 安装](https://www.bilibili.com/video/BV1eY411v71J/)
- [catchadmin 开发之模块创建](https://www.bilibili.com/video/BV1jP41127aW/)
- [catchadmin 之快速开发](https://www.bilibili.com/video/BV1Qh4y1J7eB/)

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

## 感谢🙏
> 排名不分先后

- [Laravel](https://laravel.com)
- [Vue](https://cn.vuejs.org/)
- [ElementPlus](https://element-plus.org)
- [Docusaurus](https://docusaurus.com)
- [JetBrains](https://www.jetbrains.com/)


