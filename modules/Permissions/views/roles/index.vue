<template>
  <div>
    <div class="w-full min-h-0 bg-white dark:bg-regal-dark pl-5 pt-5 pr-5 rounded-lg">
      <el-form :inline="true">
        <el-form-item label="角色名称" prop="role_name">
          <el-input v-model="query.role_name" name="role_name" clearable />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="search()">
            <Icon name="magnifying-glass" class="w-4 mr-1 -ml-1" />
            搜索
          </el-button>
          <el-button @click="reset()">
            <Icon name="arrow-path" class="w-4 mr-1 -ml-1" />
            重置
          </el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="pl-2 pr-2 bg-white dark:bg-regal-dark rounded-lg mt-4 pb-10">
      <div class="pt-5 pl-2">
        <Add @click="show(null)" />
      </div>
      <el-table :data="tableData" class="mt-3" v-loading="loading" row-key="id" default-expand-all :tree-props="{ children: 'children' }">
        <el-table-column prop="role_name" label="角色名称" />
        <el-table-column prop="identify" label="角色标识" />
        <el-table-column prop="description" label="角色描述" />
        <el-table-column prop="created_at" label="创建时间" />
        <el-table-column label="操作" width="200">
          <template #default="scope">
            <Update @click="show(scope.row.id)" />
            <Destroy @click="destroy(api, scope.row.id)" />
          </template>
        </el-table-column>
      </el-table>
    </div>
    <Dialog v-model="visible" :title="title" destroy-on-close>
      <Create @close="close" :primary="id" :api="api" />
    </Dialog>
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'
import Create from './create.vue'
import { useGetList } from '/admin/composables/curd/useGetList'
import { useDestroy } from '/admin/composables/curd/useDestroy'
import { useEnabled } from '/admin/composables/curd/useEnabled'
import { t } from '/admin/support/helper'

const visible = ref<boolean>(false)
const id = ref(null)
const api = 'permissions/roles'
const title = ref<string>('')

const { data, query, search, reset, loading } = useGetList(api)

const { destroy, isDeleted } = useDestroy()

onMounted(() => search())

const tableData = computed(() => data.value?.data)

const close = () => {
  visible.value = false
  reset()
}

const show = primary => {
  title.value = primary ? t('system.edit') : t('system.add')
  id.value = primary
  visible.value = true
}

watch(isDeleted, function () {
  // change origin status
  isDeleted.value = false
  reset()
})
</script>
