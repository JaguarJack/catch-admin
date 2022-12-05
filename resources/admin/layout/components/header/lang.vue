<template>
  <div class="w-10 h-10 grid place-items-center rounded-full mt-3 hover:cursor-pointer">
    <div class="flex hover:cursor-pointer pl-1 pr-1">
      <el-dropdown size="large" class="flex items-center justify-center hover:cursor-pointer w-full" @command="selectLanguage">
        <Icon name="language" />
        <template #dropdown>
          <el-dropdown-menu>
            <el-dropdown-item v-for="lang in langs" :key="lang.value" :command="lang.value" :disabled="lang.value == defaultLang">
              {{ $t('system.' + lang.label) }}
            </el-dropdown-item>
          </el-dropdown-menu>
        </template>
      </el-dropdown>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { reactive, computed } from 'vue'
import { useAppStore } from '/admin/stores/modules/app'

const langs = reactive([
  { label: 'chinese', value: 'zh' },
  { label: 'english', value: 'en' },
])

const appStore = useAppStore()
// select default languages
const defaultLang = computed(() => {
  return appStore.getLocale
})

// select language
const selectLanguage = (value: 'zh' | 'en') => {
  appStore.changeLocale(value)
  location.reload()
}
</script>
