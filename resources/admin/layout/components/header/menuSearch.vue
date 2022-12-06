<template>
  <div class="w-10 h-10 grid place-items-center rounded-full mt-3 hover:cursor-pointer">
    <div class="flex flex-row w-96">
      <Icon name="magnifying-glass" class="hidden sm:block" @click="searchMenuVisiable = true" />

      <Teleport to="body">
        <el-dialog v-model="searchMenuVisiable" width="30%" draggable>
          <el-cascader :filterable="true" :options="options" @change="toWhere" placeholder="请输入菜单名称" clearable class="w-full" :show-all-levels="false" />
        </el-dialog>
      </Teleport>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { usePermissionsStore } from '/admin/stores/modules/user/permissions'
import { Menu } from '/admin/types/Menu'
import router from '/admin/router'
import { ref, computed } from 'vue'

const searchMenuVisiable = ref(false)

const permissionStore = usePermissionsStore()
const options = computed(() => {
  return filterMenus(permissionStore.getMenus)
})
const toWhere = (value: string[]) => {
  if (value.length) {
    router.push({ path: value[value.length - 1] })
  }

  searchMenuVisiable.value = false
}

/**
 * filter menus
 *
 * @param menus
 */
function filterMenus(menus: Menu[] | undefined): Object[] {
  const cascaderMenus: Object[] = []

  menus?.forEach(menu => {
    if (menu.meta === undefined) {
      const child = menu.children?.pop()
      cascaderMenus.push(Object.assign({ label: child?.meta?.title, value: child?.path }))
    } else {
      const cascaderMenu = Object.assign({ label: menu.meta?.title, value: menu.path, children: [] })

      cascaderMenu.children = filterMenus(menu.children)

      cascaderMenus.push(cascaderMenu)
    }
  })

  return cascaderMenus
}
</script>
