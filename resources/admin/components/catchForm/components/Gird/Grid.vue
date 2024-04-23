<template>
  <div class="form-item-grid">
    <div :style="gridStyle">
      <FormRender :formItems="children" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import FormRender from '/admin/components/catchForm/FormRender.vue'

const thisProps = defineProps({
  name: String,
  props: Object,
  children: Array,
  design: Boolean
})
const gridStyle = computed(() => ({
  display: 'grid',
  'grid-template-columns': `repeat(${thisProps.props.columns}, 1fr)`,
  'row-gap': thisProps.props['row-gap'] + 'px',
  'column-gap': thisProps.props['column-gap'] + 'px'
}))
</script>

<style scoped lang="scss">
.form-item-grid {
  .el-form-item {
    margin-bottom: 0;
  }
  .el-form-item__content {
    align-items: start;
  }
}
</style>
