import { createPinia } from 'pinia'
import type { App } from 'vue'

const store = createPinia()

export function bootstrapStore(app: App) : void {
  app.use(store)
}

export default store
