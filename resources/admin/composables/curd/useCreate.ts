import http from '/admin/support/http'
import { ref, unref, watch } from 'vue'
import { Code } from '/admin/enum/app'
import Message from '/admin/support/message'
import { FormInstance } from 'element-plus'
import { AxiosResponse } from 'axios'
import { isFunction } from '/admin/support/helper'

// get table list
export function useCreate(path: string, id: string | number | null = null, _formData: object = {}) {
  const formData = ref<object>(_formData)

  const loading = ref<boolean>()
  const isClose = ref<boolean>(false)

  // 创建前 hook
  const beforeCreate = ref()
  // 更新前 hook
  const beforeUpdate = ref()

  // store
  function store(path: string, id: string | number | null = null) {
    loading.value = true
    let promise: Promise<AxiosResponse> | null = null
    if (id) {
      if (isFunction(beforeUpdate.value)) {
        beforeUpdate.value()
      }

      promise = http.put(path + '/' + id, unref(formData))
    } else {
      if (isFunction(beforeCreate.value)) {
        beforeCreate.value()
      }

      promise = http.post(path, unref(formData))
    }

    promise
      .then(r => {
        if (r.data.code === Code.SUCCESS) {
          isClose.value = true
          Message.success(r.data.message)
        } else {
          Message.error(r.data.message)
        }
      })
      .finally(() => {
        loading.value = false
      })
  }

  const form = ref<FormInstance>()
  const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl
      .validate(valid => {
        if (valid) {
          store(path, id)
        } else {
          loading.value = false
        }
      })
      .then(() => {})
  }

  const close = (func: Function) => {
    watch(isClose, function (value) {
      if (value && isFunction(func)) {
        func()
      }
    })
  }

  return { formData, loading, form, submitForm, close, beforeCreate, beforeUpdate }
}
