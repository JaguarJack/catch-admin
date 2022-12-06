import http from '/admin/support/http'
import { Code } from '/admin/enum/app'
import Message from '/admin/support/message'
import { ref, watch } from 'vue'
import { isFunction } from '/admin/support/helper'

export function useDestroy(confirm: string = '确认删除吗') {
  const isDeleted = ref(false)

  const beforeDestroy = ref()

  // fetch list
  function destroy(path: string, id: string | number) {
    Message.confirm(confirm + '?', function () {
      // before destroy
      if (isFunction(beforeDestroy.value)) {
        beforeDestroy.value()
      }

      http
        .delete(path + '/' + id)
        .then(r => {
          if (r.data.code === Code.SUCCESS) {
            Message.success(r.data.message)
            isDeleted.value = true
          } else {
            Message.error(r.data.message)
          }
        })
        .finally(() => {})
    })
  }

  const deleted = (reset: Function) => {
    watch(isDeleted, function (value) {
      if (value) {
        isDeleted.value = false
        reset()
      }
    })
  }

  return { destroy, deleted }
}
