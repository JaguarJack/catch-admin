<template>
  <div>
    <Search :search="search" :reset="reset">
      <template v-slot:body>
        <el-form-item label="模块名称">
          <el-input v-model="query.module" name="module" clearable />
        </el-form-item>
        <el-form-item label="Schema 名称">
          <el-input v-model="query.name" name="name" clearable />
        </el-form-item>
      </template>
    </Search>
    <div class="pl-2 pr-2 bg-white dark:bg-regal-dark rounded-lg mt-4 pb-6">
      <Operate :show="open" />
      <el-table :data="tableData" class="mt-3" v-loading="loading">
        <el-table-column prop="module" label="所属模块" />
        <el-table-column prop="name" label="schema 名称" />
        <el-table-column prop="columns" label="字段">
          <template #default="scope">
            <el-button size="small" type="success" @click="view(scope.row.id)"><Icon name="eye" class="w-3 mr-1" /> 查看</el-button>
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
      <Paginate />
    </div>

    <!-- schema 创建 -->
    <Dialog v-model="visible" :title="title" width="650px" destroy-on-close>
      <Create @close="close(reset)" :api="api" />
    </Dialog>

    <!-- schema 表结构 -->
    <Dialog v-model="schemaVisible" title="Schema 结构" width="650px" destroy-on-close>
      <Show :id="id" :api="api" />
    </Dialog>
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import Create from './create.vue'
import Show from './show.vue'
import { useGetList } from '/admin/composables/curd/useGetList'
import { useDestroy } from '/admin/composables/curd/useDestroy'
import { useOpen } from '/admin/composables/curd/useOpen'

const schemaVisible = ref<boolean>(false)

const api = 'schema'

const { data, query, search, reset, loading } = useGetList(api)
const { destroy, deleted } = useDestroy('确认删除吗? 将会删除数据库的 Schema，请提前做好备份，一旦删除，将无法恢复!')
const { open, close, title, visible, id } = useOpen()

const tableData = computed(() => data.value?.data)

const view = primaryId => {
  schemaVisible.value = true

  id.value = primaryId
}

onMounted(() => {
  search()

  deleted(reset)
})
</script>
