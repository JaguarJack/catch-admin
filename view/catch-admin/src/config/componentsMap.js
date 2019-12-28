/**
 * export component map
 */
export default {
  workplace: () => import('@/views/dashboard/Workplace'),
  users: () => import('@/views/permissions/users/users'),
  roles: () => import('@/views/permissions/roles/roles'),
  rules: () => import('@/views/permissions/rules/rules'),
  database: () => import('@/views/system/database/index'),
  loginLog: () => import('@/views/system/log/login'),
  operateLog: () => import('@/views/system/log/operate')
}
