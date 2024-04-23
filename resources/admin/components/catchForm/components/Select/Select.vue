<template>
  <el-select v-bind="$attrs" class="w-full" clearable filterable :multiple="multiple" :value-key="valueKey" v-model="modelValue">
    <template v-if="group">
      <el-option-group v-for="group in elOptions" :key="group[label]" :label="group[label]">
        <el-option v-for="item in group.options" :key="item[valueKey]" :label="item[label]" :value="item[valueKey]" />
      </el-option-group>
    </template>
    <el-option v-for="option in elOptions" :key="option[valueKey]" :label="option[label]" :value="option[valueKey]" v-else>
      <slot />
    </el-option>
  </el-select>
</template>

<script lang="ts" setup>
import {  inject, ref, watch } from 'vue'
import { $global} from '/admin/components/catchForm/config/symbol'

const props = defineProps({
  options: {
    type: Array,
    require: false,
    default: () => {
      return []
    }
  },
  label: {
    type: String,
    default: 'label'
  },
  valueKey: {
      type: String,
      default: 'value'
  },
  multiple: {
    type: Boolean,
    default: false
  },
  api: {
    type: String,
    require: false,
    default: ''
  },
  group: {
    type: Boolean,
    default: false
  },
  query: {
    type: Object,
    default: null
  }
})

interface Option {
  label: string
  value: string | number
}

interface GroupOption {
  label: string
  options: Array<Option>
}

const modelValue = defineModel()

const { http } = inject($global)
const getOptions = () => {
    http.get(props.api, props.query).then(r => {
    elOptions.value = r.data.data
  })
}

const elOptions: any = props.group ? ref<Array<GroupOption>>() : ref<Array<Option>>()
if (props.api) {
  if (!props.query) {
    getOptions()
  } else {
    watch(props, function () {
      getOptions()
    })
  }
} else {
  elOptions.value = props.options
}
</script>
