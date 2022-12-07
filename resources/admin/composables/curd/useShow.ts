import http from '/admin/support/http'
import { Ref, ref } from 'vue'
import { isFunction } from '../../support/helper'

const loading = ref<boolean>(true)

const data = ref<object>()

// 后置钩子
const afterShow = ref()

export function useShow(path: string, id: string | number, fillData: null | Ref = null) {
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
