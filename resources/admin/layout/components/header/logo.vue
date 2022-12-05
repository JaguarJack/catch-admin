<template>
  <div class="h-16 flex items-center justify-center logo-bg">
    <img :src="logo" class="h-9 w-9" />
    <div class="text-md logo-text pl-3" v-if="store.isExpand">CatchAdmin</div>
  </div>
</template>

<script lang="ts" setup>
import { useAppStore } from '/admin/stores/modules/app'
import logo from '/admin/assets/logo.png'

const store = useAppStore()
</script>
<style scoped lang="scss">
.logo-bg {
  background-color: var(--header-logo-bg-color);
}

.logo-text {
  color: var(--header-logo-text-color);
}
</style>
