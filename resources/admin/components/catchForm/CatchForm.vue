<template>
  <el-form :class="schema.class" :model="formValues"  :label-width="schema.labelWidth || 100" :label-position="schema.labelAlign || 'right'" :size="schema.size || 'default'" :disabled="schema.disabled" :hide-required-asterisk="schema.hideRequiredAsterisk" ref="formRef" v-bind="$attrs">
    <FormRender :formItems="formItems" />
    <slot name="body"/>
    <FormItem v-bind="footer" />
    <slot name="footer"/>
  </el-form>
</template>

<script setup lang="ts">
import { ref, computed, reactive, provide, watch, onMounted } from 'vue'
import type { FormInstance } from 'element-plus'
import { handleLinkages, deepParse, setDataByPath, getDataByPath } from '/admin/components/catchForm/support'
import FormRender from './FormRender.vue'
import FormItem from './FormItem.vue'
import { cloneDeep, merge } from 'lodash'
import type { anyObject, schemaType } from '/admin/components/catchForm/config/commonType'
import { $schema, $formValues, $selectData, $formEvents, $initialValues } from '/admin/components/catchForm/config/symbol'

const props = defineProps<
  Readonly<{
    schema: schemaType
    schemaContext?: anyObject
  }>
>()

const modelValue = defineModel()
const emit = defineEmits<{
  onChange: [values: anyObject]
  onSubmit: [values: anyObject]
  onSubmitFailed: [e: anyObject]
}>()
const formRef = ref<FormInstance>()
const selectData = reactive({})
const initialValues = reactive({})
const stateFormValues = ref({})

const formValues = computed({
  get() {
    return modelValue.value || stateFormValues.value
  },
  set(values) {
    modelValue.value = values
    stateFormValues.value = values
  }
})

const context = computed(() => ({
  $values: formValues.value,
  $selectData: selectData,
  $initialValues: initialValues,
  ...props.schemaContext
}))

const formItems = computed(() => deepParse(props.schema.items || [], context.value))

// 保持schema的响应 传递给后代使用
const currentSchema = computed(() => props.schema)

// 表单底部
const defaultFooter = {
    name: 'FormFooter',
    props: {
        class: 'flex justify-end mt-4'
    },
    component: 'inline',
    children: [
        {
            name: 'divider_xAlcpi',
            props: {
                name: '提交',
                clickEvent: 'submitForm'
            },
            component: 'button',
            style: 'margin-right:10px'
        },
        {
            name: 'divider_UktsYm',
            props: {
                name: '重置',
                clickEvent: 'resetForm',
                type: 'default'
            },
            component: 'button'
        }
    ]
}
const footer = computed(() => props.schema.footer || defaultFooter)
const validate = () => formRef.value?.validate()

const submit = async () => {
  try {
    await validate()
    emit('onSubmit', formValues.value)
    return formValues.value
  } catch (e: any) {
    emit('onSubmitFailed', e)
    return Promise.reject(e)
  }
}

const getFormValues = () => ({ ...formValues.value })
const setFormValues = (values: anyObject) => {
  formValues.value = { ...formValues.value, ...values }
}

const resetFields = (names: string[]) => {
  if (names) {
    let temp = cloneDeep(formValues.value)

    names.forEach(name => {
      temp = setDataByPath(temp, name, getDataByPath(initialValues, name))
    })

    formValues.value = temp

  } else {
    formValues.value = initialValues
  }

  clearValidate();
}
// 这里做一个魔法操作，分栏操作，导致第二栏没有办法初始化，所以 resetFields 下
// 只有创建的时候进行该操作
onMounted(() => {
    resetFields([]);
})
// const reset validates
const clearValidate = () => {
  formRef.value?.clearValidate()
}
watch(
  formValues,
  (newVal, oldVal) => {
    emit('onChange', newVal)
    handleLinkages({ newVal, oldVal, formValues, formItems: formItems.value })
  },
  { deep: true }
)

watch(initialValues, newVal => {
  formValues.value = merge(formValues.value, newVal)
})

provide($schema, currentSchema)
provide($formValues, {
  formValues,
  updateFormValues: (values: anyObject) => (formValues.value = values)
})
provide($selectData, selectData)
provide($formEvents, { submit, validate, getFormValues, setFormValues, resetFields })
provide($initialValues, {
  initialValues,
  updateInitialValues: (values: anyObject) => Object.assign(initialValues, values)
})

defineExpose({ submit, validate, getFormValues, setFormValues, resetFields, context })
</script>
