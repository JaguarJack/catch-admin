import { ElInput } from 'element-plus'
import { h } from 'vue'

export default {
  name: 'input',
  component: h(ElInput, { showWordLimit: true, autocomplete: 'off' }),
  type: 'basic'
}
