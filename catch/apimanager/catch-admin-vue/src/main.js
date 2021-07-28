import Vue from 'vue'

import 'normalize.css/normalize.css' // a modern alternative to CSS resets

import Element from 'element-ui'
import Avue from '@smallwei/avue'
import 'element-ui/lib/theme-chalk/index.css'
import './styles/element-variables.scss'

// import enLang from 'element-ui/lib/locale/lang/en'// 如果使用中文语言包请默认支持，无需额外引入，请删除该依赖

import '@/styles/index.scss' // global css

import App from './App'
import store from './store'
import router from './router'

import './icons' // icon
import './permission' // permission control
import './utils/error-log' // error log
import request from '@/utils/request'
import * as filters from './filters' // global filters
import catchAdmin from '@/components/Catch'

Vue.use(Element, {
  size: 'small'// set element-ui default size
  // locale: enLang // 如果使用中文，无需设置，请删除
})
window.axios = request;
Vue.use(Avue, { request });
// register global utility filters
Object.keys(filters).forEach(key => {
  Vue.filter(key, filters[key])
})

// 后台启动
catchAdmin.boot()

Vue.config.productionTip = false
Vue.prototype.$http = request
Vue.prototype.admin = catchAdmin

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
