import { RouteRecordRaw } from 'vue-router'
import { isUndefined } from '/admin/support/helper'
// @ts-ignore
export function getModuleRoutes() {
  const modules = import.meta.glob('@/module/**/views/router.ts', { eager: true })
  let moduleRoutes: RouteRecordRaw[] = []

  Object.keys(modules).forEach(routePath => {
    // @ts-ignore
    if (!isUndefined(modules[routePath].default)) {
      // @ts-ignore
      moduleRoutes = moduleRoutes.concat(modules[routePath].default)
    }
  })

  return moduleRoutes
}

export function getModuleViewComponents() {
  return import.meta.glob(['@/module/**/views/**/*.vue', '@/module/!User/views/**/*.vue', '@/module/!Develop/views/**/*.vue', '@/module/!Options/views/**/*.vue'])
}
