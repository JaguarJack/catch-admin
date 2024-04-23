export default [
  {
    component: 'Alert',
    props: {
      type: 'success',
      description: '对于配置级的联动，请直接点击下方按钮【编辑配置文本】，通过插值表达式实现',
      closable: true,
      'show-icon': true
    },
    designKey: 'design-MQPU',
    name: 'form-Oqi5'
  },
  {
    label: '值联动',
    help: '本字段值改变时，修改其他字段的值',
    name: 'change',
    component: 'FormList',
    children: [
      {
        label: '目标字段',
        name: 'target',
        component: 'Input',
        props: {}
      },
      {
        label: '值',
        name: 'value',
        component: 'Input',
        props: {}
      }
    ],
    props: {
      mode: 'card'
    }
  }
]
