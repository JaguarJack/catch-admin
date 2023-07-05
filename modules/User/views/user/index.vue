<template>
  <div class="flex flex-col sm:flex-row w-full justify-between">
    <Department v-model="query.department_id" @searchDepartmentUsers="search" v-if="hasRoles" class="dark:bg-regal-dark" />
    <div :class="hasRoles ? 'w-full ml-0 sm:ml-2 mt-2 sm:mt-0' : 'w-full'">
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
      <div class="table-default">
        <Operate :show="open">
          <template #operate>
            <el-button @click="download('/user')">导出</el-button>
          </template>
        </Operate>
        <el-table :data="tableData" class="mt-3" v-loading="loading">
          <el-table-column prop="username" label="用户名" width="150" />
          <el-table-column prop="avatar" label="头像">
            <template #default="scope">
              <el-avatar :icon="UserFilled" v-if="!scope.row.avatar" />
              <el-avatar :src="scope.row.avatar" v-else />
            </template>
          </el-table-column>
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
        <Create @close="close(reset)" :primary="id" :api="api" :has-roles="hasRoles" />
      </Dialog>
    </div>
  </div>
</template>

<script lang="ts" setup>
// @ts-nocheck
import { computed, onMounted, ref } from 'vue'
import Create from './create.vue'
import { useGetList } from '/admin/composables/curd/useGetList'
import { useDestroy } from '/admin/composables/curd/useDestroy'
import { useOpen } from '/admin/composables/curd/useOpen'
import Department from './components/department.vue'
import { useUserStore } from '/admin/stores/modules/user'
import { isUndefined } from '/admin/support/helper'
import { UserFilled } from '@element-plus/icons-vue'
import { useExcelDownload } from '/resources/admin/composables/curd/useExcelDownload'

const userStore = useUserStore()

const api = 'users'
const { data, query, search, reset, loading } = useGetList(api)
const { destroy, deleted } = useDestroy()
const { open, close, title, visible, id } = useOpen()
const { download } = useExcelDownload()

const tableData = computed(() => data.value?.data)

const roles = ref<Array<Object>>()
const hasRoles = ref<boolean>(false)

onMounted(() => {
  search()
  deleted(reset)
  hasRoles.value = !isUndefined(userStore.getRoles)
})
</script>
