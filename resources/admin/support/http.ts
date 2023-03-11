import { Code } from '/admin/enum/app'
import axios, { AxiosInstance, AxiosRequestConfig } from 'axios'
import { env, getAuthToken, removeAuthToken } from './helper'
import Message from './message'
import router from '/admin/router'
import ResponseData from '/admin/types/responseData'

/**
 * http util
 */
class Http {
  /**
   * axios config
   * @protected
   */
  protected config: AxiosRequestConfig = {}

  /**
   * base url
   * @protected
   */
  protected baseURL: string = ''

  /**
   * http request timeout
   *
   * @protected
   */
  protected timeout: number = 0

  /**
   * http request headers
   *
   * @protected
   */
  protected headers: { [k: string]: string } = {}

  /**
   * axios instance
   *
   * @protected
   */
  protected request: AxiosInstance

  /**
   * instance
   */
  constructor() {
    this.request = axios.create(this.getConfig())
  }

  /**
   * get request
   *
   * @param path
   * @param params
   */
  public get(path: string, params: object = {}) {
    return this.request.get(this.baseURL + path, {
      params,
    })
  }

  /**
   * post request
   *
   * @param path
   * @param data
   */
  public post(path: string, data: object = {}) {
    return this.request.post(this.baseURL + path, data)
  }

  /**
   * put request
   *
   * @param path
   * @param data
   */
  public put(path: string, data: object = {}) {
    return this.request.put(this.baseURL + path, data)
  }

  /**
   * delete request
   *
   * @param path
   */
  public delete(path: string) {
    return this.request.delete(this.baseURL + path)
  }

  /**
   * set timeout
   *
   * @param timeout
   * @returns
   */
  public setTimeout(timeout: number): Http {
    this.timeout = timeout

    return this
  }

  /**
   * set baseurl
   *
   * @param url
   * @returns
   */
  public setBaseUrl(url: string): Http {
    this.baseURL = url

    return this
  }

  /**
   * set headers
   *
   * @param key
   * @param value
   * @returns
   */
  public setHeader(key: string, value: string): Http {
    this.headers.key = value

    return this
  }

  /**
   * get axios 配置
   *
   * @returns
   */
  protected getConfig(): AxiosRequestConfig {
    // set base url
    this.config.baseURL = this.baseURL ? this.baseURL : env('VITE_BASE_URL')

    // set timeout
    this.config.timeout = this.timeout ? this.timeout : 10000

    // set ajax request
    this.headers['X-Requested-With'] = 'XMLHttpRequest'
    // set dashboard request
    this.headers['Request-from'] = 'Dashboard'
    this.config.headers = this.headers

    return this.config
  }

  /**
   * 添加请求拦截器
   *
   */
  public interceptorsOfRequest(): void {
    // @ts-ignore
    this.request.interceptors.request.use((config: AxiosRequestConfig) => {
      const token = getAuthToken()
      if (token) {
        if (!config.headers) {
          config.headers = {}
        }

        config.headers.authorization = 'Bearer ' + token
      }

      return config
    })
  }

  /**
   * 添加响应拦截器
   *
   */
  public interceptorsOfResponse(): void {
    this.request.interceptors.response.use(
      response => {
        const r: ResponseData = response.data
        const code = r.code
        const message = r.message
        if (code === 1e4) {
          return response
        }

        if (code === 10004) {
          Message.error(message || 'Error')
        } else if (code === Code.LOST_LOGIN || code === Code.LOGIN_EXPIRED) {
          // to re-login
          Message.confirm(message + '，需要重新登陆', function () {
            removeAuthToken()
            router.push('/login')
          })
        } else if (code === Code.LOGIN_BLACKLIST || code === Code.USER_FORBIDDEN) {
          Message.error(message || 'Error')
          removeAuthToken()
          // to login page
          router.push('/login')
        } else {
          Message.error(message || 'Error')
        }

        return Promise.reject(new Error(message || 'Error'))
      },
      error => {
        Message.error(error.message)
        return Promise.reject(error)
      },
    )
  }
}

const http = new Http()
http.interceptorsOfRequest()

http.interceptorsOfResponse()
export default http
