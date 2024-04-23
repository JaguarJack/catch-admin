<!-- eslint-disable vue/no-multiple-template-root -->
<template>
  <template v-if="!hidden">
    <div v-if="config.type === 'layout'" :style="itemStyle" :class="thisProps.class">
      <component :is="config.component"  :name="name" :props="props" :children="children" />
    </div>

    <div v-else-if="config.type === 'assist'" :style="itemStyle" :class="thisProps.class">
      <component :is="config.component" v-bind="props" />
    </div>

    <el-form-item v-else id="form-item" :style="itemStyle" :label="label" :key="name" :prop="name" :rules="computeRules" :class="thisProps.class">
      <!--<template #label v-if="!hideLabel">
        <div class="form-item-label">
          <div >{{ label }}</div>
          <div class="ico" v-if="help">
            <el-tooltip class="box-item" effect="dark" :content="help">
              <div><icon-render name="help" /></div>
            </el-tooltip>
          </div>
        </div>
      </template>-->
      <component :is="config.component" :class="formItemProps.class" :disabled="schema.disabled" :size="schema.size" v-bind="formItemProps" v-model:[config.modelName]="value" />
    </el-form-item>
  </template>
</template>

<script setup lang="ts">
import { computed, inject, nextTick, onMounted, ref } from 'vue'
import { isArray, isString } from 'lodash'
import { isRegexString, getDataByPath, setDataByPath } from '/admin/components/catchForm/support'
import { $global, $schema, $formValues, $initialValues } from '/admin/components/catchForm/config/symbol'
import type { formItemType, changeItemType, schemaType, anyObject, $globalType } from '/admin/components/catchForm/config/commonType'
import defaultElements from '/admin/components/catchForm/components'

type FormItemProps = {
  label?: string
  name: string
  component: string
  required?: boolean
  props?: object
  initialValue?: any
  help?: string
  children?: formItemType[]
  hidden?: boolean | string
  hideLabel?: boolean
  rules?: any[]
  // eslint-disable-next-line vue/no-reserved-props
  class?: string
  // eslint-disable-next-line vue/no-reserved-props
  style?: any
  change?: changeItemType[]
}

const thisProps = defineProps<FormItemProps>()

const { elements = {} } = inject<$globalType>($global, { elements: defaultElements })

const schema = inject<schemaType>($schema)

const { formValues, updateFormValues } = inject($formValues, {
  formValues: ref({}),
  updateFormValues: (values: anyObject) => {
    console.log(values)
  }
})

const { initialValues, updateInitialValues } = inject($initialValues, {
  initialValues: {},
  updateInitialValues: (values: anyObject) => {
    console.log(values)
  }
})

const value = computed({
  get() {
    return getDataByPath(formValues.value, thisProps.name)
  },
  set(val) {
      const newValues = setDataByPath(formValues.value, thisProps.name, val)

      updateFormValues(newValues)
  }
})

const itemStyle = computed(() => ({
  // marginBottom: thisProps.design ? 0 : '18px',
  ...thisProps.style
}))

const computeRules = computed(() => {
  const { rules, required } = thisProps

  const ruleData = []

  if (required) {
    ruleData.push({ required: true, message: '该字段是必填字段', trigger: 'blur' })
  }

  if (rules) {
    const ruleParse = rules.map(({ type, message, trigger, customReg }) => {
      const ruleDef = {
        message,
        trigger
      }
      if (['email', 'url'].includes(type)) {
        return { ...ruleDef, type }
      }
      if (type === 'custom') {
        return {
          ...ruleDef,
          pattern: customReg
        }
      }
      if (isRegexString(type)) {
        return {
          ...ruleDef,
          pattern: type
        }
      }
      return {}
    })
    return [...ruleData, ...ruleParse]
  }

  return ruleData
})

const currentComponent = computed(() => {
  if (isString(value.value) && /^{{\s*(.*?)\s*}}$/.test(value.value)) {
    return 'input'
  }

  return thisProps.component
})

const config = computed(() => {
  return elements[currentComponent.value] || {}
})

const formItemProps = computed(() => {
  const initProps: anyObject = {
    ...thisProps.props,
    name: thisProps.name
  }

  if (thisProps.children) {
    initProps.children = thisProps.children
  }

  return initProps
})

onMounted(() => {
    if (thisProps.initialValue !== undefined) {
        if (!value.value || (isArray(value.value) && value.value.length === 0)) {
            const newInitialValues = setDataByPath(initialValues, thisProps.name, thisProps.initialValue)
            updateInitialValues(newInitialValues)
            // select array value
            if (isArray(value.value)) {
                nextTick(() => {
                    value.value = thisProps.initialValue
                })
            }
        }
    }
    /**
    if (!value.value && thisProps.default !== undefined) {
        const newInitialValues = setDataByPath(initialValues, thisProps.name, thisProps.default)
        updateInitialValues(newInitialValues)
    }*/
})
</script>

<style lang="scss">
#form-item {
  .form-item-label {
    display: flex;
    position: relative;
    .ico {
      margin-left: 3px;
      font-size: 15px;
      position: relative;
      .el-tooltip__trigger {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
      }
    }
  }
}
</style>
