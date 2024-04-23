<template>
  <div class="form-item-inline">
    <div :class="InlineStyle">
      <FormRender :formItems="children" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import FormRender from '/admin/components/catchForm/FormRender.vue'

const thisProps = defineProps({
  name: String,
  props: Object,
  children: Array,
  class: String
})
const InlineStyle = computed(() => thisProps.props.class)
</script>
