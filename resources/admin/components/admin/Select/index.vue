<template>
  <el-select v-bind="$attrs">
    <el-option-group v-for="group in elOptions" :key="group.label" :label="group.label" v-if="group">
      <el-option v-for="item in group.options" :key="item.value" :label="item.label" :value="item.value" />
    </el-option-group>
    <el-option v-for="option in elOptions" :key="option.value" :label="option.label" :value="option.value" v-else>
      <slot />
    </el-option>
  </el-select>
</template>

<script lang="ts" setup>
import http from '/admin/support/http'
import { ref } from 'vue'

const props = defineProps({
  options: {
    type: Array,
    default: [],
  },
  api: {
    type: String,
    default: '',
  },
  group: {
    type: Boolean,
    default: false,
  },
})

interface Option {
  label: string
  value: string | number
}

interface GroupOption {
  label: string
  options: Array<Option>
}

const elOptions = props.group ? ref<Array<GroupOption>>() : ref<Array<Option>>()
if (props.api) {
  http.get('options/' + props.api).then(r => {
    elOptions.value = r.data.data
  })
} else {
  elOptions.value = props.options
}
</script>
