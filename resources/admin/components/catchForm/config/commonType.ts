type anyObject = { [key: string]: any }

type formValuesType = anyObject

type contextType = {
  $values: formValuesType
  $selectData: formValuesType
  $initialValues: formValuesType
  [key: string]: any
}

type changeItemType = {
  target: string
  value: any
}

interface formItemType {
  label?: string
  name: string
  component: string
  required?: boolean
  props?: object
  default?: any
  help?: string
  children?: formItemType[]
  hidden?: boolean | string
  hideLabel?: boolean
  rules?: any[]
  class?: string
  style?: any
  change?: changeItemType[]
  footer: formItemType
}

type formItemsType = formItemType[]

type schemaType = {
  labelWidth: number
  labelAlign: string
  size: string
  footer: Object
  class?: string
  disabled?: boolean
  hideRequiredAsterisk?: boolean
  labelBold?: boolean
  items: formItemsType
}

type formElement = {
  name: string
  component: any
  icon: string
  type: 'assist' | 'layout' | 'basic' | 'high'
  order: number
  attr: formItemsType
  initialValues: formItemType
  modelName: string
}

type iconSelectConfigType = { component?: any; propKey?: string; iconList?: string[] }

type $globalType = {
  http?: any
  getSchema?: (schemaId: string) => Promise<schemaType>
  elements?: { [key: string]: formElement }
  iconSelectConfig?: iconSelectConfigType
  customElements?: { [key: string]: formElement }
}

export type { anyObject, schemaType, formValuesType, contextType, formItemType, formItemsType, formElement, changeItemType, $globalType, iconSelectConfigType }
