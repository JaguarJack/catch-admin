<template>
  <div>
    <el-table :data="structures" class="draggable">
      <el-table-column prop="field" :label="$t('generate.schema.structure.field_name.name')" />
      <el-table-column prop="type" :label="$t('generate.schema.structure.type.name')">
        <template #default="scope">
          <el-tag type="success">{{ scope.row.type }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="nullable" :label="$t('generate.schema.structure.nullable')" width="90px">
        <template #default="scope">
          <el-tag v-if="scope.row.nullable">{{ $t('system.yes') }}</el-tag>
          <el-tag v-else type="info">{{ $t('system.no') }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="default" :label="$t('generate.schema.structure.default')" />
      <!--<el-table-column prop="comment" label="注释" />-->
      <el-table-column prop="id" :label="$t('generate.schema.structure.operate')" width="120px">
        <template #default="scope">
          <el-button type="primary" :icon="Edit" @click="updateField(scope.row.id)" size="small" />
          <el-button type="danger" :icon="Delete" @click="deleteField(scope.row.id)" size="small" />
        </template>
      </el-table-column>
    </el-table>

    <div class="flex justify-end mt-4">
      <el-button type="success" :icon="Plus" @click="addField">{{ $t('system.add') }}</el-button>
    </div>

    <div class="w-full sm:w-96 justify-between mx-auto pl-24 mt-2">
      <el-button class="mt-5" @click="emits('prev')">{{ $t('system.prev') }}</el-button>
      <el-button class="mt-5" type="primary" @click="next">{{ $t('system.confirm') }}</el-button>
    </div>

    <Dialog v-model="visible" :title="$t('system.add')">
      <el-form :model="structure" status-icon label-width="120px" ref="form">
        <el-form-item
          :label="$t('generate.schema.structure.field_name.name')"
          prop="field"
          :rules="[
            {
              required: true,
              message: $t('generate.schema.structure.field_name.verify'),
            },
          ]"
        >
          <el-input v-model="structure.field" />
        </el-form-item>
        <div class="flex justify-between">
          <el-form-item
            class="w-full"
            :label="$t('generate.schema.structure.type.name')"
            prop="type"
            :rules="[
              {
                required: true,
                message: $t('generate.schema.structure.type.verify'),
              },
            ]"
          >
            <el-select v-model="structure.type" :placeholder="$t('generate.schema.structure.type.placeholder')" filterable class="w-full">
              <el-option v-for="item in types" :key="item" :label="item" :value="item" />
            </el-select>
          </el-form-item>
        </div>
        <el-form-item :label="$t('generate.schema.structure.length')" prop="length">
          <el-input-number v-model="structure.length" :min="0" />
        </el-form-item>
        <div class="flex justify-between">
          <el-form-item label="nullable" prop="nullable">
            <el-switch v-model="structure.nullable" inline-prompt :active-text="$t('system.yes')" :inactive-text="$t('system.no')" />
          </el-form-item>
          <el-form-item :label="$t('generate.schema.structure.default')" prop="default" v-if="!structure.nullable">
            <el-input v-model="structure.default" />
          </el-form-item>
        </div>
        <el-form-item :label="$t('generate.schema.structure.unique')" prop="unique">
          <el-switch v-model="structure.unique" inline-prompt :active-text="$t('system.yes')" :inactive-text="$t('system.no')" />
        </el-form-item>
        <el-form-item :label="$t('generate.schema.structure.comment')" prop="comment">
          <el-input v-model="structure.comment" text />
        </el-form-item>
        <div class="flex justify-end">
          <el-button type="primary" @click="submitStructure(form)">{{ $t('system.confirm') }}</el-button>
        </div>
      </el-form>
    </Dialog>
  </div>
</template>
<script lang="ts" setup>
import { computed, Ref, ref } from 'vue'
import { useSchemaStore, Structure } from '../store'
import { Delete, Plus, Edit } from '@element-plus/icons-vue'
import type { FormInstance } from 'element-plus'
import Message from '/admin/support/message'
import http from '/admin/support/http'
import { Code } from '/admin/enum/app'

const schemaStore = useSchemaStore()
const emits = defineEmits(['prev', 'next'])
const visible = ref(false)

const structures = computed(() => {
  return schemaStore.getStructures
})

const structure: Ref<Structure> = ref(schemaStore.initStructure())
// structure
const addField = async () => {
  await form.value?.clearValidate()
  visible.value = true
}
const updateField = (id: number) => {
  visible.value = true
  schemaStore.getStructures.forEach(s => {
    if (s.id === id) {
      structure.value = s
    }
  })
}

const form = ref<FormInstance>()
const submitStructure = (formEl: FormInstance | undefined) => {
  if (!formEl) return
  formEl.validate(valid => {
    if (valid) {
      visible.value = !visible.value
      schemaStore.addStructure(structure.value)
      structure.value = schemaStore.initStructure()
    } else {
      return false
    }
  })
}

const deleteField = (id: number) => {
  schemaStore.filterStructures(id)
}

const next = () => {
  if (schemaStore.getStructures.length < 1) {
    Message.error('请先填写表结构数据')
  } else {
    http.post('schema', schemaStore.$state).then(r => {
      if (r.data.code == Code.SUCCESS) {
        Message.success('创建成功')
        schemaStore.finished()
      }
    })
  }
}

const types: string[] = [
  'id',
  'smallIncrements',
  'mediumIncrements',
  'increments',
  'smallInteger',
  'integer',
  'bigIncrements',
  'bigInteger',
  'mediumInteger',
  'unsignedInteger',
  'unsignedMediumInteger',
  'unsignedSmallInteger',
  'unsignedTinyInteger',
  'string',
  'text',
  'binary',
  'boolean',
  'char',
  'dateTimeTz',
  'dateTime',
  'date',
  'decimal',
  'double',
  'float',
  'geometryCollection',
  'geometry',
  'ipAddress',
  'json',
  'jsonb',
  'lineString',
  'longText',
  'macAddress',
  'mediumText',
  'multiLineString',
  'multiPoint',
  'multiPolygon',
  'nullableMorphs',
  'nullableTimestamps',
  'nullableUuidMorphs',
  'point',
  'polygon',
  'timeTz',
  'time',
  'timestampTz',
  'timestamp',
  'timestampsTz',
  'timestamps',
  'tinyIncrements',
  'tinyInteger',
  'tinyText',
  'unsignedDecimal',
  'uuid',
  'year',
]
</script>
