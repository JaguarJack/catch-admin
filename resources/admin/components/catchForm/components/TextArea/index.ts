import { ElInput } from 'element-plus'
import { h } from 'vue'

export default {
  name: 'textarea',
  component: h(ElInput, { type: 'textarea', showWordLimit: true, autocomplete: 'off' }),
  type: 'basic'
}
