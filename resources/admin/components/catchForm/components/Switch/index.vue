<script setup lang="ts">
import { onMounted } from 'vue'
import { before } from 'node:test'

const props = defineProps({
    value: {
        type: [Boolean, String, Number],
        default: false
    }
})

defineOptions({
    name: 'cswitch'
})

const valueModel = defineModel()
onMounted(() => {
    valueModel.value = props.value
})

const change = (val: any) => {
    valueModel.value = val
}
</script>

<template>
  <el-switch @change="change" v-model="valueModel"/>
</template>
