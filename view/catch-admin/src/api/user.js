/**
 * 用户管理模块
 *
 */

import { axios } from '@/utils/request'

export function getUsers (parameter) {
  return axios({
    url: '/user',
    method: 'get',
    params: parameter
  })
}

export function store (parameter) {
  return axios({
    url: '/user',
    method: 'post',
    data: parameter
  })
}

export function read (id) {
  return axios({
    url: '/user/' + id,
    method: 'get'
  })
}

export function update (id, parameter) {
  return axios({
    url: '/user/' + id,
    method: 'put',
    data: parameter
  })
}

export function del (id) {
  return axios({
    url: '/user/' + id,
    method: 'delete'
  })
}

export function swtichStatus (id) {
  return axios({
    url: 'user/switch/status/' + id,
    method: 'put'
  })
}
