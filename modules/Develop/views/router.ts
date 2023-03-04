import { RouteRecordRaw } from 'vue-router'

// @ts-ignore
const router: RouteRecordRaw[] = [
  {
    path: '/develop',
    component: () => import('/admin/layout/index.vue'),
    meta: { title: '开发工具', icon: 'wrench-screwdriver' },
    children: [
      {
        path: 'modules',
        name: 'modules',
        meta: { title: '模块管理', icon: 'queue-list' },
        component: () => import('./module/index.vue'),
      },
      {
        path: 'schemas',
        name: 'schemas',
        meta: { title: 'Schemas', icon: 'list-bullet' },
        component: () => import('./schema/index.vue'),
      },
      {
        path: 'generate/:schema',
        name: 'generate',
        meta: { title: '代码生成', hidden: true, active_menu: '/develop/schemas' },
        component: () => import('./generate/index.vue'),
      },
    ],
  },
]

export default router
