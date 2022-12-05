import { RouteRecordRaw } from 'vue-router'
// @ts-ignore
const modules = import.meta.glob('@/module/**/views/router.ts', { eager: true })
let moduleRoutes: RouteRecordRaw[] = []

Object.keys(modules).forEach(routePath => {
  moduleRoutes = moduleRoutes.concat(modules[routePath].default)
})
export default moduleRoutes
