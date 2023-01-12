<template>
  <div class="table-default">
    <el-table :data="tableData" class="mt-3" v-loading="loading">
      <el-table-column prop="account" label="账户" />
      <el-table-column prop="browser" label="浏览器" />
      <el-table-column prop="platform" label="平台" />
      <el-table-column prop="login_ip" label="IP" />
      <el-table-column prop="status" label="状态">
        <template #default="scope">
          <el-tag type="success" v-if="scope.row.status === 1">成功</el-tag>
          <el-tag type="danger" v-else>失败</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="login_at" label="登录时间" />
    </el-table>

    <Paginate />
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted } from 'vue'
import { useGetList } from '/admin/composables/curd/useGetList'

const api = 'user/login/log'

const { data, query, search, reset, loading } = useGetList(api)

onMounted(() => search())

const tableData = computed(() => data.value?.data)
</script>

<style scoped></style>
