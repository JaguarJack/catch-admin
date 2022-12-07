<template>
  <el-table :data="data?.columns" class="mt-3" v-loading="loading">
    <el-table-column prop="name" label="字段名称" />
    <el-table-column prop="type" label="类型" />
    <el-table-column prop="nullable" label="nullable">
      <template #default="scope">
        <el-tag v-if="scope.row.nullable">是</el-tag>
        <el-tag type="danger" v-else>否</el-tag>
      </template>
    </el-table-column>
    <el-table-column prop="default" label="默认值">
      <template #default="scope"> </template>
    </el-table-column>
    <el-table-column prop="comment" label="注释" />
  </el-table>
</template>

<script lang="ts" setup>
import { useShow } from '/admin/composables/curd/useShow'
const props = defineProps({
  id: {
    type: Number,
    required: true,
  },
})

// const data = ref<Array<object>>()
const { data, loading } = useShow('schema', props.id)
</script>

<style scoped></style>
