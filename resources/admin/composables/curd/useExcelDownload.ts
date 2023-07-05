import Request from '/admin/support/request'
import { ref, watch } from 'vue'

const http = new Request()
export function useExcelDownload() {
  const isSuccess = ref(false)
  const loading = ref<boolean>(false)
  const afterDownload = ref()
  function download(path: string, data: object = {}) {
    loading.value = true
    http
      .setResponseType('blob')
      .init()
      .get(path + '/export', data)
      .then(r => {
        const downloadLink = document.createElement('a')
        const blob = new Blob([r.data], { type: r.headers['content-type'] })
        downloadLink.href = URL.createObjectURL(blob)
        downloadLink.download = r.headers.filename
        document.body.appendChild(downloadLink)
        downloadLink.click()
        document.body.removeChild(downloadLink)
      })
      .finally(() => {
        loading.value = false
      })
  }

  const success = (func: Function) => {
    watch(isSuccess, function () {
      isSuccess.value = false
      func()
    })
  }

  return { download, success, loading, afterDownload }
}
