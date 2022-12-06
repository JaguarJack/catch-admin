import http from '/admin/support/http'
import {provide, ref, unref} from 'vue'
import { Code } from '/admin/enum/app'
import Message from '/admin/support/message'

const initLimit = 10
const initPage = 1;
const initTotal = 10;

// get table list
export function useGetList(path: string) {
  const data = ref<object>()
  const page = ref<number>(initPage)
  const limit = ref<number>(initLimit)
  const total = ref<number>(initTotal)
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
          // @ts-ignore
          total.value = data.value?.total
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
    resetPage()

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

  function resetPage() {
      page.value = 1
  }

  // change limit
  function changeLimit(l: number) {
    limit.value = l
    resetPage()
    // @ts-ignore
    query.value.page = 1
    // @ts-ignore
    query.value.limit = l

    search()
  }

  // provider for paginate component
  provide('paginate', {page, limit, total, changePage, changeLimit})

  return { data, query, search, reset, loading }
}
