<template>
  <div class="grid w-full grid-cols-1 gap-4 pl-2 sm:grid-cols-3 lg:grid-cols-6 place-content-between">
    <template v-for="(item, k) in fields" :key="k">
      <div v-if="item.show && (k < 3 || expand)" class="align-items-center justify-items-center">
        <el-form-item :label="item.label" v-if="item.type === 'input'">
          <el-input :placeholder="item.placeholder" v-model="query[item.name]" clearable />
        </el-form-item>
        <el-form-item :label="item.label" v-if="item.type === 'select'">
          <Select :api="item.api" :options="item.options" v-model="query[item.name]" :placeholder="item.placeholder" />
        </el-form-item>
        <el-form-item :label="item.label" v-if="item.type === 'input-number'">
          <el-input-number v-model="query[item.name]" :placeholder="item.placeholder" clearable />
        </el-form-item>
        <el-form-item :label="item.label" v-if="item.type === 'date'">
          <el-date-picker type="date" v-model="query[item.name]" :placeholder="item.placeholder" clearable value-format="YYYY-MM-DD" />
        </el-form-item>
        <el-form-item :label="item.label" v-if="item.type === 'datetime'">
          <el-date-picker type="datetime" v-model="query[item.name]" :placeholder="item.placeholder" clearable value-format="YYYY-MM-DD HH:mm:ss" />
        </el-form-item>
        <el-form-item :label="item.label" v-if="item.type === 'tree'">
          <el-tree-select v-model="query[item.name]" value-key="id" :placeholder="item.placeholder" clearable :data="item.options" check-strictly :props="item.props" />
        </el-form-item>
        <template v-if="item.type === 'range'">
          <div class="flex flex-wrap">
            <el-form-item :label="item.label">
              <div class="flex flex-wrap gap-x-2">
                <div v-for="(sitem, key) in item.children" :key="key">
                  <el-input v-model="query[item.name]" :placeholder="item.placeholder" v-if="sitem.type === 'input'" clearable />
                  <Select :api="item.api" :options="item.options" v-model="query[item.name]" :placeholder="item.placeholder" clearable v-if="item.type === 'select'" />
                  <el-input-number v-model="query[item.name]" :placeholder="item.placeholder" v-if="sitem.type === 'input-number'" clearable />
                  <el-date-picker type="date" v-model="query[item.name]" :placeholder="item.placeholder" v-if="sitem.type === 'date'" clearable value-format="YYYY-MM-DD" />
                  <el-date-picker type="datetime" v-model="query[item.name]" :placeholder="item.placeholder" v-if="sitem.type === 'datetime'" clearable value-format="YYYY-MM-DD HH:mm:ss" />
                </div>
              </div>
            </el-form-item>
          </div>
        </template>
      </div>
    </template>
    <div class="flex justify-end col-end-0 sm:col-end-5 lg:col-end-8">
      <el-button type="primary" @click="search"> {{ t('system.search') }} </el-button>
      <el-button @click="reset"> {{ t('system.reset') }} </el-button>
      <el-button @click="expandSearch()" v-if="fields.length > 3" text circle>
        <Icon :name="expand ? 'chevron-up' : 'chevron-down'" className="w-4 h-4" />
      </el-button>
    </div>
  </div>
</template>
<script lang="ts" setup>
// @ts-nocheck
import { reactive, ref } from 'vue'
import { isBoolean, t } from '/admin/support/helper'
type itemType = 'input' | 'select' | 'input-number' | 'date' | 'datetime' | 'range'
interface Option {
  label: string
  value: string | number
}
interface field {
  type: itemType
  label: string
  name: string
  api?: string
  placeholder?: string
  default?: any
  options?: Array<Option>
  children?: Array<field>
  show?: boolean
  props?: Object // 树形 props
}

const props = defineProps({
  fields: {
    type: Array<field>,
    default: () => {
      return []
    }
  }
})

const emits = defineEmits(['search', 'reset'])
const query = ref<Object>({})
const expand = ref<boolean>(false)

const _fields = ref<Array<field>>(props.fields)
_fields.value = _fields.value?.map(field => {
  if (!field.placeholder) {
    field.placeholder = (field.type === 'select' ? '请选择' : '请输入') + field.label
  }
  field.show = isBoolean(field.show) ? field.show : true
  return reactive(field)
})

const search = () => {
  emits('search', query.value, true)
}

const reset = () => {
  query.value = {}
  emits('reset')
}
const expandSearch = () => {
  expand.value = !expand.value
}

defineExpose({ reset })
</script>
<style lang="scss" scoped>
:deep(.el-form-item__label) {
  font-size: 14px !important;
}

:deep(.el-form-item) {
  margin-bottom: 0px !important;
}
</style>
