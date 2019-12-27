/**
 * 用户管理模块
 *
 */

import { axios } from '@/utils/request'

export function getPermissionList (parameter) {
  return axios({
    url: '/permissions',
    method: 'get',
    params: parameter
  })
}

export function store (parameter) {
  return axios({
    url: '/permissions',
    method: 'post',
    data: parameter
  })
}

export function read (id) {
  return axios({
    url: '/permissions/' + id,
    method: 'get'
  })
}

export function update (id, parameter) {
  return axios({
    url: '/permissions/' + id,
    method: 'put',
    data: parameter
  })
}

export function del (id) {
  return axios({
    url: '/permissions/' + id,
    method: 'delete'
  })
}
