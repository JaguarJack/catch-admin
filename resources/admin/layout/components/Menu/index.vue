<script lang="ts">
import { h, defineComponent, VNode, toRaw } from 'vue'
import { usePermissionsStore } from '/admin/stores/modules/user/permissions'
import MenuItem from './item.vue'
import menus from './menus.vue'
import { useUserStore } from '/admin/stores/modules/user'
import { Menu } from '/admin/types/Menu'

/**
 * 递归渲染 Menu 节点
 */
function getVNodes(menus: Menu[] | undefined, _subMenuClass: string | undefined): VNode[] {
  const vnodes: VNode[] = []
  menus?.forEach(menu => {
    if (!menu.meta?.hidden) {
      let vnode: VNode
      const len = menu.children?.length
      if (len) {
        vnode = h(
          MenuItem,
          {
            subMenuClass: _subMenuClass,
            menu,
          },
          {
            default: () => getVNodes(menu.children, 'children-menu'),
          },
        )
      } else {
        vnode = h(MenuItem, {
          subMenuClass: _subMenuClass,
          menu,
        })
      }
      vnodes.push(vnode)
    }
  })

  return vnodes
}

/**
 * filter menus
 *
 * @param menus
 */
function filterMenus(menus: Menu[] | undefined): Menu[] {
  const newMenus: Menu[] = []

  menus?.forEach(m => {
    if (m.meta?.hidden) {
      return false
    }

    if (isHasOnlyChild(m) && m.children?.length) {
      newMenus.push(
        Object.assign({
          path: m.children[0].path,
          meta: m.children[0].meta,
          name: m.name,
        }),
      )
    } else {
      newMenus.push(m)
    }
  })

  return newMenus
}

/**
 * is has only child
 *
 * @param menu
 */
function isHasOnlyChild(menu: Menu): boolean {
  if (menu.children === undefined) {
    return true
  }

  if (menu.children.length > 1 || !menu.children.length) {
    return false
  }

  if (menu.children[0].children?.length) {
    return false
  }

  return true
}

export default defineComponent({
  props: {
    subMenuClass: {
      type: String,
      require: true,
    },
    menuClass: {
      type: String,
      require: true,
    },
  },
  setup(props, ctx) {
    const permissionsStore = usePermissionsStore()
    const userStore = useUserStore()

    // 后端的 permissions 返回 undefined，则认为该后端无权限系统
    const permissions = userStore.getPermissions === undefined ? [] : userStore.getPermissions

    console.log(permissionsStore.getMenusFrom(permissions))
    console.log(filterMenus(permissionsStore.getMenusFrom(permissions)))
    const vnodes = getVNodes(filterMenus(permissionsStore.getMenusFrom(permissions)), props.subMenuClass)
    return () => {
      return h(
        menus,
        {
          class: 'border-none side-menu ' + props.menuClass,
        },
        {
          default: () => vnodes,
        },
      )
    }
  },
})
</script>
