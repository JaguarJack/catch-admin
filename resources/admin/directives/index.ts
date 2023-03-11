import type { App } from 'vue'

import action from './permission/action'
export function bootstrapDirectives(app: App): void {
  app.directive('action', action)
}
