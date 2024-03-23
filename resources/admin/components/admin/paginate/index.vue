<template>
  <div class="flex justify-end pt-5">
    <el-pagination background :layout="layout" :current-page="page" :page-size="limit" @current-change="changePage" @size-change="changeLimit" :total="parseInt(total)" :page-sizes="pageSizes" />
  </div>
</template>

<script lang="ts" setup>
import { inject } from 'vue'

const layout = 'total,sizes,prev, pager,next'

const pageSizes = [10, 20, 30, 50]

interface paginate {
  page: number
  limit: number
  total: string
  changePage: number
  changeLimit: number
}

const { page, limit, total, changePage, changeLimit } = inject('paginate') as paginate
</script>
