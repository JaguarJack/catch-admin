<template>
  <div class="w-full sm:w-[90%] mx-auto">
    <el-form :model="schema" ref="form" label-width="80px">
      <el-form-item
        :label="$t('generate.code.module.name')"
        prop="module"
        :rules="[
          {
            // required: true,
            message: $t('generate.code.module.verify'),
          },
        ]"
      >
        <Select v-model="schema.module" clearable :placeholder="$t('generate.code.module.placeholder')" api="modules" class="w-full" filterable />
      </el-form-item>
      <el-form-item
        :label="$t('generate.schema.name')"
        prop="name"
        :rules="[
          {
            required: true,
            message: $t('generate.schema.name_verify'),
          },
        ]"
      >
        <el-input v-model="schema.name" clearable />
      </el-form-item>
      <el-form-item
        :label="$t('generate.schema.engine.name')"
        prop="engine"
        :rules="[
          {
            required: true,
            message: $t('generate.schema.engine.verify'),
          },
        ]"
      >
        <el-select class="w-full" v-model="schema.engine" :placeholder="$t('generate.schema.engine.placeholder')" clearable>
          <el-option v-for="engine in engines" :key="engine.value" :label="engine.label" :value="engine.value" />
        </el-select>
      </el-form-item>
      <el-form-item :label="$t('generate.schema.default_field.name')">
        <el-checkbox v-model="schema.created_at" :label="$t('generate.schema.default_field.created_at')" size="large" />
        <el-checkbox v-model="schema.updated_at" :label="$t('generate.schema.default_field.updated_at')" size="large" />
        <el-checkbox v-model="schema.creator_id" :label="$t('generate.schema.default_field.creator')" size="large" />
        <el-checkbox v-model="schema.deleted_at" :label="$t('generate.schema.default_field.delete_at')" size="large" />
      </el-form-item>
      <el-form-item
        :label="$t('generate.schema.comment.name')"
        prop="comment"
        :rules="[
          {
            required: true,
            message: $t('generate.schema.comment.verify'),
          },
        ]"
      >
        <el-input v-model="schema.comment" type="textarea" />
      </el-form-item>
    </el-form>

    <div class="w-full sm:w-96 justify-between mx-auto pl-24 mt-4">
      <el-button class="mt-5" @click="$emit('prev')">{{ $t('system.prev') }}</el-button>
      <el-button class="mt-5" type="primary" @click="submitCreateTable(form)">{{ $t('system.next') }}</el-button>
    </div>
  </div>
</template>
<script lang="ts" setup>
import { reactive, computed, ref, unref } from 'vue'
import { useSchemaStore } from '../store'
import type { FormInstance } from 'element-plus'

const schemaStore = useSchemaStore()
schemaStore.start()

const emits = defineEmits(['prev', 'next'])

const schema = ref(schemaStore.getSchema)
const form = ref<FormInstance>()
const submitCreateTable = (formEl: FormInstance | undefined) => {
  if (!formEl) return
  formEl.validate(valid => {
    if (valid) {
      emits('next')
      schemaStore.setSchema(unref(schema))
    } else {
      return false
    }
  })
}
const engines = computed(() => {
  return [
    {
      value: 'InnoDB',
      label: 'InnoDB',
    },
    {
      value: 'MyISAM',
      label: 'MyISAM',
    },
    {
      value: 'Memory',
      label: 'Memory',
    },
  ]
})
</script>
