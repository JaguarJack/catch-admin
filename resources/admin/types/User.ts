// login user type

import { Permission } from './permission'

export interface User {
  id: number

  username: string

  avatar: string

  email: string

  status: number

  remember_token: string

  roles?: string[]

  permissions?: Permission[]
}
