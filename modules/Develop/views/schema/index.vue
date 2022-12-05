<template>
  <div>
    <div class="w-full min-h-0 bg-white dark:bg-regal-dark pl-5 pt-5 pr-5 rounded-lg">
      <el-form :inline="true">
        <el-form-item label="模块名称">
          <el-input v-model="query.module" name="module" clearable />
        </el-form-item>
        <el-form-item label="Schema 名称">
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
        <Add @click="add(null)" />
      </div>
      <el-table :data="tableData" class="mt-3" v-loading="loading">
        <el-table-column prop="module" label="所属模块" />
        <el-table-column prop="name" label="schema 名称" />
        <el-table-column prop="columns" label="字段">
          <template #default="scope">
            <el-button size="small" type="success" @click="show(scope.row.id)"><Icon name="eye" class="w-3 mr-1" /> 查看</el-button>
          </template>
        </el-table-column>
        <el-table-column prop="is_soft_delete" label="?软删">
          <template #default="scope">
            <el-tag v-if="scope.row.is_soft_delete">是</el-tag>
            <el-tag type="danger" v-else>否</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="created_at" label="创建时间" />
        <el-table-column label="操作" width="250">
          <template #default="scope">
            <router-link :to="'/develop/generate/' + scope.row.id">
              <el-button type="warning" size="small"><Icon name="wrench-screwdriver" class="w-3 mr-1" /> 生成代码</el-button>
            </router-link>
            <Destroy @click="destroy(api, scope.row.id)" class="ml-2" />
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

    <!-- schema 创建 -->
    <Dialog v-model="visible" :title="$t('generate.schema.title')" width="650px" destroy-on-close>
      <Create @close="close" :primary="id" :api="api" />
    </Dialog>

    <!-- schema 表结构 -->
    <Dialog v-model="showVisible" title="Schema 结构" width="650px" destroy-on-close>
      <Show :id="id" :api="api" />
    </Dialog>
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'
import Create from './create.vue'
import Show from './show.vue'

import { useGetList } from '/admin/composables/curd/useGetList'
import { useDestroy } from '/admin/composables/curd/useDestroy'

const visible = ref<boolean>(false)
const showVisible = ref<boolean>(false)

const id = ref<number>()
const api = 'schema'
const title = ref<string>('')

const { data, query, search, reset, changePage, changeLimit, loading } = useGetList(api)
const { destroy, isDeleted } = useDestroy('确认删除吗? 将会删除数据库的 Schema，请提前做好备份，一旦删除，将无法恢复!')

onMounted(() => search())

const tableData = computed(() => data.value?.data)
const total = computed(() => data.value?.total)

const close = () => {
  visible.value = false
  reset()
}

const add = () => {
  visible.value = true
}

const show = primaryId => {
  showVisible.value = true

  id.value = primaryId
}

watch(isDeleted, function () {
  isDeleted.value = false
  reset()
})
</script>
