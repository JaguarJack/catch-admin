import { defineStore } from 'pinia'
import router from '/admin/router'
interface meta {
    title: string
    affix: boolean
}
interface tab {
    name: string
    fullPath: string
    path: string
    is_active:boolean,
    meta: meta
}

const defaultTab: tab = {
    name: 'Dashboard',
    fullPath: '/dashboard',
    path: '/dashboard',
    is_active: true,
    meta: {
        title: 'Dashboard',
        affix: true,
    },
}

export const useNavTabStore = defineStore('nav_tabs', {
    state: ()=> {
        return {
            tabs: [defaultTab] as Array<tab>,
        }
    },

    getters: {
        getNavTabs(state): Array<tab> {
            return state.tabs
        },
    },

    actions: {
        addTabs(Tab: tab): void {
            if (this.tabs.length >= 20) {
                console.log('最多添加 20 个 tab 标签');
                return;
            }

            let isExist = false

            this.tabs.map(t => {
                if (t.name === Tab.name) {
                    isExist = true
                    t.is_active = true
                } else {
                    t.is_active = false
                }
            })

            if (!isExist) {
                this.tabs.push(Tab)
            }
        },

        getActiveTabIndex(): number|null {
            for (let i = 0; i < this.tabs.length; i++) {
                if (this.tabs[i].is_active) {
                    return i
                }
            }

            return null;
        },

        selectTab(tab: tab): void {
            this.tabs.map(t => {
                if (t.name === tab.name) {
                    t.is_active = true
                } else {
                    t.is_active = false
                }
            })

            router.push(tab.fullPath)
        },

        removeTab(index: number): void {
            const activeIndex = this.getActiveTabIndex()
            if (index === activeIndex) {
                this.tabs = this.tabs.filter((_, idx) => idx !== index);
                router.push(this.tabs[index - 1].fullPath)
            } else {
                const goPath = activeIndex ? this.tabs[activeIndex].fullPath : this.tabs[index - 1].fullPath
                this.tabs = this.tabs.filter((_, idx) => idx !== index);
                router.push(goPath)
            }
        },


        // 右击菜单操作
        // 刷新
        refreshCurrentTab() {
            const index = this.getActiveTabIndex()
            if (index) {
                router.replace({ path: this.tabs[index].fullPath });

                // router.push({ path: this.tabs[index].fullPath });
            }
        },
        // 关闭当前
        removeCurrentTab() {
            const index = this.getActiveTabIndex()
            if (index) {
                if (this.tabs[index].meta.affix) {
                    return
                }
                this.removeTab(index)
            }
        },
        // 关闭所有
        removeAllTabs() {
            this.tabs = [defaultTab]
            router.push('/dashboard')
        },
        // 关闭其他
        removeOtherTabs() {
            const index = this.getActiveTabIndex()
            if (index) {
                this.tabs = this.tabs.filter((_, idx) => idx === index || idx === 0)
            }
        }
    },
})
