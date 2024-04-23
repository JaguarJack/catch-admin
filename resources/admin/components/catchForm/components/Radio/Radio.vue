<!-- eslint-disable vue/no-multiple-template-root -->
<template>
  <div v-if="!options.length" style="font-size: 12px">暂无选项</div>
  <el-radio-group v-model="modelValue" @change="clickRadio" v-bind="$attrs">
    <el-space wrap :direction="direction" :size="[space, space]" alignment="normal" v-if="space > 0">
      <template v-if="optionType === 'circle' || optionType === 'border'">
        <el-radio v-for="item in options"  :key="item[value]" :value="item[value]" :border="optionType === 'border'">{{ item[label] }}</el-radio>
      </template>

      <template v-else>
        <el-radio-button v-for="item in options"  :key="item[value]" :value="item[value]" :size="$attrs.size">{{ item[label] }} </el-radio-button>
      </template>
    </el-space>
    <div v-else>
      <template v-if="optionType === 'circle' || optionType === 'border'">
        <el-radio v-for="item in options"  :key="item[value]" :value="item[value]" :border="optionType === 'border'">{{ item[label] }}</el-radio>
      </template>

      <template v-else>
        <el-radio-button v-for="item in options"  :key="item[value]" :value="item[value]" :size="$attrs.size">{{ item[label] }} </el-radio-button>
      </template>
    </div>
  </el-radio-group>
</template>

<script setup>
// import { defineEmits } from 'vue'
// import useSelect from '/admin/components/catchForm/hooks/useSelect'

defineProps({
  options: {
    type: Array,
    default: () => []
  },
  label: {
    type: String,
    default: 'label'
  },
  value: {
    type: String,
    default: 'value'
  },
  autoSelectedFirst: {
    type: Boolean,
    default: false
  },
  api: Object,
  name: String,
  optionType: {
    type: String,
    default: 'circle'
  },

  direction: {
    type: String,
    default: 'horizontal'
  },
  space: {
    type: Number,
    default: 0
  }
})

const modelValue = defineModel()
const clickRadio = value => {
    modelValue.value = value
}
</script>
