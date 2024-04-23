/* eslint-disable no-new-func */
import { cloneDeep, isString, isPlainObject, isArray } from 'lodash'
import type { formValuesType } from '/admin/components/catchForm/config/commonType'
import type { formItemType, formItemsType } from '/admin/components/catchForm/config/commonType'
import { isEqual } from 'lodash'
import type { anyObject, contextType} from '/admin/components/catchForm/config/commonType'

type handleLinkagesType = (obj: { newVal: Object; oldVal: Object; formValues: anyObject; formItems: formItemsType }) => void

// 模板转换函数，将一个由双大括号包裹的字符串，转化为js表达式并返回结果（context限制变量范围）
const templateParse = (str: string, context: contextType) => {
    if (!str) return str
    if (typeof str !== 'string') return str

    const template = str.match(/{{(.+?)}}/)
    if (template) {
        try {
            const parse = new Function(Object.keys(context).join(','), 'return ' + template[1])

            return parse(...Object.values(context))
        } catch (e) {
            // console.log(str, '模板转换错误：', e)
            return str
        }
    } else {
        return str
    }
}

const deepParse = (prop: any, context: contextType): any => {
    const $values = context.$values

    if (isString(prop)) {
        return templateParse(prop, context)
    }

    if (isPlainObject(prop)) {
        return Object.keys(prop).reduce((all, key) => {
            const itemContext = { ...context }

            if (prop.name && $values) {
                itemContext.$val = getDataByPath($values, prop.name)
                itemContext.$select = context.$selectData[prop.name]
            }

            return { ...all, [key]: deepParse(prop[key], itemContext) }
        }, {})
    }
    if (isArray(prop)) {
        return prop.map(item => {
            return deepParse(item, context)
        })
    }

    return prop
}

const handleLinkages: handleLinkagesType = ({ newVal, oldVal, formValues, formItems }) => {
    for (const item of formItems) {
        const newValue = getDataByPath(newVal, item.name)
        const oldValue = getDataByPath(oldVal, item.name)

        if (item.change && !isEqual(newValue, oldValue)) {
            let temp = cloneDeep(formValues.value)
            item.change.forEach(({ target, value }) => {
                temp = setDataByPath(temp, target, value)
            })
            formValues.value = temp
        }

        if (item.children && item.component !== 'FormList') {
            handleLinkages({
                newVal,
                oldVal,
                formValues,
                formItems: item.children
            })
        }
    }
}

const recursionDelete = (items: formItemsType, callback: (item: formItemType) => boolean): formItemsType => {
    const data = items.filter(callback)
    return data.map(item => {
        if (item.children) {
            return {
                ...item,
                children: recursionDelete(item.children, callback)
            }
        }
        return item
    })
}
const setDataByPath = (object: formValuesType, path: string, value: any) => {
    const cloneObj = cloneDeep(object)
    // 将路径字符串分割成路径数组
    const pathArray = path.split('.')
    // 递归函数，用于在对象的深层级找到要修改的位置并更新其值
    function update(obj: formValuesType, pathArray: string[], value: any) {
        // 如果路径数组为空，表示已经到达了最后一级，更新值并返回
        if (pathArray.length === 1) {
            obj[pathArray[0]] = value
            return
        }

        // 获取当前路径的第一个部分
        const currentKey = pathArray.shift()

        if (currentKey) {
            // 如果当前键不存在，则创建一个空对象
            if (!obj[currentKey]) {
                obj[currentKey] = {}
            }

            // 递归调用更新函数
            update(obj[currentKey], pathArray, value)
        }
    }

    // 调用递归函数
    update(cloneObj, pathArray, value)

    // 返回更新后的对象
    return cloneObj
}

const isRegexString = (str: string) => {
    const regexMetaCharacters = /[.*+?^${}()|[\]\\]/

    return regexMetaCharacters.test(str)
}

const getRandomId = (length: number) => {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
    let randomId = ''

    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length)
        randomId += characters.charAt(randomIndex)
    }

    return randomId
}

const getDataByPath = (obj: anyObject, path: string) => {
    // 使用正则表达式分割路径字符串
    const keys = path.split('.')

    // 遍历路径，逐层深入对象
    let result = obj
    for (const key of keys) {
        if (result && typeof result === 'object' && key in result) {
            result = result[key]
        } else {
            // 如果路径无效，返回 undefined 或者其他默认值
            return undefined
        }
    }

    return result
}

export { setDataByPath, recursionDelete, isRegexString, getRandomId, handleLinkages, getDataByPath, deepParse}
