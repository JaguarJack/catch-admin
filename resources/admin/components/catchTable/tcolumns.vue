<template>
  <el-table-column :label="column.label" v-if="column.children">
    <tcolumns :column="child" v-for="(child, k) in column.children" :key="k" :api="api">
      <template v-for="slot in Object.keys($slots)" #[slot]="scope: Record<string, any>">
        <slot :name="slot" v-bind="scope" />
      </template>
    </tcolumns>
  </el-table-column>
  <el-table-column
    :type="column.type"
    :prop="column.prop"
    :aligh="column.align"
    :label="column.label"
    :width="column.width"
    :min-width="column['min-width']"
    :align="column.align"
    :sortable="column.sortable"
    :formatter="column.formatter"
    v-else
  >
    <!-- 自定义 column header 插槽 -->
    <template #header v-if="column.header">
      {{ column.width }}
      <slot :name="column.header" />
    </template>
    <!-- 插槽内容 -->
    <template #default="scope">
      <!-- 自定义 -->
      <slot :name="column.slot" v-bind="scope" v-if="column.slot" />
      <!-- default -->
      <slot :name="column.prop" v-bind="scope" v-if="column.prop && !column.slot">
        <!-- 长字段省略号显示 -->
        <span v-if="column.ellipsis">
          <Ellipsis :content="scope.row[column.prop]" :length="isBoolean(column.ellipsis) ? 20 : (column.ellipsis as number)" />
        </span>
        <!-- switch 字段切换-->
        <span v-else-if="column.switch && column.prop">
          <SwitchColumn :field="column.prop" :api="api" :id="scope.row.id" v-model="scope.row[column.prop]" :refresh="column.switchRefresh || refresh" />
        </span>
        <!-- 图片预览 -->
        <span v-else-if="column.image && column.prop">
          <div v-if="scope.row[column.prop]">
            <el-image
              width="100"
              class="preview_img"
              :src="getImage(column.filter ? column.filter(scope.row[column.prop]) : scope.row[column.prop])"
              :z-index="10000"
              style="width: 50px; height: 50px"
              v-if="!column.preview"
            >
              <template #error>
                <div class="image-slot">
                  <el-icon><icon-picture /></el-icon>
                </div>
              </template>
            </el-image>
            <el-image
              class="cursor-pointer preview_img"
              :src="getImage(column.filter ? column.filter(scope.row[column.prop]) : scope.row[column.prop])"
              :z-index="10000"
              :initial-index="0"
              style="width: 50px; height: 50px"
              :preview-teleported="true"
              :preview-src-list="column.filter ? column.filter(scope.row[column.prop]) : scope.row[column.prop]"
              v-else
            >
              <template #error>
                <div class="image-slot">
                  <el-icon><icon-picture /></el-icon>
                </div>
              </template>
            </el-image>
          </div>
        </span>
        <!-- 链接 -->
        <span v-else-if="column.link && column.prop">
          <a :href="column.filter ? column.filter(scope.row[column.prop]) : scope.row[column.prop]" target="_blank">
            <el-tag>{{ column.link_text ? column.link_text : '链接' }}</el-tag>
          </a>
        </span>
        <!-- 标签 -->
        <span v-else-if="column.tags && column.prop">
          <el-tag :type="getTagsType(column.tags, scope.row[column.prop])">{{ column.filter ? column.filter(scope.row[column.prop]) : scope.row[column.prop] }}</el-tag>
        </span>
        <!-- 普通字段 -->
        <span v-else>
          {{ column.filter ? column.filter(scope.row[column.prop]) : scope.row[column.prop] }}
        </span>
      </slot>
    </template>
  </el-table-column>
</template>
<script lang="ts" setup>
import { isBoolean, isUndefined } from '/admin/support/helper'
import { Column } from './ctable'
import Ellipsis from './components/ellipsis/index.vue'
import SwitchColumn from './components/switchColumn/index.vue'
import { Picture as IconPicture } from '@element-plus/icons-vue'

interface Props {
  column: Column
  api: string
}

defineProps<Props>()

const getImage = (src: string | Array<string>) => {
  if (isUndefined(src) || src === null) {
    return null
  }

  if (typeof src === 'string') {
    return src
  }
  return src[0]
}

const getTagsType = (tags: Array<number> | boolean, value: number) => {
  if (typeof tags === 'boolean') {
    return 'primary'
  }
  if (value) {
    const res = tags[value - 1]

    return res || 'primary'
  }

  return 'primary'
}

// switch 重新请求刷新页面
const emits = defineEmits(['refresh'])
const refresh = () => {
  emits('refresh')
}
</script>
<style scoped lang="scss">
.image-slot {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  background: var(--el-fill-color-light);
  color: var(--el-text-color-secondary);
  font-size: 30px;
}
.image-slot .el-icon {
  font-size: 30px;
}
</style>
