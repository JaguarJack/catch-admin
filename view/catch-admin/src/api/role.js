/**
 * 角色管理模块
 *
 */

import { axios } from '@/utils/request'

export function getRoleList (parameter) {
  return axios({
    url: '/roles',
    method: 'get',
    params: parameter
  })
}

export function store (parameter) {
  return axios({
    url: '/roles',
    method: 'post',
    data: parameter
  })
}

export function read (id) {
  return axios({
    url: '/roles/' + id,
    method: 'get'
  })
}

export function update (id, parameter) {
  return axios({
    url: '/roles/' + id,
    method: 'put',
    data: parameter
  })
}

export function del (id) {
  return axios({
    url: '/roles/' + id,
    method: 'delete'
  })
}
