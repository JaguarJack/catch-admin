/**
 * 用户管理模块
 *
 */

import { axios } from '@/utils/request'

export function getTables (parameter) {
  return axios({
    url: '/tables',
    method: 'get',
    params: parameter
  })
}

export function optimize (parameter) {
  return axios({
    url: '/table/optimize',
    method: 'post',
    data: parameter
  })
}

export function backup (parameter) {
  return axios({
    url: '/table/backup',
    method: 'post',
    data: parameter
  })
}

export function read (table) {
  return axios({
    url: '/table/view/' + table,
    method: 'get'
  })
}
