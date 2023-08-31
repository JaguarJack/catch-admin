<template>
  <div>
    <Search :search="search" :reset="reset">
      <template v-slot:body>
        <el-form-item label="菜单名称" prop="permission_name">
          <el-input v-model="query.permission_name" name="permission_name" clearable />
        </el-form-item>
      </template>
    </Search>
    <div class="table-default">
      <Operate :show="open" />
      <el-table :data="tableData" class="mt-3" v-loading="loading" row-key="id" default-expand-all :tree-props="{ children: 'children' }">
        <el-table-column prop="permission_name" label="菜单名称" />
        <el-table-column prop="route" label="菜单路由" />
        <el-table-column prop="permission_mark" label="权限标识" width="330">
          <template #default="scope">
            <div v-if="scope.row.actions.length" class="flex grid gap-1 grid-cols-4">
              <el-tag v-for="action in scope.row.actions" class="cursor-pointer min-w-fit" @click="open(action.id)" closable @close="destroy(api, action.id)">{{ action.permission_name }}</el-tag>
            </div>
            <div v-else>
              <el-popconfirm confirm-button-text="确认" title="添加基础actions" @confirm="actionGenerate(scope.row.id)" placement="top">
                <template #reference>
                  <el-tag class="cursor-pointer w-8" v-if="scope.row.type === MenuType.PAGE_TYPE">
                    <Icon name="cog-6-tooth" class="animate-spin w-5 h-5" v-if="generateId === scope.row.id" />
                    <Icon name="plus" className="w-4 h-4" v-else />
                  </el-tag>
                </template>
              </el-popconfirm>
            </div>
          </template>
        </el-table-column>
        <el-table-column prop="hidden" label="状态" width="100">
          <template #default="scope">
            <Status v-model="scope.row.hidden" :id="scope.row.id" :api="api" @refresh="search" />
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
import { computed, onMounted, ref } from 'vue'
import Create from './form/create.vue'
import { useGetList } from '/admin/composables/curd/useGetList'
import { useDestroy } from '/admin/composables/curd/useDestroy'
import { useOpen } from '/admin/composables/curd/useOpen'
import { MenuType } from '/admin/enum/app'
import http from '../../../../resources/admin/support/http'

const api = 'permissions/permissions'

const { data, query, search, reset, loading } = useGetList(api, false)
const { destroy, deleted } = useDestroy()
const { open, close, title, visible, id } = useOpen()

const tableData = computed(() => data.value?.data)

onMounted(() => {
  search()
  deleted(reset)
})

const actionLoading = ref<boolean>(false)
const generateId = ref<number>(0)
const actionGenerate = async (id: number) => {
  generateId.value = id
  http
    .post(api, { parent_id: id, actions: true })
    .then(r => {
      search()
      generateId.value = 0
    })
    .catch(e => {
      generateId.value = 0
      catchtable.value.reset()
    })
}
</script>
