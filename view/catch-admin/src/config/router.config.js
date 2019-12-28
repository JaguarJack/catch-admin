// eslint-disable-next-line
import { UserLayout, BasicLayout, RouteView, BlankLayout, PageView } from '@/layouts'
import { bxAnaalyse } from '@/core/icons'

export const asyncRouterMap = [

  {
    path: '/',
    name: 'index',
    component: BasicLayout,
    meta: { title: '首页' },
    redirect: '/dashboard/workplace',
    children: [
      // dashboard
      {
        path: '/dashboard',
        name: 'dashboard',
        redirect: '/dashboard/workplace',
        component: RouteView,
        meta: { title: '仪表盘', keepAlive: true, icon: bxAnaalyse, permission: [ 'dashboard' ] },
        children: [
          {
            path: '/dashboard/workplace',
            name: 'Workplace',
            component: () => import('@/views/dashboard/Workplace'),
            meta: { title: '工作台', keepAlive: true, permission: [ 'dashboard' ] }
          }
        ]
      },

      // permissions
      {
        path: '/permissions',
        name: 'permissions',
        component: PageView,
        redirect: '/permissions',
        meta: { title: '权限管理', icon: 'table', permission: [ 'permission' ] },
        children: [
          {
            path: '/permissions/users',
            name: 'users',
            hideChildrenInMenu: true, // 强制显示 MenuItem 而不是 SubMenu
            component: () => import('@/views/permissions/users/users'),
            meta: { title: '用户管理', keepAlive: true, permission: [ 'user' ] }
          },
          {
            path: '/permissions/roles',
            name: 'roles',
            hideChildrenInMenu: true, // 强制显示 MenuItem 而不是 SubMenu
            component: () => import('@/views/permissions/roles/roles'),
            meta: { title: '角色管理', keepAlive: true, permission: [ 'role' ] }
          },
          {
            path: '/permissions/rules',
            name: 'rules',
            hideChildrenInMenu: true, // 强制显示 MenuItem 而不是 SubMenu
            component: () => import('@/views/permissions/rules/rules'),
            meta: { title: '菜单管理', keepAlive: true, permission: [ 'permission' ] }
          }
        ]
      },
      {
        path: '/system',
        name: 'system',
        component: PageView,
        redirect: '/system',
        meta: { title: '系统管理', icon: 'table', system: [ 'system' ] },
        children: [
          {
            path: '/system/database',
            name: 'database',
            hideChildrenInMenu: true, // 强制显示 MenuItem 而不是 SubMenu
            component: () => import('@/views/system/database/index'),
            meta: { title: '数据字典', keepAlive: true, system: [ 'database' ] }
          },
          {
            path: '/system/log',
            name: 'log',
            meta: { title: '日志管理', keepAlive: true, permission: [ 'log' ] },
            children: [
              {
                path: '/system/log/login',
                name: 'loginLog',
                hideChildrenInMenu: true, // 强制显示 MenuItem 而不是 SubMenu
                component: () => import('@/views/system/log/login'),
                meta: { title: '登录日志', keepAlive: true, system: [ 'loginLog/index' ] }
              },
              {
                path: '/system/log/operate',
                name: 'operateLog',
                hideChildrenInMenu: true, // 强制显示 MenuItem 而不是 SubMenu
                component: () => import('@/views/system/log/operate'),
                meta: { title: '操作日志', keepAlive: true, system: [ 'operateLog/index' ] }
              }
            ]
          }
        ]
      }
    ]
  },
  {
    path: '*', redirect: '/404', hidden: true
  }
]

/**
 * 基础路由
 * @type { *[] }
 */
export const constantRouterMap = [
  {
    path: '/user',
    component: UserLayout,
    redirect: '/user/login',
    hidden: true,
    children: [
      {
        path: 'login',
        name: 'login',
        component: () => import(/* webpackChunkName: "user" */ '@/views/user/Login')
      },
      {
        path: 'register',
        name: 'register',
        component: () => import(/* webpackChunkName: "user" */ '@/views/user/Register')
      },
      {
        path: 'register-result',
        name: 'registerResult',
        component: () => import(/* webpackChunkName: "user" */ '@/views/user/RegisterResult')
      },
      {
        path: 'recover',
        name: 'recover',
        component: undefined
      }
    ]
  },

  {
    path: '/test',
    component: BlankLayout,
    redirect: '/test/home',
    children: [
      {
        path: 'home',
        name: 'TestHome',
        component: () => import('@/views/Home')
      }
    ]
  },

  {
    path: '/404',
    component: () => import(/* webpackChunkName: "fail" */ '@/views/exception/404')
  }

]
