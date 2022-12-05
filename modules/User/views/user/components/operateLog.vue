<template>
  <el-table :data="tableData" class="mt-3" v-loading="loading">
    <el-table-column prop="username" label="用户名" width="180" />
    <el-table-column prop="avatar" label="头像" width="180" />
    <el-table-column prop="email" label="邮箱" />
    <el-table-column prop="status" label="状态">
      <template #default="scope">
        <Status v-model="scope.row.status" :id="scope.row.id" :api="api" />
      </template>
    </el-table-column>
    <el-table-column prop="created_at" label="创建时间" />
  </el-table>

  <div class="pt-2 pb-2 flex justify-end">
    <el-pagination
      background
      v-if="total > query.limit"
      layout="total,sizes,prev, pager,next"
      :current-page="query.page"
      :page-size="query.limit"
      @current-change="changePage"
      @size-change="changeLimit"
      :total="total"
      :page-sizes="[10, 20, 30, 50]"
    />
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted } from 'vue'
import { useGetList } from '/admin/composables/curd/useGetList'

const api = 'users'

const { data, query, search, reset, changePage, changeLimit, loading } = useGetList(api)

onMounted(() => search())

const tableData = computed(() => data.value?.data)
const total = computed(() => data.value?.total)
</script>

<style scoped></style>
