import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

// 全局进度条的配置
NProgress.configure({
  easing: 'ease', // 动画方式
  speed: 500, // 递增进度条的速度
  showSpinner: true, // 是否显示加载ico
  trickleSpeed: 200, // 自动递增间隔
  minimum: 0.3, // 更改启动时使用的最小百分比
  parent: 'body' // 指定进度条的父容器
})

export default NProgress
