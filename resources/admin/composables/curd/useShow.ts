import http from '/admin/support/http'

export function useShow(path: string, id: string | number) {
  return new Promise((resolve, reject) => {
    http
      .get(path + '/' + id)
      .then(response => {
        resolve(response.data)
      })
      .catch(e => {
        reject(e)
      })
  })
}
