## Introduce
`CatchAdmin` is a background management system based on secondary development of [Laravel](https://laravel.com) and [Element Plus](https://element-plus.org). The `Laravel` community also has many excellent background management systems, such as `Nova`, an official product, which is of course charged, and `Filament` based on `Livewire` is free, and `Laravel Admin` has to be said. `CatchAdmin` still adopts the traditional front-end and back-end separation strategy, and the `Laravel` framework is only exported as `Api`. Coupling between management system modules is minimized. Each module has independent controllers, routes, models, and data tables. In terms of development, the influence between modules is minimized as much as possible, which reduces the difficulty of development. Based on `CatchAdmin`, systems such as `CMS`, `CRM`, `OA`, etc. can be developed. It also encapsulates many practical tools to enhance the development experience.

[Chinese](./README.md)|[English](./README-en.md)

## Function
- [x] User management Background
- [x] Department Management Configure the company's department structure, support tree structure
- [x] Position Management Configure the position of background users
- [x] Menu Management Configure system menus, buttons, etc.
- [x] Role management Configure user roles and assign permissions
- [x] Operation log Background user operation records
- [x] Login log The login record of background system users
- [x] Code Generation Generate CURD operations on the API side
- [x] Schema management Generate table structure
- [x] module management system

## Project Address
- [github catch admin](https://github.com/jaguarjack/catch-admin)
- 
## Document Address
- [Document Address](https://catchadmin.com/docs/3.0/intro)

## Preview
![zRrjNd.png](https://i.imgtg.com/2023/02/16/dASpg.png)
![zRsAEQ.png](https://i.imgtg.com/2023/02/16/dAsKK.png)
![zRsUv6.png](https://i.imgtg.com/2023/02/16/dA0fB.png)
![zRsV4s.png](https://i.imgtg.com/2023/02/16/dAd5s.png)

## Demo Address
[demo address](https://v3.catchadmin.com)
- Account: `catch@admin.com`
- Password: `catchadmin`

## Sponsorship
If the project helps you, or saves you development time at work. If you can, you can support the `Catchadmin` project, thank you very much 🙏
<img src="https://i.imgtg.com/2023/02/16/dAV0a.jpg" width = "200" alt="support"/>

## Specification
###PHP
Use fixer for code checking, please refer to the specifications of the `.php-cs-fixer.dist.php` file in the root directory for details, and the following two steps are required
```shell
mkdir path
```
```shell
composer require --working-dir=path friendsofphp/php-cs-fixer
```
After the installation is complete, you can use
```shell
composer cs
```
Format the code, this command will directly modify the file to complete the correction, if you only need to check whether the format is correct, then use
```shell
composer cs-diff
```

## Thanks 🙏
> Ranked in no particular order

- [Laravel](https://laravel.com)
- [Vue](https://cn.vuejs.org/)
- [ElementPlus](https://element-plus.org)
- [Docusaurus](https://docusaurus.com)
- [JetBrains](https://www.jetbrains.com/)
