import Cache from '/admin/support/cache'
import { createI18n } from 'vue-i18n'
import en from './languages/en'
import zh from './languages/zh'
import type { App } from 'vue'

const messages = {
  en,
  zh,
}

const i18n = createI18n({
  locale: Cache.get('language') || 'zh',
  messages,
  globalInjection: true,
})

export function bootstrapI18n(app: App): void {
  app.use(i18n)
}

export default i18n
