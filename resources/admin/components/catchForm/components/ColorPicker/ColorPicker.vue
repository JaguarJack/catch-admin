<script setup lang="ts">
import { onMounted } from 'vue'

const props = defineProps({
    value: {
        type: String,
        default: ''
    }
})

const modelValue = defineModel()

onMounted(() => {
    modelValue.value = props.value
})
</script>

<template>
<el-color-picker v-model="modelValue" v-bind="$attrs"/>
</template>

<style scoped lang="scss">

</style>
