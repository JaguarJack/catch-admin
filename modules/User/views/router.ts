import { RouteRecordRaw } from 'vue-router'

// @ts-ignore
const router: RouteRecordRaw[] = [
  {
    path: '/users',
    component: () => import('/admin/layout/index.vue'),
    meta: { title: '用户管理', icon: 'user' },
    children: [
      {
        path: 'index',
        name: 'user-account',
        meta: { title: '账号管理', icon: 'home' },
        component: () => import('./user/index.vue'),
      },
      {
        path: 'center',
        name: 'user-center',
        meta: { title: '个人中心', icon: 'home' },
        component: () => import('./user/center.vue'),
      },
    ],
  },
]

export default router
