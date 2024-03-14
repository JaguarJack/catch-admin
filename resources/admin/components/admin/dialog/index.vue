<template>
  <div>
    <el-dialog :model-value="modelValue" :show-close="false" :fullscreen="isFullscreen" v-bind="$attrs" :width="width" :close="close" :before-close="beforeClose" draggable>
      <template #header="{ titleId, titleClass }">
        <div class="flex justify-between w-full">
          <div>
            <h4 :id="titleId" :class="titleClass">{{ title }}</h4>
          </div>
          <div class="flex w-12 justify-end">
            <!--<Icon :name="fullscreenIcon" @click="fullscreen" className="hover:cursor-pointer w-4 h-4" />-->
            <Icon name="x-mark" className="hover:cursor-pointer w-5 h-5" @click="close" />
          </div>
        </div>
      </template>
        <div class="pt-4">
            <slot />
        </div>
      <template #footer v-if="showFooter">
        <span class="dialog-footer">
          <el-button @click="close">{{ $t('system.cancel') }}</el-button>
          <el-button type="primary" @click="close">{{ $t('system.confirm') }}</el-button>
        </span>
      </template>
    </el-dialog>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
    require: true,
  },
  showFooter: {
    type: Boolean,
    default: false,
  },

  width: {
    type: String,
    required: false,
    default: '',
  },
  title: {
    type: String,
    default: '',
  },
})

const emits = defineEmits(['update:modelValue'])

const isFullscreen = ref(false)

const fullscreenIcon = computed(() => {
  return isFullscreen.value ? 'arrows-pointing-in' : 'arrows-pointing-out'
})
const fullscreen = () => {
  isFullscreen.value = !isFullscreen.value
}

const close = () => {
  emits('update:modelValue', false)
}

// 遮罩关闭调用
const beforeClose = () => {
  emits('update:modelValue', false)
}
const width = ref<string>('')

onMounted(() => {
  width.value = props.width ? props.width : getWidth()
})

// 窗口尺寸
const getWidth = () => {
  const clientWidth = window.document.body.clientWidth

  if (clientWidth <= 726) {
    return '100%'
  }

  if (clientWidth > 726 && clientWidth < 1440) {
    return '60%'
  }

  return '650px'
}
</script>

<style scoped lang="scss">
:deep(.el-dialog) {
  border-radius: 0.5rem;
  .el-dialog__header {
    margin-right: 0 !important;
    border-bottom: 1px solid #e2e8f0;
  }
}
</style>
