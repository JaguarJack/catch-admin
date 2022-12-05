import { defineStore } from 'pinia'

/**
 * 表结构信息
 */
export interface Structure {
  field: string
  label: string
  form_component: string
  list: boolean
  form: boolean
  search: boolean
  search_op: string
  validates: string[]
}

/**
 * CodeGen
 */
export interface CodeGen {
  module: string
  controller: string
  model: string
  paginate: true
  schema: string
}

/**
 * generate
 */
interface generate {
  schemaId: number
  structures: Structure[]
  codeGen: CodeGen
}

/**
 * useGenerateStore
 */
export const useGenerateStore = defineStore('generateStore', {
  state(): generate {
    return {
      // schema id
      schemaId: 0,
      // structures
      structures: [] as Structure[],
      // codeGen
      codeGen: Object.assign({
        module: '',
        controller: '',
        model: '',
        paginate: true,
        schema: '',
      }),
    }
  },

  // store getters
  getters: {
    getSchemaId(): any {
      return this.schemaId
    },

    getStructures(): Structure[] {
      return this.structures
    },

    getCodeGen(): CodeGen {
      return this.codeGen
    },
  },

  // store actions
  actions: {
    // set schema
    setSchemaId(schemaId: any): void {
      this.schemaId = schemaId
    },
    // reset
    resetStructures(): void {
      this.structures = []
    },
    // filter structures
    filterStructures(field: string) {
      this.structures = this.structures.filter((s: Structure) => {
        return !(s.field === field)
      })
    },

    // init structure
    initStructures(fields: Array<any>): void {
      const unSupportFields = ['deleted_at', 'creator_id']

      fields.forEach(field => {
        if (!unSupportFields.includes(field.name)) {
          this.structures.push(
            Object.assign({
              field: field.name,
              label: '',
              form_component: 'input',
              list: true,
              form: true,
              search: false,
              search_op: '',
              validates: [],
            }),
          )
        }
      })
    },
  },
})
