<template>
  <ElCollapse v-bind="{ ...props, ...$attrs }" v-model="activeKey">
    <ElCollapseItem v-for="item in children" :key="item.name" :name="item.name">
      <template #title>
        <Title :title="item.title" italic type="h4" />
      </template>
      <FormRender :formItems="item.children" />
    </ElCollapseItem>
  </ElCollapse>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import FormRender from '/admin/components/catchForm/FormRender.vue'
import Title from '../Title/Title.vue'
const thisProps = defineProps({
  props: Object,
  children: Array
})

const activeKey = ref([])

onMounted(() => {
  activeKey.value = thisProps.children.filter(item => item.checked).map(item => item.name)
})
</script>

<style lang="scss">
.form-item-grid {
  .el-form-item {
    margin-bottom: 0;
  }
}
</style>
