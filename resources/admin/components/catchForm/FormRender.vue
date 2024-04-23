<!-- eslint-disable vue/no-multiple-template-root -->
<template>
  <form-item v-for="item in formItems" :key="item.name" v-bind="item" />
</template>

<script setup lang="ts">
import type { formItemsType } from '/admin/components/catchForm/config/commonType'
import FormItem from './FormItem.vue'

const props = defineProps<{
  formItems: formItemsType
}>()
</script>
