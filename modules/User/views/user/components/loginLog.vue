<template>
  <el-table :data="tableData" class="mt-3" v-loading="loading">
    <el-table-column prop="account" label="账户" width="150px" />
    <el-table-column prop="browser" label="浏览器" width="100px" />
    <el-table-column prop="platform" label="平台" width="100px" />
    <el-table-column prop="login_ip" label="IP" width="120px" />
    <el-table-column prop="status" label="状态" width="100px">
      <template #default="scope">
        <el-tag type="success" v-if="scope.row.status === 1">成功</el-tag>
        <el-tag type="danger" v-else>失败</el-tag>
      </template>
    </el-table-column>
    <el-table-column prop="login_at" label="登录时间" />
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

const api = 'user/login/log'

const { data, query, search, changePage, changeLimit, loading } = useGetList(api)

onMounted(() => search())

const tableData = computed(() => data.value?.data)
const total = computed(() => data.value?.total)
</script>

<style scoped></style>
