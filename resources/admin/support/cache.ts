export default class Cache {
  private static readonly prefix:string = 'catchadmin_'
  /**
     * set
     *
     * @param key
     * @param value
     */
  static set (key:string, value: any) : void {
    window.localStorage.setItem(Cache.prefix + key, value)
  }

  /**
     * get
     *
     * @param key
     * @returns
     */
  static get (key: string) : any {
    return window.localStorage.getItem(Cache.prefix + key)
  }

  /**
     * delete
     *
     * @param key
     * @returns
     */
  static del (key: string) : void {
    window.localStorage.removeItem(Cache.prefix + key)
  }
}
