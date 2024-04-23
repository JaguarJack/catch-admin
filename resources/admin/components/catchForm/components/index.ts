import type { formElement } from '/admin/components/catchForm/config/commonType'

const modules = import.meta.glob('./*/index.ts', { eager: true })
const components: { [component: string]: formElement } = {}

for (const path in modules) {
  const data = (modules[path] as { default: formElement }).default
  if (data) {
    components[data.name] = data
  }
}
export default components
