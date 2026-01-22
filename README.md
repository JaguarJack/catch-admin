<br />
<div align="center">
    <img src="https://image.catchadmin.com/202601101626397.png" alt="logo" width="300"/>
    <h1 style="font-size:36px;font-weight:600;margin:0 0 6px 0;
  background:linear-gradient(
    120deg,
    #42a5f5 0%,
    #6a8dff 25%,
    #42a5f5 50%,
    #5c6bc0 75%,
    #42a5f5 100%
  );
  color:transparent;
  background-clip:text;
  -webkit-background-clip:text;
">CatchAdmin</h1>
    <p style="font-size: 17px;color: #6a8bad;margin-bottom: 10px;">五分钟快速搭建内置强大权限系统的功能完备的 PHP 后台管理系统</p>
    <a href="https://catchadmin.com" target="_blank">官网</a> |
    <a href="https://v5.catchadmin.com" target="_blank">演示</a> |
    <a href="https://catchadmin.vip/forum" target="_blank">社区</a> |
   <a href="https://catchadmin.vip/plugins" target="_blank">插件</a> |
    <a href="https://doc.catchadmin.com/" target="_blank">文档</a> |
    <a href="https://gitee.com/catchadmin/catchAdmin" target="_blank">Gitee仓库</a> |
    <a href="https://github.com/JaguarJack/catch-admin" target="_blank">GitHub仓库</a>
</div>
<br />
<p align="center">
    <a href="https://php.net/" target="_blank">
        <img src="https://img.shields.io/badge/PHP-8.2-777bb4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
    </a>
    <a href="https://laravel.com/" target="_blank">
        <img src="https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel&logoColor=white" alt="laravel">
    </a>
    <a href="https://vuejs.org/" target="_blank">
        <img src="https://img.shields.io/badge/Vue-3.x-42b883?style=for-the-badge&logo=vuedotjs&logoColor=white" alt="vue">
    </a>
    <a href="https://element-plus.org/" target="_blank">
      <img src="https://img.shields.io/badge/Element%20Plus-UI-409EFF?style=for-the-badge&logo=element&logoColor=white" alt="Element Plus">
    </a>
    <a href="https://vitejs.dev/" target="_blank">
        <img src="https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
    </a>
    <a href="https://httpd.apache.org/" target="_blank">
      <img src="https://img.shields.io/badge/Apache-2.0-D22128?style=for-the-badge&logo=apache&logoColor=white" alt="Apache 2.0">
    </a>
</p>

## 介绍
`CatchAdmin` 是一款基于 [Laravel 12.x](https://laravel.com) 与 [Vue3](https://vuejs.org/) 二次开发的 PHP 开源后台管理系统，采用前后端分离架构，面向企业级后台场景提供开箱即用的基础能力与可扩展的模块化框架。系统内置 Token 鉴权、权限管理（菜单/按钮/数据权限）、动态路由、动态表格、分页封装、资源权限控制、上传/下载、代码生成器（支持一键导入/导出）、数据回收站、附件管理等功能，覆盖后台系统从安全、权限到效率开发的常见需求。

在架构设计上，`Laravel` 仅作为 `API` 服务层对外输出，尽可能弱化业务模块之间的耦合关系。每个模块均具备独立的控制器、路由、模型与数据表结构，支持按模块拆分、按需加载与独立演进，从而降低开发复杂度，提高可维护性与迭代效率。同时，项目封装了大量通用能力与开发工具（如统一响应、异常处理、分页与资源封装等），让业务开发更聚焦、更高效。

基于 `CatchAdmin`，你可以快速搭建 `CMS`、`CRM`、`OA` 等各类管理系统，并在稳定的基础设施之上持续扩展业务模块，满足不同规模团队的开发与交付需求。

[中文](./README.md)|[英文](./README-en.md)

## 快速开始
极速安装项目，五分钟即可构建内置强大权限系统的功能完备的 PHP 后台管理系统
```shell
composer create catchadmin/catchadmin

cd catchadmin

php artisan catch:install
```

## 功能

- ☑️ **用户管理**：支持用户新增/编辑/删除/禁用、密码重置与基础信息维护；不同用户登录后台可呈现不同首页与可见功能模块
- ☑️ **部门管理**：支持公司/部门/小组多级组织架构配置与维护，树形结构展示，支持层级调整与人员归属管理
- ☑️ **岗位管理**：岗位（职务）统一维护与分配，支持主岗位/多岗位配置，为权限控制与业务流程提供身份基础
- ☑️ **角色管理**：树结构角色体系，支持角色菜单权限、按钮级权限分配与数据权限配置，满足精细化授权需求
- ☑️ **菜单管理**：可视化配置系统菜单、路由与按钮资源，支持排序/层级/隐藏等管理，实现前后端一致的权限控制
- ☑️ **字典管理**：集中维护枚举/状态/类型等基础数据，支持分组与启用禁用，便于统一复用并减少硬编码
- ☑️ **系统配置**：系统常用参数集中管理，支持分类维护与动态读取，配置调整可快速生效，降低运维与二开成本
- ☑️ **操作日志**：记录关键操作与变更轨迹，支持按用户/模块/时间多维检索，便于审计追溯与问题定位
- ☑️ **登录日志**：记录登录历史与访问信息（如时间/IP/设备等，按实现为准），支持查询统计与异常排查
- ☑️ **文件上传**：统一上传能力，支持 `本地`、`七牛云`、`阿里云`、`腾讯云` 等存储方式，按配置灵活切换
- ☑️ **附件管理**：对系统上传的文件/图片等资源集中管理，支持检索、预览与清理维护，避免资源冗余
- ☑️ **数据表维护**：支持数据表碎片清理与优化，并提供数据回收与销毁管理能力，保障系统长期稳定运行
- ☑️ **代码生成**：一键生成前后端代码（`php`、`vue`）及数据库迁移文件，支持直接生成到模块，显著提升开发效率
- ☑️ **支持 Vue 即时渲染**：支持前端 Vue 即时渲染，无需编译即可生效，加快开发调试与迭代速度
- ☑️ **支持插件系统**：插件即 Composer 包，深度融入 Composer 生态，支持模块化扩展与快速集成
    - 文档：[CatchAdmin 插件快速开始](https://doc.catchadmin.com/docs/5.0/plugin/quickstart)

## 前端项目
由于是完全分离的项目，所有 CatchAdmin 有专门的前端项目仓库，如下
[catchadmin-vue](https://gitee.com/catchadmin/catch-admin-vue)

## 体验地址
[demo 地址](https://v5.catchadmin.com)

[超管账户]
- 账户: `catch@admin.com`
- 密码: `catchadmin`

[测试账户]
- 账户: `test@admin.com`
- 密码: `Testadmin1`

## 讨论
- 可以提 `ISSUE`，请按照 `issue` 模板提问
- 添加微信好友，加微信入群，备注 `catchadmin`

<img src="./resources/screenshoots/wechat.png" width="200"/>

## 版权信息
`CatchAdmin` 遵循 Apache2.0 开源协议发布，提供无需授权的免费使用。商业使用需要保留版权信息即可

## 项目预览
|                                                |                                                     |
|------------------------------------------------|-----------------------------------------------------|
| ![登录](./resources/screenshoots/login.png)      | ![控制台](./resources/screenshoots/dashboard.png)      |
| ![权限](./resources/screenshoots/permission.png) | ![布局](./resources/screenshoots/layouts.png)         |
| ![上传](./resources/screenshoots/upload.png)     | ![代码生成](./resources/screenshoots/code_generate.png) |
| ![菜单](./resources/screenshoots/menu.png)       | ![模板](./resources/screenshoots/template.png)        |


## 视频教程
- [catchadmin 安装](https://www.bilibili.com/video/BV1eY411v71J/)
- [catchadmin 开发之模块创建](https://www.bilibili.com/video/BV1jP41127aW/)
- [catchadmin 之快速开发](https://www.bilibili.com/video/BV1Qh4y1J7eB/)

## 配套相关资源
- [Laravel 中文文档](https://laravel-docs.catchadmin.com/)
- [Laravel 免费入门教程](https://laravel-study.catchadmin.com)
- [Laravel Livewire 中文文档](https://laravel-livewire.catchadmin.com/)

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


