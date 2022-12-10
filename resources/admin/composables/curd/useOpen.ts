import { ref } from 'vue'
import { t } from '/admin/support/helper'

export function useOpen() {
  const visible = ref<boolean>(false)
  const id = ref(null)
  const title = ref<string>('')

  const open = (primary: any) => {
    title.value = primary ? t('system.edit') : t('system.add')
    id.value = primary
    visible.value = true
  }

  const close = (func: Function) => {
    visible.value = false
    func()
  }

  return { open, close, title, visible, id }
}
