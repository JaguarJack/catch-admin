apimanager 模块是一个用于API管理、测试的模块。

# 概述

  本模块的设计目标是提供开发人员、产品人员等相关角色，可以管理和测试API，可以将系统内部或外部API信息保存在系统内，使得产品具有自完备的特性和持续交付的特性，并可进行灵活的二次开发。
  
## 主要特性
  
1.  支持API分类管理，支持自定义用户环境变量，支持API测试用例管理。
2.  支持HTTP、HTTPS接口测试用例的在线运行。（更多协议支持规划在模块roadmap中）
3.  支持接口文档管理。
4.  已集成微信第三方平台相关接口测试用例，开发者可快速进行第三方平台应用开发。
5.  支持多帐号多应用使用环境，易于团队协作，不限制接口数量、用户数量、请求数量。
6.  基于catchadmin开发，模块安装简单，使用便捷，支持模块数据导入导出。
7.  开源开放易于二次开发，测试用例可共享，形成产品API知识库。
8.  支持私有化部署、云原生部署。
9.  可视化管理系统路由列表，并可与API测试工具结合可视化测试系统接口。

  演示地址：demo.uctoo.com 控制台使用demo帐号登录
  模块使用界面截图：
  <table>
      <tr>
          <td><img src="https://gitee.com/UCT_admin/materials/raw/master/uctoo_apitester/images/api%20category%20list.png"></td>
      </tr>
      <tr>
          <td><img src="https://gitee.com/UCT_admin/materials/raw/master/uctoo_apitester/images/api%20category%20edit.png"></td>
      </tr>
      <tr>
          <td><img src="https://gitee.com/UCT_admin/materials/raw/master/uctoo_apitester/images/api%20user%20env%20list.png"></td>
      </tr>
      <tr>
          <td><img src="https://gitee.com/UCT_admin/materials/raw/master/uctoo_apitester/images/api%20user%20env%20edit.png"></td>
      </tr>
      <tr>
          <td><img src="https://gitee.com/UCT_admin/materials/raw/master/uctoo_apitester/images/api%20test%20case%20list.png"></td>
      </tr>
       <tr>
          <td><img src="https://gitee.com/UCT_admin/materials/raw/master/uctoo_apitester/images/api%20test%20case%20edit.png"></td>
      </tr>
      <tr>
          <td><img src="https://gitee.com/UCT_admin/materials/raw/master/uctoo_apitester/images/apirun.png"></td>
      </tr>
      <tr>
           <td><img src="https://gitee.com/UCT_admin/materials/raw/master/uctoo_apitester/images/routelist.png"></td>
      </tr>
  </table>

## 产品架构
1.  基于catchadmin标准模块开发方式开发，可在管理后台一键安装模块和初始化模块数据。
2.  前端采用axios技术选型，前端可形成标准客户端接口库。
3.  本地接口（数据源类型local）主要沿用catchadmin基于用户身份的接口鉴权方案，需在API测试用例header添加authorization参数，其值为登录接口返回的值。
4.  在扫码登录后注册用户帐号接口测试用例，演示了采用微信扫码登录后获取到的用户access_token进行接口鉴权的示例。
5.  微信相关开发使用了[uctoo/think-easywechat SDK](https://gitee.com/UCT/think-easywechat) 集成catchadmin (TP6+VUE) 和 easywechat 4，支持微信第三方平台、微信小程序云开发、微信支付服务商等特性。

## 安装教程
  
### 运行环境依赖

  PHP >= 7.1.0     
  Mysql >= 5.5.0 (需支持innodb引擎)  
  PDO PHP Extension     
  MBstring PHP Extension     
  CURL PHP Extension     
  ZIP Extension    
  Composer  
  catchadmin  
    
### 分步骤安装
1.  从https://gitee.com/jaguarjack/catchAdmin 或 https://gitee.com/uctoo/uctoo 下载https://gitee.com/uctoo/uctoo/tree/master/catch/apimanager 目录模块，复制到catchadmin对应目录
2.  apimanager/catch-admin-vue 目录内是模块前端vue项目代码，复制到前端VUE项目对应目录，注意如和原前端vue项目目录的文件有冲突，需自行合并代码版本。
3.  前端package.json文件请谨慎覆盖原项目文件。请使用命令 npm install --save @smallwei/avue ，npm install --save vue-json-editor ，npm install --save vue-json-views 添加模块依赖（等效于手动合并package.json版本）。如模块新依赖了第三方组件，需要在前端项目目录重新运行 yarn install 命令。
3.  登录管理后台，在系统管理->模块管理启用API管理模块，即可安装模块和初始化模块数据。

## 使用手册
1.  可以通过API管理->API分类功能增删改查API分类。
2.  可以通过API环境变量功能增删改查用户环境变量。环境变量的key值以{{key}}方式定义，在API测试用例中对应的{{key}}值将替换为环境变量的value值。每个用户可以创建多组环境变量，可以切换当前选中的环境变量组。
3.  可以通过API列表功能增删改查API测试用例。api_url、header、body、query、auth字段支持环境变量。新增API测试用例时，标识字段请与路由列表name字段保持一致，以便API测试用例与路由一一对应快速检索。
4.  可以对已添加的API测试用例执行测试操作，在API测试界面，可以对api_url、header、body、query、auth等字段进行自定义编辑。发送按钮可以实际执行API测试用例，获得接口返回值。
5.  可以使用路由列表->同步至数据库功能，将系统内所有路由信息保存至数据库，以便可视化管理和测试。与 php think route:list -m 命令相同效果。
6.  可以使用路由列表->API测试功能，以路由name字段为请求参数跳转至API测试列表页面，以便快速查询出对应的API测试用例进行API测试。（需更新前端vue项目layout/mixin/formOperate.js文件修复了页面初始化传参bug）

  具体请参考 https://www.kancloud.cn/doc_uctoo/manual
  
## 开发说明
### 模块roadmap

1.  通过解析路由文件router.php中的数据，自动生成系统接口（system类型）的所有测试用例。即实现系统接口的可视化测试。
2.  实现API管理功能，即可通过界面配置进行基于appid的接口权限管理，OAUTH2接口鉴权方案。
3.  实现API测试用例中API文档字段支持markdown编辑和展示。
4.  实现除POST、GET、PUT、DELETE之外的其他接口请求方式。
5.  实现全部content-type类型的支持。
6.  实现测试数据的保存、历史记录等功能。
7.  实现notify类型接口的测试，目前还没有在市面上见过类似功能的产品，但是实际开发中notify类型的接口在微信第三方平台、各种支付回调、硬件数据上传等很多场景都有遇到。
8.  实现API测试用例的公开（共享）、私有、保护（有偿获取）等特性。

  具体请参考开源版开发手册 https://www.kancloud.cn/doc_uctoo/uctoo_dev 及 本开源项目示例  