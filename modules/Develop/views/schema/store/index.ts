import { defineStore } from 'pinia'

/**
 * 表信息
 */
export interface Schema {
  module: string
  name: string
  comment: string
  engine: string
  charset: string
  collaction: string
  created_at: boolean
  creator_id: boolean
  updated_at: boolean
  deleted_at: boolean
}

/**
 * 表结构信息
 */
export interface Structure {
  id: number
  field: string
  length: number
  type: string
  nullable: boolean
  unique: boolean
  default: number | string
  comment: string
}

/**
 * generate
 */
interface CreateSchema {
  schema: Schema
  structures: Structure[]
  is_finished: boolean
}

/**
 * useSchemaStore
 */
export const useSchemaStore = defineStore('schemaStore', {
  state(): CreateSchema {
    return {
      // schema
      schema: Object.assign({
        module: '',
        name: '',
        comment: '',
        engine: 'InnoDB',
        charset: 'utf8mb4',
        collection: 'utf8mb4_unicode_ci',
        created_at: true,
        creator_id: true,
        updated_at: true,
        deleted_at: true,
      }),
      // structures
      structures: [] as Structure[],

      // is finished
      is_finished: false,
    }
  },

  // store getters
  getters: {
    getSchema(): Schema {
      return this.schema
    },

    getStructures(): Structure[] {
      return this.structures
    },

    getFinished(): boolean {
      return this.is_finished
    },
  },

  // store actions
  actions: {
    // set schema
    setSchema(schema: Schema): void {
      this.schema = schema
    },

    setStructures(structures: Array<Structure>): void {
      this.structures = structures
    },
    // add structure
    addStructure(structure: Structure): void {
      if (structure.id) {
        this.structures = this.structures.filter((s: Structure) => {
          if (s.id === structure.id) {
            s = structure
          }
          return s
        })
      } else {
        structure.id = this.structures.length + 1
        this.structures.push(structure)
      }
    },

    // filter structures
    filterStructures(id: number) {
      this.structures = this.structures.filter((s: Structure) => {
        return !(s.id === id)
      })
    },

    // init structure
    initStructure(): Structure {
      return Object.assign({
        id: 0,
        field: '',
        label: '',
        type: '',
        length: 0,
        nullable: false,
        unique: false,
        default: '',
        comment: '',
      })
    },

    /**
     * finished
     */
    finished(): void {
      this.$reset()
      this.is_finished = true
    },

    /**
     * unfinished
     */
    start(): void {
      this.is_finished = false
    },
  },
})
