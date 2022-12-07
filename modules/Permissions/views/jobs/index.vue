<template>
  <div>
    <Search :search="search" :reset="reset">
      <template v-slot:body>
        <el-form-item label="岗位名称" prop="job_name">
          <el-input v-model="query.job_name" name="job_name" clearable />
        </el-form-item>
      </template>
    </Search>
    <div class="pl-2 pr-2 bg-white dark:bg-regal-dark rounded-lg mt-4 pb-6">
      <Operate :show="open" />
      <el-table :data="tableData" class="mt-3" v-loading="loading">
        <el-table-column prop="job_name" label="岗位名称" />
        <el-table-column prop="coding" label="岗位编码" />
        <el-table-column prop="status" label="状态">
          <template #default="scope">
            <Status v-model="scope.row.status" :id="scope.row.id" :api="api" />
          </template>
        </el-table-column>
        <el-table-column prop="sort" label="排序" />
        <el-table-column prop="description" label="岗位描述" />
        <el-table-column label="操作" width="200">
          <template #default="scope">
            <Update @click="open(scope.row.id)" />
            <Destroy @click="destroy(api, scope.row.id)" />
          </template>
        </el-table-column>
      </el-table>
      <Paginate />
    </div>

    <Dialog v-model="visible" :title="title" destroy-on-close>
      <Create @close="close(reset)" :primary="id" :api="api" />
    </Dialog>
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted } from 'vue'
import Create from './create.vue'
import { useGetList } from '/admin/composables/curd/useGetList'
import { useDestroy } from '/admin/composables/curd/useDestroy'
import { useOpen } from '/admin/composables/curd/useOpen'

const api = 'permissions/jobs'

const { data, query, search, reset, loading } = useGetList(api)
const { destroy, deleted } = useDestroy()

const tableData = computed(() => data.value?.data)
const { open, close, title, visible, id } = useOpen()

onMounted(() => {
  search()

  deleted(reset)
})
</script>
