import http from '/admin/support/http'
import { Code } from '/admin/assets/enum/app'
import Message from '/admin/support/message'
import { ref } from 'vue'

export function useEnabled() {
  const success = ref(false)
  const loading = ref<boolean>(false)
  function enabled(path: string, id: string | number, data: object = {}) {
    loading.value = true
    http
      .put(path + '/enable/' + id, data)
      .then(r => {
        if (r.data.code === Code.SUCCESS) {
          success.value = true
          Message.success(r.data.message)
        } else {
          Message.error(r.data.message)
        }
      })
      .finally(() => {
        loading.value = false
      })
  }

  return { enabled, success, loading }
}
