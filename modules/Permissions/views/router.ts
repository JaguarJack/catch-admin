import { RouteRecordRaw } from 'vue-router'

// @ts-ignore
const router: RouteRecordRaw[] = [
  {
    path: '/permission',
    component: () => import('/admin/layout/index.vue'),
    meta: { title: '权限管理', icon: 'user' },
    children: [
      {
        path: 'roles',
        name: 'roles',
        meta: { title: '角色管理', icon: 'home' },
        component: () => import('./roles/index.vue'),
      },
      {
        path: 'jobs',
        name: 'jobs',
        meta: { title: '岗位管理', icon: 'home' },
        component: () => import('./jobs/index.vue'),
      },
    ],
  },
]

export default router
