/**
 * 服务端返回码
 */
export const enum Code {
  SUCCESS = 10000, // 成功
  LOST_LOGIN = 10001, //  登录失效
  VALIDATE_FAILED = 10002, // 验证错误
  PERMISSION_FORBIDDEN = 10003, // 权限禁止
  LOGIN_FAILED = 10004, // 登录失败
  FAILED = 10005, // 操作失败
  LOGIN_EXPIRED = 10006, // 登录失效
  LOGIN_BLACKLIST = 10007, // 黑名单
  USER_FORBIDDEN = 10008, // 账户被禁
  WECHAT_RESPONSE_ERROR = 40000,
}

/**
 * status
 */
export const enum Status {
  ENABLE = 1,
  DISABLE = 2,
}

/**
 * 白名单页面
 *
 * 不需要权限认证
 */
export const enum WhiteListPage {
  LOGIN_PATH = '/login',

  NOT_FOUND_PATH = '/404',
}

/**
 * menu 类型
 */
export const enum MenuType {
  TOP_TYPE = 1,
  PAGE_TYPE = 2,
  Button_Type = 3,
}
