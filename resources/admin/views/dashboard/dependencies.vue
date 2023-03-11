<template>
  <div class="flex flex-col bg-white dark:bg-regal-dark pl-5 pr-5 rounded">
    <span class="text-lg mt-5">项目依赖</span>
    <div class="flex mt-3">
      <el-table :data="dependencies" border width="200">
        <el-table-column prop="dependency" label="Dependency" />
        <el-table-column prop="version" label="Version" />
      </el-table>
      <el-table :data="devDependencies" border>
        <el-table-column prop="devDependency" label="DevDependency" />
        <el-table-column prop="version" label="Version" />
      </el-table>
    </div>
  </div>
</template>
<script lang="ts" setup>
import packages from '../../../../package.json'
import { computed } from 'vue'

const dependencies = computed(() => {
  const _dependencies = []
  for (const dependency in packages.dependencies) {
    _dependencies.push(Object.assign({ dependency, version: (packages.dependencies as any)[dependency] }))
  }

  return _dependencies
})
const devDependencies = computed(() => {
  const _devDependencies = []
  for (const devDependency in packages.devDependencies) {
    _devDependencies.push(Object.assign({ devDependency, version: (packages.devDependencies as any)[devDependency] }))
  }

  return _devDependencies
})
</script>
