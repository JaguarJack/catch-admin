<template>
    <el-tree
        ref="tree"
        :data="data"
        :node-key="valueKey"
        :class="class"
        :props="{ label }"
        @check="selectPermissions"
        @node-click="nodeClick"
    />
</template>

<script setup lang="ts">
import { ref, nextTick, onMounted, watch, inject } from 'vue'
import { isArray } from 'lodash'
import { $global} from '/admin/components/catchForm/config/symbol'

const tree = ref()
const  modelValue = defineModel()
const currentCheckedKeys = ref<Array<number>|Array<string>|string|number>([])
const data = ref<any>([])
const { http } = inject($global)

const props = defineProps({
  options: {
    type: Array,
    default: () => []
  },
  mode: {
    type: String,
    default: 'static'
  },
  label: {
    type: String,
    default: 'label'
  },
  valueKey: {
    type: String,
    default: 'id'
  },
  class: {
    type: String,
    default: 'w-full'
  },
  api: {
      type: String,
      default: null
  },
})

if (props.api) {
    http.get(props.api).then((r:any) => {
        data.value = r.data.data
    })
} else {
    data.value = props.options
}

// 设置已选权限
const selectPermissions = (checkedNodes: any, checkedKeys: any) => {
    currentCheckedKeys.value = checkedKeys.checkedKeys.concat(checkedKeys.halfCheckedKeys).sort()
    tree.value.setCheckedKeys(checkedKeys.checkedKeys)
}

onMounted(() => {
    nextTick(() => {
        if (tree.value) {
            if (isArray(modelValue.value)) {
                modelValue.value.forEach(id => {
                    tree.value.setChecked(id, true, false)
                })
            } else {
                tree.value.setCurrentKey(modelValue.value, true, false);
            }
        }
    });
});

const nodeClick = (node:any) => {
    currentCheckedKeys.value = node.id
}
// 监听选中的 checked
watch(() => currentCheckedKeys.value, (newValue) => {
    modelValue.value = newValue
}, {deep: true})
</script>
