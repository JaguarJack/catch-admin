import http from '/admin/support/http'
import { Ref, ref } from 'vue'
import { isFunction } from '../../support/helper'

export function useShow(path: string, id: string | number, fillData: null | Ref = null) {
  const loading = ref<boolean>(true)

  const data = ref<object>()

  // 后置钩子
  const afterShow = ref()

  http.get(path + '/' + id).then(r => {
    loading.value = false
    data.value = r.data.data
    if (fillData) {
      fillData.value = r.data.data

      if (isFunction(afterShow.value)) {
        afterShow.value(fillData)
      }
    }
  })

  return { data, loading, afterShow }
}
