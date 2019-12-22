
/**
 * @param email
 */
export function validEmail (email) {
  const reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/
  return reg.test(email)
}

export function confirm (self, compare) {
  return self === compare
}
