<template>
  <el-card class="box-card" shadow="never">
    <template #header>
      <div>
        <span>{{ $t('generate.code.title') }}</span>
      </div>
    </template>
    <div class="w-full sm:w-[40%] mx-auto">
      <el-form :model="gen" ref="form" label-width="100px">
        <el-form-item
          :label="$t('generate.code.module.name')"
          prop="module"
          :rules="[
            {
              required: true,
              message: $t('generate.code.module.verify'),
            },
          ]"
        >
          <Select v-model="gen.module" clearable :placeholder="$t('generate.code.module.placeholder')" api="modules" class="w-full" filterable />
        </el-form-item>
        <el-form-item
          :label="$t('generate.code.controller.name')"
          prop="controller"
          :rules="[
            {
              required: true,
              message: $t('generate.code.controller.verify'),
            },
          ]"
        >
          <el-input v-model="gen.controller" clearable :placeholder="$t('generate.code.controller.placeholder')" />
        </el-form-item>
        <el-form-item :label="$t('generate.code.model.name')" prop="model">
          <el-input v-model="gen.model" clearable :placeholder="$t('generate.code.model.placeholder')" />
        </el-form-item>

        <div class="flex">
          <el-form-item :label="$t('generate.code.paginate')" prop="paginate">
            <el-switch v-model="gen.paginate" inline-prompt :active-text="$t('system.yes')" :inactive-text="$t('system.no')" />
          </el-form-item>
          <el-form-item label-width="15px">
            <div class="text-sm text-gray-300">控制列表是否使用分页功能</div>
          </el-form-item>
        </div>
      </el-form>
    </div>
    <Structure />
    <div class="w-full flex justify-center pt-5">
      <router-link to="/develop/schemas">
        <el-button>{{ $t('system.back') }}</el-button>
      </router-link>
      <el-button type="primary" @click="submitGenerate(form)" class="ml-5">{{ $t('system.finish') }}</el-button>
    </div>
  </el-card>
</template>
<script lang="ts" setup>
import { watch, onMounted, reactive, ref } from 'vue'
import { useGenerateStore } from './store'
import type { FormInstance } from 'element-plus'
import http from '/admin/support/http'
import Structure from './structure.vue'
import { useRouter } from 'vue-router'
const generateStore = useGenerateStore()
const gen = reactive(generateStore.getCodeGen)

const router = useRouter()

const schemaId = router.currentRoute.value.params.schema

onMounted(() => {
  if (!generateStore.getSchemaId) {
    generateStore.setSchemaId(schemaId)
    getSchema()
  } else {
    if (schemaId !== generateStore.getSchemaId) {
      generateStore.setSchemaId(schemaId)
      generateStore.resetStructures()
      getSchema()
    }
  }
})

const getSchema = () => {
  http.get('schema/' + schemaId).then(r => {
    gen.module = r.data.data.module
    gen.schema = r.data.data.name

    gen.model = r.data.data.name.replace(/\_(\w)/g, (value, letter) => {
      return letter.toUpperCase()
    })

    generateStore.initStructures(r.data.data.columns)
  })
}
const form = ref<FormInstance>()
const submitGenerate = (formEl: FormInstance | undefined) => {
  if (!formEl) return
  formEl.validate(valid => {
    if (valid) {
      http.post('generate', generateStore.$state).then(r => {})
      //emits('next')
      //generateStore.$reset()
    } else {
      return false
    }
  })
}
</script>
