<template>
  <div>
    <div class="w-full min-h-0 bg-white dark:bg-regal-dark pl-5 pt-5 pr-5 rounded-lg">
      <el-form :inline="true">
        <el-form-item label="模块名称">
          <el-input v-model="query.name" name="name" clearable />
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
    <div class="pl-2 pr-2 bg-white dark:bg-regal-dark rounded-lg mt-4">
      <div class="pt-5 pl-2">
        <Add @click="show(null)" />
      </div>
      <el-table :data="tableData" class="mt-3" v-loading="loading">
        <el-table-column prop="name" label="模块名称" width="180" />
        <el-table-column prop="path" label="模块目录" width="180" />
        <el-table-column prop="version" label="模块版本">
          <template #default="scope">
            <el-tag type="warning">{{ scope.row.version }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="enable" label="模块状态">
          <template #default="scope">
            <el-switch v-model="scope.row.enable" @change="enabled(api, scope.row.name)" />
          </template>
        </el-table-column>
        <el-table-column label="操作" width="300">
          <template #default="scope">
            <Update @click="show(scope.row.name)" />
            <Destroy @click="destroy(api, scope.row.name)" />
          </template>
        </el-table-column>
      </el-table>

      <div class="pt-2 pb-2 flex justify-end">
        <el-pagination
          background
          layout="total,sizes,prev, pager,next"
          :current-page="query.page"
          :page-size="query.limit"
          @current-change="changePage"
          @size-change="changeLimit"
          :total="total"
          :page-sizes="[10, 20, 30, 50]"
        />
      </div>
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
import Sortable from 'sortablejs'

const visible = ref<boolean>(false)
const id = ref(null)
const api = 'module'
const title = ref<string>('')

const { data, query, search, reset, changePage, changeLimit, loading } = useGetList(api)
const { destroy, isDeleted } = useDestroy('确认删除吗? ⚠️将会删除模块下所有文件')
const { enabled } = useEnabled()

onMounted(() => search())

const tableData = computed(() => data.value?.data)
const total = computed(() => data.value?.total)

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
  isDeleted.value = false
  reset()
})
</script>
