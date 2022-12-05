<template>
  <component :is="icon" class="w-5 h-5" />
</template>

<script setup>
import { computed } from 'vue'
import * as heroIcons from '@heroicons/vue/24/outline'

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
})

const icon = computed(() => {
  let name = ''
  props.name.split('-').forEach(v => {
    name += v[0].toUpperCase() + v.substr(1)
  })
  return heroIcons[name + 'Icon']
})
</script>
