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
        path: 'permissions',
        name: 'permissions',
        meta: { title: '菜单管理', icon: 'home' },
        component: () => import('./permissions/index.vue'),
      },
      {
        path: 'jobs',
        name: 'jobs',
        meta: { title: '岗位管理', icon: 'home' },
        component: () => import('./jobs/index.vue'),
      },
      {
        path: 'departments',
        name: 'departments',
        meta: { title: '部门管理', icon: 'home' },
        component: () => import('./departments/index.vue'),
      },
    ],
  },
]

export default []
