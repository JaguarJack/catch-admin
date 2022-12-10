import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router'
import type { App } from 'vue'
// module routers
import { getModuleRoutes, getModuleViewComponents } from './constantRoutes'

const moduleRoutes = getModuleRoutes()
getModuleViewComponents()
export const constantRoutes: RouteRecordRaw[] = [
  {
    path: '/dashboard',
    component: () => import('/admin/layout/index.vue'),
    children: [
      {
        path: '',
        name: 'Dashboard',
        meta: { title: 'Dashboard', icon: 'home', hidden: false },
        component: () => import('/admin/views/dashboard/index.vue'),
      },
    ],
  },
]
  // @ts-ignore
  .concat(moduleRoutes)

// default routes, it will not change to menus
const defaultRoutes: RouteRecordRaw[] = [
  {
    path: '/',
    name: '/',
    component: () => import('/admin/layout/index.vue'),
    redirect: '/dashboard',
    children: [
      {
        path: '/404',
        name: '404',
        meta: { title: '404' },
        component: () => import('/admin/components/404/index.vue'),
      },
    ],
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('/admin/views/login/index.vue'),
  },
  // 未定义路有重定向到 404
  {
    path: '/:pathMatch(.*)*',
    redirect: '/404',
  },
]

const routes = constantRoutes.concat(defaultRoutes)
const router = createRouter({
  history: createWebHashHistory(),
  routes,
  // 路由滚动
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { top: 0, behavior: 'smooth' }
  },
})

export function bootstrapRouter(app: App) {
  app.use(router)
}

export default router
