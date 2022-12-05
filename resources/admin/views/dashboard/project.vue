<template>
  <div class="w-full sm:w-[27%]">
    <el-card shadow="never">
      <template #header>
        <div class="flex flex-row justify-between">
          <span class="text-lg">项目信息</span>
        </div>
      </template>
      <div class="grid grid-cols-1 sm:grid-cols-1 gap-1">
        <div>
          <div class="text-xl text-center">Catch-admin</div>
          <div class="text-base text-gray-400">
            是一个基于Vue3.0、Vite、 ElementPlus 、TypeScript 的后台解决方案，提供了丰富的功能组件，它可以帮助你快速搭建企业级中后台产品原型。
            <el-link href="https://catchadmin.com/" target="_blank">更多...</el-link>
          </div>
          <div class="flex justify-between pl-4 pr-4 mt-2 w-full sm:w-[75%] m-auto">
            <a href="https://github.com/JaguarJack/catch-admin/stargazers"><img alt="GitHub stars" src="https://img.shields.io/github/stars/JaguarJack/catch-admin" /></a>
            <a href="https://github.com/JaguarJack/catch-admin/network"><img alt="GitHub forks" src="https://img.shields.io/github/forks/JaguarJack/catch-admin" /></a>
            <a href="https://github.com/JaguarJack/catch-admin/blob/master/LICENSE.txt"><img alt="GitHub license" src="https://img.shields.io/github/license/JaguarJack/catch-admin" /></a>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 mt-2">
          <el-card shadow="hover">
            <div class="text-center">易用</div>
            <div class="text-sm text-gray-400 pt-2">catchadmin 是从头开始设计的模块化框架，解耦化的设计让你轻松驾驭框架</div>
          </el-card>
          <el-card shadow="hover">
            <div class="text-center">专注</div>
            <div class="text-sm text-gray-400 pt-2">catchadmin 加入大量快速开发组件，让你专注于后台管理的开发，而无需重复无意义的工作</div>
          </el-card>
          <el-card shadow="hover">
            <div class="text-center">强大</div>
            <div class="text-sm text-gray-400 pt-2">catchadmin 基于 PHP 庞大的生态，使其稳定并且强大。使用 catchadmin 可以开发任意 web 应用</div>
          </el-card>
        </div>
      </div>
    </el-card>
  </div>
</template>
