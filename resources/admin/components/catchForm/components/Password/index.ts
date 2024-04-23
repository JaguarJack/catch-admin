import { ElInput } from 'element-plus'
import { h } from 'vue'

export default {
  name: 'password',
  component: h(ElInput, { type: 'password', showWordLimit: true, autocomplete: 'off' }),
  type: 'basic'
}
