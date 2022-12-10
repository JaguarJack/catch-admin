import { defineStore } from 'pinia'
import { User } from '/admin/types/user'
import http from '/admin/support/http'
import { rememberAuthToken, removeAuthToken } from '/admin/support/helper'
import Message from '/admin/support/message'
import router from '/admin/router'
import { Permission } from '/admin/types/permission'

export const useUserStore = defineStore('UserStore', {
  state: (): User => {
    return {
      id: 0,

      nickname: '',

      avatar: '',

      email: '',

      remember_token: '',

      status: 0,

      permissions: [] as Permission[],

      roles: [] as string[],
    }
  },

  getters: {
    getId(): number {
      return this.id
    },
    getNickname(): string {
      return this.nickname
    },

    getAvatar(): string {
      return this.avatar
    },

    getRoles(): string[] | undefined {
      return this.roles
    },

    getPermissions(): Permission[] | undefined {
      return this.permissions
    },
  },

  actions: {
    isSuperAdmin(): boolean {
      return this.id === 1
    },
    setNickname(nickname: string) {
      this.nickname = nickname
    },

    setId(id: number) {
      this.id = id
    },

    setRememberToken(token: string) {
      this.remember_token = token
    },

    setAvatar(avatar: string) {
      this.avatar = avatar
    },

    setRoles(roles: string[]) {
      this.roles = roles
    },

    setPermissions(permissions: Permission[]) {
      this.permissions = permissions
    },

    setEmail(email: string) {
      this.email = email
    },

    setStatus(status: number) {
      this.status = status
    },

    /**
     * login
     *
     * @param params
     * @returns
     */
    login(params: Object) {
      return new Promise<void>((resolve, reject) => {
        http
          .post('/login', params)
          .then(response => {
            const { token } = response.data.data
            rememberAuthToken(token)
            this.setRememberToken(token)
            resolve()
          })
          .catch(e => {
            reject(e)
          })
      })
    },

    /**
     * logout
     */
    logout() {
      http
        .post('/logout')
        .then(() => {
          removeAuthToken()
          this.$reset()
          router.push({ path: '/login' })
        })
        .catch(e => {
          Message.error(e.message)
        })
    },

    /**
     * user info
     */
    getUserInfo() {
      return new Promise((resolve, reject) => {
        http
          .get('/user/online')
          .then(response => {
            const { id, nickname, email, avatar, permissions, roles, rememberToken, status } = response.data.data
            // set user info
            this.setId(id)
            this.setNickname(nickname)
            this.setEmail(email)
            this.setRoles(roles)
            this.setRememberToken(rememberToken)
            this.setStatus(status)
            this.setAvatar(avatar)
            this.setPermissions(permissions)

            resolve(response.data.data)
          })
          .catch(e => {
            reject(e)
          })
      })
    },
  },
})
