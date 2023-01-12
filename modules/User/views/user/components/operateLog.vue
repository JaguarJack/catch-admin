<template>
  <div class="table-default">
    <div class="w-full flex justify-end">
      <el-radio-group v-model="query.scope" size="small" @change="search">
        <el-radio-button label="self">只看自己</el-radio-button>
        <el-radio-button label="all">全部</el-radio-button>
      </el-radio-group>
    </div>
    <el-table :data="tableData" class="mt-3" v-loading="loading">
      <el-table-column prop="creator" label="创建人" />
      <el-table-column prop="module" label="模块" />
      <el-table-column prop="action" label="操作" width="150" />
      <el-table-column prop="http_method" label="请求方法" width="90" />
      <el-table-column prop="http_code" label="请求状态" width="90">
        <template #default="scope">
          <el-tag type="success" v-if="scope.row.http_code >= 200 && scope.row.http_code < 300"> {{ scope.row.http_code }}</el-tag>
          <el-tag type="danger" v-else>{{ scope.row.http_code }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="time_taken" label="耗时" />
      <el-table-column prop="params" label="参数">
        <template #default="scope">
          <el-tooltip class="box-item" effect="dark" :content="scope.row.params" placement="top-start">
            <el-button size="small" type="primary">查看</el-button>
          </el-tooltip>
        </template>
      </el-table-column>
    </el-table>
    <Paginate />
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted } from 'vue'
import { useGetList } from '/admin/composables/curd/useGetList'

const api = 'user/operate/log'

const { data, query, search, reset, loading } = useGetList(api)

query.value.scope = 'self'

onMounted(() => search())

const tableData = computed(() => data.value?.data)
</script>

<style scoped></style>
