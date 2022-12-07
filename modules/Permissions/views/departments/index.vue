<template>
  <div>
    <Search :search="search" :reset="reset">
      <template v-slot:body>
        <el-form-item label="部门名称" prop="department_name">
          <el-input v-model="query.department_name" name="department_name" clearable />
        </el-form-item>
      </template>
    </Search>
    <div class="pl-2 pr-2 bg-white dark:bg-regal-dark rounded-lg mt-4 pb-6">
      <Operate :show="open" />
      <el-table :data="tableData" class="mt-3" v-loading="loading" row-key="id" default-expand-all :tree-props="{ children: 'children' }">
        <el-table-column prop="department_name" label="部门名称" />
        <el-table-column prop="sort" label="排序" />
        <el-table-column prop="status" label="状态">
          <template #default="scope">
            <Status v-model="scope.row.status" :id="scope.row.id" :api="api" @refresh="search" />
          </template>
        </el-table-column>
        <el-table-column prop="created_at" label="创建时间" />
        <el-table-column label="操作" width="200">
          <template #default="scope">
            <Update @click="open(scope.row.id)" />
            <Destroy @click="destroy(api, scope.row.id)" />
          </template>
        </el-table-column>
      </el-table>
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

const api = 'permissions/departments'

const { data, query, search, reset, loading } = useGetList(api)
const { destroy, deleted } = useDestroy()
const { open, close, title, visible, id } = useOpen()

const tableData = computed(() => data.value?.data)

onMounted(() => {
  search()

  deleted(reset)
})
</script>
