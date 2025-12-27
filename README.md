## 介绍
### 这是 catchadmin V5 版本
`CatchAdmin`是一款基于[Laravel 12.x](https://laravel.com)和[Element Plus](https://element-plus.org)二次开发而成的 PHP 开源后台管理系统。`Laravel` 社区也有许多非常优秀的后台管理系统，例如 `Nova`, 官方出品，当然是收费的，免费的有基于 `Livewire` 的 `Filament`，还有不得不说的 `Laravel Admin`。它采用前后端分离架构，CatchAdmin 集成了 Token 鉴权、权限管理、动态路由、动态表格、分页封装、资源权限、上传下载、代码生成器支持一键导出导入，数据回收站，附件管理的一款模块化框架。`Laravel` 框架仅仅作为 `Api` 输出。将管理系统模块之间的耦合降到了最低限度。每个模块之间都有独立的控制器，路由，模型，数据表。在开发上尽可能将模块之间的影响降到最低，降低了开发上的难度。基于 `CatchAdmin `可以开发 `CMS`，`CRM`，`OA` 等 等系统。也封装了很多实用的工具，提升开发体验。
## 参与 Gitee 年度最佳 WEB 项目活动
我正在参加 Gitee 2025 最受欢迎的开源软件投票活动，[**快来给我投票吧！**](https://gitee.com/activity/2025opensource?ident=IRBNBA) :joy: 

## 前端项目
[catchadmin-vue](https://gitee.com/catchadmin/catch-admin-vue)

## Laravel 入门教程
[Laravel 免费入门教程](https://laravel-study.catchadmin.com)

[中文](./README.md)|[英文](./README-en.md)
## 其他版本
- [tp8 独立版本](https://gitee.com/catchamin/catchadmin-tp)
- [webman 独立高性能版本](https://gitee.com/catchamin/catchadmin-webman)

## 新功能
- [动态表单](https://doc.catchadmin.com/docs/5.0/front/catch-form)
- [动态表格](https://doc.catchadmin.com/docs/5.0/front/catch-table)

## 专业版
[专业版本官方地址](https://license.catchadmin.com)

首先感谢一直以来对 `CatchAdmin` 开源项目的支持和使用。作为一名开源工作者，我一直致力于开发出功能强大且易于使用的后台管理系统，以帮助您简化业务流程和提升工作效率。然而，由于某些原因，我不得不做出一些调整。为了能够继续开发和维护这个项目，我将推出一款付费的后台管理系统，以确保我能够持续为您提供高质量的服务和支持。

专业版本不会在开源版本做一些破坏性变更，所以当您从开源版本切换到专业版本，不会有任何开发心智负担。但是使用专业版本会有新的组件来配合您的工作。

我深信，付费后台管理系统将为您带来更多的价值和便利，帮助您提升工作效率

## 功能
- ☑️**用户管理** 完成用户添加、修改、删除配置，支持不同用户登录后台看到不同的首页
- ☑️**部门管理** 部门组织机构（公司、部门、小组），树结构展现
- ☑️**岗位管理** 可以给用户配置所担任职务
- ☑️**角色管理** 树结构设计，支持角色菜单和按钮权限分配，支持角色数据权限分配、强大的角色管理体系
- ☑️**菜单管理** 配置系统菜单和按钮等
- ☑️**字典管理** 对系统中经常使用并且固定的数据可以重复使用和维护
- ☑️**系统配置** 系统的一些常用设置管理
- ☑️**操作日志** 用户对系统的一些正常操作的查询
- ☑️**登录日志** 用户登录系统的记录查询
- ☑️**文件上传** 支持`本地`、`七牛云`、`阿里云`、`腾讯云`
- ☑️**附件管理** 管理当前系统上传的文件及图片等信息
- ☑️**数据表维护** 对系统的数据表可以进行清理碎片和优化，并且管理所有数据的回收和销毁
- ☑️**代码生成** 前后端代码的生成（php、vue、 数据库迁移），支持一键生成到模块
- ☑️**支持 Vue 即时渲染** 支持前端 Vue 即时渲染 无需编译
- ☑️**支持插件系统** [CatchAdmin 插件](https://doc.catchadmin.com/docs/5.0/plugin/quickstart)即 Composer 包，无需再学一次插件开发，完全绑定 composer 生态


## 讨论
- 可以提 `ISSUE`，请按照 `issue` 模板提问
- 添加微信好友，加微信入群，备注 `catchadmin`

<img src="wechat.png" width="200"/>

## 项目地址
- [github catchadmin](https://github.com/jaguarjack/catch-admin)
## 文档地址
- [文档地址](https://catchadmin.com/docs/3.0/intro)
## 预览

![CatchAdmin 登录](https://image.catchadmin.com/202512151142046.png)
![CatchAdmin 首页](https://image.catchadmin.com/202512150841525.png)
![CatchAdmin 权限](https://image.catchadmin.com/202512151143109.png)
![CatchAdmin 布局](https://image.catchadmin.com/202512151144233.png)

## 体验地址
[demo 地址](https://pro.catchadmin.com)
- 账户: `catch@admin.com`
- 密码: `catchadmin`

## 视频教程(😂记得一键三连哦)
- [catchadmin 安装](https://www.bilibili.com/video/BV1eY411v71J/)
- [catchadmin 开发之模块创建](https://www.bilibili.com/video/BV1jP41127aW/)
- [catchadmin 之快速开发](https://www.bilibili.com/video/BV1Qh4y1J7eB/)

## 规范
### PHP
使用 Laravel pint 规范代码格式
```shell
composer format                                                                                      
```
使用 PHPstan 做静态检查
```shell
composer analyse
```

## 感谢🙏
> 排名不分先后

- [Laravel](https://laravel.com)
- [Vue](https://cn.vuejs.org/)
- [ElementPlus](https://element-plus.org)
- [VitePress](https://vitepress.dev/zh/)
- [JetBrains](https://www.jetbrains.com/)


