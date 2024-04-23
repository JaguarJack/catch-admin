<template>
  <component :is="type" :class="class" :style="{ fontStyle: italic ? 'italic' : 'normal' }">{{
    title
  }}</component>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    default: '这是一个标题'
  },
  type: {
    type: String,
    default: 'h3'
  },
  class: {
    type: String,
    default: ''
  },
  italic: Boolean
})
</script>
