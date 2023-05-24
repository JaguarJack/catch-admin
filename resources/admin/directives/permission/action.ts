import { useUserStore } from '/admin/stores/modules/user'
import { MenuType } from '/admin/enum/app'
function checkAction(el: any, action: any) {
  if (action.value && typeof action.value === 'string') {
    const userStore = useUserStore()
    const permissions = userStore.getPermissions

    action = action.value.replace('@', '.').toLowerCase()
    const hasAction = permissions?.some(permission => {
      if (permission.type === MenuType.Button_Type) {
        const a: string = permission.module + '.' + permission.permission_mark.replace('@', '.')
        return action === a.toLowerCase()
      }
    })

    if (!hasAction) {
      // el.style.display = 'none'
      el.parentNode && el.parentNode.removeChild(el)
    }
  } else {
    throw new Error(`need action! Like v-action="module.controller.action"`)
  }
}

export default {
  mounted(el: any, binding: any) {
    checkAction(el, binding)
  },

  updated(el: any, binding: any) {
    checkAction(el, binding)
  },
}
