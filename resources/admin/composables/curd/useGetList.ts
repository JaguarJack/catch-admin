import http from '/admin/support/http'
import { ref, unref } from 'vue'
import { Code } from '/admin/enum/app'
import Message from '/admin/support/message'

const initLimit = 10
const initPage = 1;

// get table list
export function useGetList(path: string) {
  const data = ref<object>()
  const page = ref(initPage)
  const limit = ref(initLimit)
  const query = ref<object>({
    page: page.value,
    limit: limit.value,
  })
  const loading = ref(true)
  // fetch list
  function getList() {
    // when table's data page >= 100, it will loading
    if (page.value >= 100) {
      loading.value = true
    }
    http
      .get(path, unref(query))
      .then(r => {
        closeLoading()
        if (r.data.code === Code.SUCCESS) {
          data.value = r.data
        } else {
          Message.error(r.data.message)
        }
      })
      .finally(() => {
        closeLoading()
      })
  }

  // close loading
  function closeLoading() {
    loading.value = false
  }
  // search
  function search() {
    getList()
  }

  // reset
  function reset() {
    query.value = Object.assign({ page: page.value, limit: limit.value })

    getList()
  }

  // change page
  function changePage(p: number) {
    page.value = p
    // @ts-ignore
    query.value.page = p
    search()
  }

  // change limit
  function changeLimit(l: number) {
    limit.value = l
    // @ts-ignore
    query.value.page = 1
    // @ts-ignore
    query.value.limit = l
    search()
  }

  return { data, query, search, reset, changePage, changeLimit, loading }
}
