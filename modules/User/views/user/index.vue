<template>
  <div class="flex flex-col sm:flex-row w-full justify-between">
  <Department v-model="query.department_id" @searchDepartmentUsers="search" v-if="false"/>
  <div class="w-full ml-5">
    <Search :search="search" :reset="reset">
      <template v-slot:body>
        <el-form-item label="用户名">
          <el-input v-model="query.username" clearable />
        </el-form-item>
        <el-form-item label="邮箱">
          <el-input v-model="query.email" clearable />
        </el-form-item>
        <el-form-item label="状态">
          <Select v-model="query.status" clearable api="status" />
        </el-form-item>
      </template>
    </Search>
    <div class="pl-2 pr-2 bg-white dark:bg-regal-dark rounded-lg mt-4 pb-6">
      <Operate :show="open" />
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
      <Create @close="close" :primary="id" :api="api" />
    </Dialog>
  </div>
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted } from 'vue'
import Create from './create.vue'
import { useGetList } from '/admin/composables/curd/useGetList'
import { useDestroy } from '/admin/composables/curd/useDestroy'
import { useOpen } from '/admin/composables/curd/useOpen'
import Department from './components/department.vue'

const api = 'users'

const { data, query, search, reset, loading } = useGetList(api)
const { destroy, deleted } = useDestroy()
const { open, close, title, visible, id } = useOpen()

const tableData = computed(() => data.value?.data)

onMounted(() => {
  search()

  deleted(reset)
})
</script>
