export default {
  // api分类
  apicategory: () => import('@/views/apimanager/apicategory'),
  // api测试
  apitester: () => import('@/views/apimanager/apitester'),
  apirun: () => import('@/views/apimanager/apirun'),
  apienv: () => import('@/views/apimanager/apienv'),
  apimanager_routeList: () => import('@/views/apimanager/route_list/route_list'),
}
