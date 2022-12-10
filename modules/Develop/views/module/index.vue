<template>
  <div>
    <Search :search="search" :reset="reset">
      <template v-slot:body>
        <el-form-item label="模块名称">
          <el-input v-model="query.title" name="title" clearable />
        </el-form-item>
      </template>
    </Search>
    <div class="pl-2 pr-2 bg-white dark:bg-regal-dark rounded-lg mt-4 pb-6">
      <Operate :show="open" />
      <el-table :data="tableData" class="mt-3" v-loading="loading">
        <el-table-column prop="title" label="模块名称" width="180" />
        <el-table-column prop="path" label="模块目录" width="180" />
        <el-table-column prop="version" label="模块版本">
          <template #default="scope">
            <el-tag type="warning">{{ scope.row.version }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="enable" label="模块状态">
          <template #default="scope">
            <Status v-model="scope.row.enable" :id="scope.row.name" :api="api" />
          </template>
        </el-table-column>
        <el-table-column label="操作" width="300">
          <template #default="scope">
            <Update @click="open(scope.row.name)" />
            <Destroy @click="destroy(api, scope.row.name)" />
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

const api = 'module'

const { data, query, search, reset, loading } = useGetList(api)
const { destroy, deleted } = useDestroy('确认删除吗? ⚠️将会删除模块下所有文件')
const { open, close, title, visible, id } = useOpen()

const tableData = computed(() => data.value?.data)

onMounted(() => {
  search()

  deleted(reset)
})
</script>
