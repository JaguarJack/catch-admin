export interface Permission {
  id: number

  parent_id: number

  title: string

  type: number

  icon: string

  component: string

  module: string

  permission_mark: string

  route: string

  redirect: string

  keepAlive: boolean

  hidden: boolean

  is_inner: boolean
}
