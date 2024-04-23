<template>
  <ElCard v-bind="{ ...props, ...$attrs }">
    <FormRender :formItems="children" />
  </ElCard>
</template>

<script setup>
import { ElCard } from 'element-plus'
import FormRender from '/admin/components/catchForm/FormRender.vue'

defineProps({
  props: Object,
  children: Array,
  design: Boolean
})
</script>

<style lang="scss">
.form-item-grid {
  .el-form-item {
    margin-bottom: 0;
  }
}
</style>
