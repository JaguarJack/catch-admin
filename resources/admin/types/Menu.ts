import { Component } from 'vue'
import { RouteRecordRaw } from 'vue-router'

export interface Meta {
  title: string

  icon: string

  roles?: string[]

  cache?: boolean

  hidden: boolean

  keepalive?: boolean

  active_menu?: string
}

// @ts-ignore
export interface Menu extends Omit<RouteRecordRaw, 'meta'> {
  path: string

  name: string

  meta?: Meta

  redirect?: string

  component?: Component

  children?: Menu[]
}
