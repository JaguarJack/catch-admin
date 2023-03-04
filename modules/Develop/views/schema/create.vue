<template>
  <div>
    <Schema v-if="active === 1" @next="next" @prev="prev" />
    <Structure v-if="active === 2" @next="next" @prev="prev" />
  </div>
</template>
<script lang="ts" setup>
import { ref, watch } from 'vue'
import Schema from './steps/schema.vue'
import Structure from './steps/structure.vue'
import { useSchemaStore } from './store'

const schemaStore = useSchemaStore()

const active = ref(1)
const next = () => {
  if (active.value++ >= 2) {
    active.value = 2
  }
}
const prev = () => {
  if (active.value-- === 1) {
    active.value = 1
  }
}

const emit = defineEmits(['close'])
watch(
  () => schemaStore.getFinished,
  function (value) {
    if (value) {
      emit('close')
    }
  },
)
</script>
