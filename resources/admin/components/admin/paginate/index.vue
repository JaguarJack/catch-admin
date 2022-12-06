<template>
<div class="pt-2 pb-2 flex justify-end">
    <el-pagination
        background
        :layout="layout"
        :current-page="page"
        :page-size="limit"
        @current-change="changePage"
        @size-change="changeLimit"
        :total="total"
        :page-sizes="pageSizes"
    />
</div>
</template>

<script lang="ts" setup>

import {inject} from "vue";

const layout = 'total,sizes,prev, pager,next'

const pageSizes = [10, 20, 30, 50]

const {page, limit, total, changePage, changeLimit} = inject('paginate')
</script>

<style scoped>

</style>
