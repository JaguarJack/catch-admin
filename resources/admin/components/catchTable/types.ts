export type itemType = 'input' | 'select' | 'input-number' | 'date' | 'datetime' | 'range'

export interface Option {
  label: string
  value: string | number
}

export interface Field {
  type: itemType
  label: string
  name: string
  api?: string
  placeholder?: string
  default?: any
  options?: Array<Option>
  children?: Array<Field>
  show?: boolean
  props?: Object // 树形 props
}
