## Introduction
### This is CatchAdmin V5
`CatchAdmin` is a PHP open-source backend management system built upon [Laravel 12.x](https://laravel.com) and [Element Plus](https://element-plus.org). The Laravel community has many excellent backend management systems, such as the officially produced `Nova` (which is paid), free options like `Filament` based on `Livewire`, and the notable `Laravel Admin`. CatchAdmin adopts a frontend-backend separation architecture and integrates Token authentication, permission management, dynamic routing, dynamic tables, pagination encapsulation, resource permissions, upload/download functionality, a code generator with one-click export/import, data recycle bin, and attachment management—all in a modular framework. The `Laravel` framework serves solely as an `API` output layer. It minimizes coupling between management system modules to the greatest extent possible. Each module has independent controllers, routes, models, and data tables, significantly reducing development complexity and the impact modules have on each other. Based on `CatchAdmin`, you can develop systems like `CMS`, `CRM`, `OA`, etc. It also encapsulates many practical tools to enhance the development experience.

## Participating in Gitee's Annual Best WEB Project Event
I am participating in Gitee's 2025 Most Popular Open-Source Software voting event. [**Come vote for me!**](https://gitee.com/activity/2025opensource?ident=IRBNBA) :joy:

## Frontend Project
[catchadmin-vue](https://gitee.com/catchadmin/catch-admin-vue)

## Laravel Tutorials
[Free Laravel Tutorials](https://laravel-study.catchadmin.com)

[Chinese](./README.md)|[English](./README-en.md)

## Other Versions
- [ThinkPHP 8 Independent Version](https://gitee.com/catchamin/catchadmin-tp)
- [Webman Independent High-Performance Version](https://gitee.com/catchamin/catchadmin-webman)

## New Features
- [Dynamic Forms](https://doc.catchadmin.com/docs/5.0/front/catch-form)
- [Dynamic Tables](https://doc.catchadmin.com/docs/5.0/front/catch-table)

## Professional Edition
[Professional Edition Official Website](https://license.catchadmin.com)

First of all, thank you for your ongoing support and use of the `CatchAdmin` open-source project. As an open-source developer, I have always been committed to creating a powerful and easy-to-use backend management system to help you streamline business processes and improve work efficiency. However, due to certain reasons, I've had to make some adjustments. To continue developing and maintaining this project, I will be launching a paid backend management system to ensure I can continue providing you with high-quality service and support.

The Professional Edition will not introduce breaking changes to the open-source version, so transitioning from the open-source to the professional version will not involve any developmental overhead. However, the Professional Edition will offer new components to enhance your workflow.

I am confident that the paid backend management system will bring you more value and convenience, helping to boost your work efficiency.

## Features
- ☑️ **User Management**: Complete user addition, modification, deletion, and configuration. Supports different users seeing different homepages upon login.
- ☑️ **Department Management**: Department organizational structure (company, department, group) presented in a tree structure.
- ☑️ **Position Management**: Configure positions for users.
- ☑️ **Role Management**: Tree-structured design, supports menu and button permission assignment for roles, supports data permission assignment for roles, and a robust role management system.
- ☑️ **Menu Management**: Configure system menus, buttons, etc.
- ☑️ **Dictionary Management**: Manage and reuse frequently used, fixed data within the system.
- ☑️ **System Configuration**: Manage common system settings.
- ☑️ **Operation Logs**: Query users' normal operations within the system.
- ☑️ **Login Logs**: Query records of user logins to the system.
- ☑️ **File Upload**: Supports `Local`, `Qiniu Cloud`, `Alibaba Cloud`, `Tencent Cloud`.
- ☑️ **Attachment Management**: Manage files and images uploaded by the current system.
- ☑️ **Data Table Maintenance**: Perform defragmentation and optimization on system data tables, and manage data recycling and destruction.
- ☑️ **Code Generator**: Generate frontend and backend code (PHP, Vue, database migrations). Supports one-click generation into modules.
- ☑️ **Supports Vue Instant Rendering**: Supports frontend Vue instant rendering without compilation.
- ☑️ **Supports Plugin System**: [CatchAdmin Plugins](https://doc.catchadmin.com/docs/5.0/plugin/quickstart) are Composer packages. No need to relearn plugin development; fully integrated with the Composer ecosystem.

## Discussion
- You can raise an `ISSUE`. Please follow the issue template when asking questions.
- Add WeChat as a friend for group entry. Note `catchadmin` when adding.

<img src="wechat.png" width="200"/>

## Project Links
- [GitHub catchadmin](https://github.com/jaguarjack/catch-admin)

## Documentation
- [Documentation](https://catchadmin.com/docs/3.0/intro)

## Preview

![CatchAdmin Login](https://image.catchadmin.com/202512151142046.png)
![CatchAdmin Homepage](https://image.catchadmin.com/202512150841525.png)
![CatchAdmin Permissions](https://image.catchadmin.com/202512151143109.png)
![CatchAdmin Layout](https://image.catchadmin.com/202512151144233.png)

## Demo
[Demo Link](https://pro.catchadmin.com)
- Account: `catch@admin.com`
- Password: `catchadmin`

## Video Tutorials (😂 Remember to like, share, and subscribe!)
- [CatchAdmin Installation](https://www.bilibili.com/video/BV1eY411v71J/)
- [CatchAdmin Development: Module Creation](https://www.bilibili.com/video/BV1jP41127aW/)
- [CatchAdmin: Rapid Development](https://www.bilibili.com/video/BV1Qh4y1J7eB/)

## Standards
### PHP
Uses Laravel Pint for code formatting.
```shell
composer format
```
Uses PHPStan for static analysis.
```shell
composer analyse
```

## Acknowledgements 🙏
> Listed in no particular order.

- [Laravel](https://laravel.com)
- [Vue](https://cn.vuejs.org/)
- [ElementPlus](https://element-plus.org)
- [VitePress](https://vitepress.dev/zh/)
- [JetBrains](https://www.jetbrains.com/)
