<!--
 - 数据表格
 - 二次封装 ElementPlus 表格
 - author JaguarJack
 - 仅限 pro 试用
-->
<template>
  <div>
    <div v-if="searchFields.length > 0 && showSearch" class="flex flex-wrap gap-5 pt-5 pb-5 pl-5 pr-5 bg-white dark:bg-regal-dark">
      <csearch :fields="searchFields" @search="doSearch" @reset="reset" ref="csearchRef" />
      <slot name="csearch" />
    </div>
    <div class="pb-5 pl-5 pr-5 mt-3 bg-white dark:bg-regal-dark" :style="{ height: height }" ref="catch-table">
      <!-- 表格头部 -->
      <div class="h-16 pt-5">
        <!-- 多选 -->
        <div v-if="multiSelectIds.length > 0" class="flex justify-between">
          <div class="flex pt-2 text-sm text-slate-400"><Icon name="check" class="mr-2" /> 已选 {{ multiSelectIds.length }} 条数据</div>
          <div class="flex gap-2">
            <!--批量操作的插槽-->
            <slot name="multiOperate" />
            <el-button @click="destroy(props.api, multiSelectIds.join(','))" type="danger" plain size="small"> <Icon name="trash" className="w-4 h-4 mr-1" />删除 </el-button>
          </div>
        </div>
        <!-- 正常头部 -->
        <div class="flex flex-row justify-between" v-else>
          <div>
            <Add @click="openDialog" v-if="operation" />
            <el-button @click="download(exportUrl ? exportUrl : api)" v-if="exports">导出</el-button>
            <!-- 头部插槽 -->
            <slot name="operation" />
          </div>
          <!---表头 tools-->
          <div class="flex flex-row justify-end w-1/3 tools" v-if="showTools">
            <!-- 刷新 如果没有搜索，则使用默认的重置，有，就通过 ref 调用搜索的重置 -->
            <el-button text circle @click="csearchRef ? csearchRef.reset() : reset()">
              <el-icon><RefreshRight /></el-icon>
            </el-button>
            <!-- 密度 -->
            <el-button text circle>
              <el-dropdown size="small" @command="setTableSize">
                <el-icon><More /></el-icon>
                <template #dropdown>
                  <el-dropdown-menu class="w-20 mt-2">
                    <el-dropdown-item command="large" v-if="tableSize === 'large'">宽松</el-dropdown-item>
                    <el-dropdown-item command="large" v-else>宽松</el-dropdown-item>
                    <el-dropdown-item command="default" divided>默认</el-dropdown-item>
                    <el-dropdown-item command="small" divided>紧凑</el-dropdown-item>
                  </el-dropdown-menu>
                </template>
              </el-dropdown>
            </el-button>
            <!-- 栏目 -->
            <el-button text circle>
              <el-popover placement="bottom" :width="100" trigger="hover" title="列设置">
                <template #reference>
                  <Icon name="squares-plus" className="w-4 h-4" />
                </template>
                <template #default>
                  <div v-for="column in tableColumns" :key="column.prop">
                    <el-checkbox v-model="column.show" v-if="!column.type || column.type === 'operate'">
                      {{ column.label }}
                    </el-checkbox>
                  </div>
                </template>
              </el-popover>
            </el-button>
            <el-button @click="showSearch = !showSearch" text circle>
              <Icon name="magnifying-glass" className="w-4 h-4" />
            </el-button>
          </div>
        </div>
      </div>
      <div class="mt-3" :style="{ height: 'auto' }">
        <el-table
          v-loading="loading"
          v-bind="$attrs"
          :data="tableData"
          :row-key="rowKey"
          ref="catchtable"
          :height="height"
          :size="tableSize"
          :border="border"
          :empty-text="emptyText"
          :summary-method="summaryMethod"
          :default-sort="sort"
          @sort-change="customSortChange"
          @filter-change="filterChange"
          @selection-change="multiSelect"
          class="catchtable"
        >
          <!-- 无数据展示 -->
          <template #empty>
            <div>{{ emptyText }}</div>
          </template>
          <!-- column 展示 -->
          <template v-for="(column, index) in tableColumns" :key="index">
            <!--- selection | expand -->
            <el-table-column
              v-if="column.show && (column.type === 'selection' || column.type === 'expand')"
              :type="column.type"
              :prop="column.prop"
              :aligh="column.align"
              :label="column.label"
              :width="column.width"
              :min-width="column['min-width']"
              :align="column.align"
              :fixed="column.fixed"
            />
            <!--- type === index -->
            <el-table-column
              v-if="column.show && column.type === 'index'"
              :type="column.type"
              :prop="column.prop"
              :aligh="column.align"
              :label="column.label"
              :width="column.width"
              :min-width="column['min-width']"
              :align="column.align"
              :index="index"
              :fixed="column.fixed"
            />
            <!--- type === operate -->
            <el-table-column
              v-if="column.show && column.type === 'operate'"
              :aligh="column.align"
              :label="column.label"
              :width="column.width"
              :min-width="column['min-width']"
              :align="column.align"
              :fixed="column.fixed"
            >
              <template #default="scope">
                <slot name="_operate" v-bind="scope" />
                <Update v-if="column.update" @click="openDialog(scope.row)" />
                <Destroy v-if="column.destroy" @click="destroy(api as string, scope.row[primaryName])" />
                <slot name="operate" v-bind="scope" />
              </template>
            </el-table-column>
            <!--- 多级表头 || normal -->
            <tcolumns :column="column" v-if="column.show && !column.type" :api="api" @refresh="doSearch()">
              <template v-for="slot in Object.keys($slots)" #[slot]="scope: Record<string, any>">
                <slot :name="slot" v-bind="scope" />
              </template>
            </tcolumns>
          </template>
        </el-table>
      </div>
      <!--- 分页 -->
      <div v-if="showPaginate()">
        <el-pagination
          background
          :layout="layout"
          :current-page="page"
          :page-size="limit"
          @current-change="changePage"
          @size-change="changeLimit"
          :total="+total"
          :page-sizes="limits"
          class="flex justify-end mt-5"
        />
      </div>
      <Dialog v-model="visible" :title="title" destroy-on-close :width="dialogWidth" :height="dialogHeight">
        <slot name="dialog" v-bind="row" />
      </Dialog>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { reactive, ref, computed, onMounted, provide } from 'vue'
import tcolumns from './tcolumns.vue'
// import ctcolumns from './ctcolumns'
import { Column, SItem } from './ctable'
import csearch from './csearch.vue'
import { isBoolean } from '/admin/support/helper'
import useSearch from './useSearch'
import { useDestroy } from '/admin/composables/curd/useDestroy'
import { More, RefreshRight } from '@element-plus/icons-vue'
import { useExcelDownload } from '/admin/composables/curd/useExcelDownload'

// define props
const props = defineProps({
  // 请求接口
  api: {
    type: String,
    default: null
  },
  // 高度
  height: {
    type: [String, Number],
    default: () => {
      return 'auto'
    }
  },
  border: {
    type: Boolean,
    default: true
  },
  // table 的尺寸 'large', 'default', 'small'
  size: {
    type: String,
    default: 'default'
  },
  // 行数据的 Key，用来优化 Table 的渲染
  rowKey: {
    type: [String, Function],
    default: ''
  },
  // 空数据显示文本
  emptyText: {
    type: String,
    default: () => {
      return '暂无数据'
    }
  },
  // 自定义的合计计算方法 function({ columns, data })
  summaryMethod: {
    type: Function,
    default: () => {
      return null
    }
  },
  // 合并行或列的计算方法 function({ row, column, rowIndex, columnIndex })
  spanMethod: {
    type: Function,
    default: () => {
      return null
    }
  },

  // 分页设置
  pagination: {
    type: Boolean,
    default: true
  },
  // 每页显示数量
  limit: {
    type: Number,
    default: 10
  },
  // 页码
  page: {
    type: Number,
    default: 1
  },
  // 显示数量集合
  limits: {
    type: Array,
    default: () => {
      return [10, 20, 30, 40, 50]
    }
  },
  layout: {
    type: String,
    default: () => {
      return 'total,sizes,prev, pager,next'
    }
  },
  // 表格列设置
  columns: {
    type: Array<Column>,
    default: () => {
      return []
    }
  },
  // 排序
  sort: {
    type: Object,
    default: () => {
      return { order: '' }
    }
  },
  // 排序事件
  sortChange: {
    type: Function,
    defaualt: null
  },
  // 筛选事件
  filterChange: {
    type: Function,
    defaualt: null
  },
  searchForm: {
    type: Array<SItem>,
    default: () => {
      return []
    }
  },
  operation: {
    type: Boolean,
    default: true
  },
  showTools: {
    type: Boolean,
    default: true
  },
  primaryName: {
    type: String,
    default: 'id'
  },
  // 默认参数
  defaultParams: {
    type: Object,
    default: () => {
      return {}
    }
  },
  // 删除确认提示语
  destroyConfirm: {
    type: String,
    default: '确定删除吗'
  },
  // 权限标识
  permissionMark: {
    type: String,
    default: ''
  },
  dialogWidth: {
    type: String,
    default: ''
  },
  dialogHeight: {
    type: String,
    default: ''
  },
  exports: {
    type: Boolean,
    default: false
  },
  exportUrl: {
    type: String,
    default: ''
  },
  searchable: {
    type: Boolean,
    default: true
  }
})

// 显示分页
const showPaginate = () => {
  if (props.rowKey) {
    return false
  }

  return props.pagination
}

// 获取默认参数
const getDefulatParams = () => {
  return props.defaultParams
}

const row = ref()
// search 对象
const csearchRef = ref()
// 搜索
const searchFields = ref<Array<any>>(props.searchForm)
// eslint-disable-next-line vue/no-dupe-keys
const { limit, page, total, changeLimit, searchParams, changePage, doSearch, reset, loading, data, getTableData } = useSearch(props.api, showPaginate(), props.limit, props.page, getDefulatParams())
// 多选 ID
const multiSelectIds = ref<Array<string | number>>([])
// 获取 table data
const tableData = computed(() => data.value?.data)
// 表格 columns
const tableColumns = ref<Array<Column>>([])
// 初始化 columns
const initColumns = () => {
  props.columns.forEach(c => {
    // 默认显示
    c.show = isBoolean(c.show) ? c.show : true
    // 更新操作和删除操作
    if (c.type === 'operate') {
      c.update = isBoolean(c.update) ? c.update : true
      c.destroy = isBoolean(c.destroy) ? c.destroy : true
    }

    tableColumns.value.push(reactive(c))
  })
}

// excel 导出
const { download } = useExcelDownload()
// 排序
const customSortChange = (column: any, prop: string, order: string) => {
  if (props.sortChange) {
    return props.sortChange(column, prop, order)
  } else {
    // 排序
    let sort = 'desc'
    if (column.order === 'ascending') {
      sort = 'asc'
    }
    searchParams.value = Object.assign(searchParams.value, { sortField: column.prop, order: sort })
    // 排序之后重新请求
    getTableData()
  }
}

// dialog
const visible = ref<boolean>(false)
const title = ref<string>()
const openDialog = (v = null, dialogTitle: string = '') => {
  row.value = v

  visible.value = true
  // @ts-ignore
  title.value = dialogTitle || (v?.id ? '更新' : '创建')
}
const closeDialog = () => {
  visible.value = false
  reset()
}
// 批量选择
const multiSelect = (rows: Array<any>) => {
  multiSelectIds.value = []
  rows.forEach(item => {
    multiSelectIds.value.push(item.id)
  })
}
// 获取批量选择的 IDs
const getMultiSelectIds = () => {
  return multiSelectIds.value
}

const tableSize = ref<string>(props.size)
// 设置 table 的size
const setTableSize = (command: string | number | object) => {
  tableSize.value = command as string
}
const showSearch = ref<boolean>(props.searchable)

// 删除
const { destroy, deleted } = useDestroy(props.destroyConfirm)
// onMounted
onMounted(() => {
  deleted(reset)
})

// 页面初始化
initColumns()
getTableData()

// 暴露外部的 delete
const del = (api: string, id: any) => {
  destroy(api, id)
  reset()
}

// 设置默认搜索参数
const setDefaultParams = (params: Object) => {
  searchParams.value = Object.assign(searchParams.value, params)
  searchParams.value = Object.assign(searchParams.value, getDefulatParams())
}

// 曝露方法
defineExpose({ doSearch, openDialog, del, closeDialog, reset, setDefaultParams, getMultiSelectIds })
// 注入
provide('closeDialog', () => {
  closeDialog()
})
provide('refresh', () => {
  doSearch()
})
</script>
<style scoped scss>
:deep(.tools .el-button) {
  background-color: var(--el-fill-color-light) !important;
}

:deep(.catchtable .el-table__header .el-table__cell) {
  background: var(--catch-table-header-bg-color) !important;
}
</style>
