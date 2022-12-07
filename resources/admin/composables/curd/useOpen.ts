import { ref } from 'vue'
import { t } from '/admin/support/helper'

const visible = ref<boolean>(false)
const id = ref(null)
const title = ref<string>('')
export function useOpen() {
  const open = (primary: any) => {
    console.log(primary)
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
