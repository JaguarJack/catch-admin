import { useUserStore } from '/admin/stores/modules/user'
import { getAuthToken, removeAuthToken, setPageTitle } from '/admin/support/Helper'
import progress from '/admin/support/progress'
import { WhiteListPage } from '/admin/enum/app'
import { Router, RouteRecordRaw } from 'vue-router'
import { usePermissionsStore } from '/admin/stores/modules/user/permissions'
import { Menu } from '/admin/types/Menu'
import { toRaw } from 'vue'

const guard = (router: Router) => {
  // white list
  const whiteList: string[] = [WhiteListPage.LOGIN_PATH, WhiteListPage.NOT_FOUND_PATH]

  router.beforeEach(async (to, from, next) => {
    // set page title
    setPageTitle(to.meta.title as unknown as string)
    // page start
    progress.start()
    // 获取用户的 token
    const authToken = getAuthToken()
    // 如果 token 存在
    if (authToken) {
      // 如果进入 /login 页面，重定向到首页
      if (to.path === WhiteListPage.LOGIN_PATH) {
        next({ path: '/' })
      } else {
        const userStore = useUserStore()
        // 获取用户ID
        if (userStore.getId) {
          next()
        } else {
          try {
            // 阻塞获取用户信息
            await userStore.getUserInfo()
            // 如果后端没有返回 permissions，前台则只使用静态路由
            if (userStore.getPermissions !== undefined) {
              // 挂载路由（实际是从后端获取用户的权限）
              const permissionStore = usePermissionsStore()
              // 动态路由挂载
              const asyncRoutes = permissionStore.getAsyncMenusFrom(toRaw(userStore.getPermissions))
              asyncRoutes.forEach((route: Menu) => {
                router.addRoute(route as unknown as RouteRecordRaw)
              })
            }
            next({ ...to, replace: true })
          } catch (e) {
            removeAuthToken()
            next({ path: `${WhiteListPage.LOGIN_PATH}?redirect=${to.path}` })
          }
        }
      }
      progress.done()
    } else {
      // 如果不在白名单
      if (whiteList.indexOf(to.path) !== -1) {
        next()
      } else {
        next({ path: WhiteListPage.LOGIN_PATH })
      }
      progress.done()
    }
  })

  router.afterEach(() => {
    progress.done()
  })
}

export default guard
