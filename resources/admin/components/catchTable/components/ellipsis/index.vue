<template>
  <div v-if="notNeedPopover()">
    {{ content }}
  </div>
  <el-popover v-else effect="dark" placement="top-start" :width="300" trigger="hover" :content="content">
    <template #reference>
      {{ ellipsis(length) }}
    </template>
  </el-popover>
</template>

<script setup lang="ts">
interface Prop {
  content?: string
  length: number
}

const props = defineProps<Prop>()

// need popover
const notNeedPopover = () => {
  const length = props.content ? props.content.length : 0
  return length <= 20
}

// 截取
const ellipsis = (length: number): string => {
  if (props.content) {
    return props.content.substring(0, length) + '...'
  }

  return ''
}
</script>
