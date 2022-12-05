import { defineStore } from 'pinia'
import Cache from '/admin/support/cache'

/**
 * app
 */
type app = {
  size: 'small' | 'medium' | 'large'

  isExpand: boolean

  locale: 'zh' | 'en'

  isMobile: boolean

  isDarkMode: boolean

  activeMenu: string
}

export const useAppStore = defineStore('app', {
  state: (): app => ({
    size: 'small',
    isExpand: true,
    locale: Cache.get('language'),
    isMobile: false,
    isDarkMode: false,
    activeMenu: '/dashboard',
  }),

  getters: {
    getSize(): string {
      return this.size
    },

    getLocale(): string {
      return this.locale
    },

    getIsMobile(): boolean {
      return this.isMobile
    },

    getIsDarkMode(): boolean {
      return this.isDarkMode
    },

    getActiveMenu(): string {
      return this.activeMenu
    },
  },

  actions: {
    changeSize(size: 'small' | 'medium' | 'large'): void {
      this.size = size
    },

    changeLocale(locale: 'zh' | 'en'): void {
      Cache.set('language', locale)

      this.locale = locale
    },

    changeExpaned(): void {
      this.isExpand = !this.isExpand
    },

    setDarkMode(isDarkMode: boolean): void {
      this.isDarkMode = isDarkMode
    },

    setActiveMenu(activeMenu: string): void {
      this.activeMenu = activeMenu.startsWith('/') ? activeMenu : '/' + activeMenu
    },
  },
})
