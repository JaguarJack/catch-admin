<template>
  <div class="pt-5 pl-2">
    <Add @click="show(showParams)" />
    <slot name="operate" />
  </div>
</template>

<script lang="ts" setup>
defineProps({
  show: {
    type: Function,
    required: true,
  },

  showParams: null,
})
</script>
