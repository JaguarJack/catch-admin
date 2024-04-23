import apiAttr from './apiAttr'

export default [
  {
    component: 'Divider',
    props: {
      title: '选项设置',
      contentPosition: 'center'
    },
    designKey: 'design-gSnX',
    name: 'form-xDEe',
    style: {
      marginTop: '40px'
    }
  },
  {
    label: '标签key',
    component: 'Input',
    name: 'props.labelKey',
    designKey: 'form-X6hs'
  },
  {
    label: '值Key',
    component: 'Input',
    name: 'props.valueKey',
    designKey: 'form-STkl'
  },
  {
    label: '数据模式',
    component: 'Radio',
    name: 'props.mode',
    props: {
      mode: 'static',
      options: [
        {
          label: '静态',
          value: 'static'
        },
        {
          label: '远程',
          value: 'remote'
        }
      ],
      optionType: 'button',
      space: 0
    },
    designKey: 'form-PLpj'
  },
  {
    label: '静态选项',
    name: 'props.options',
    component: 'FormList',
    hidden: '{{$values.props.mode!=="static"}}',
    children: [
      {
        label: '选项名',
        name: '{{$values.props.labelKey}}',
        component: 'Input',
        props: {
          placeholder: '请输入...'
        },
        designKey: 'form-LnGh'
        // initialValue: "{{ '选项' + ($index+1) }}"
      },
      {
        label: '选项值',
        name: '{{$values.props.valueKey}}',
        component: 'Input',
        props: {},
        designKey: 'form-HYtW'
        // initialValue: "{{ 'value' + ($index+1) }}"
      }
    ],
    designKey: 'form-Iwpd',
    props: {
      mode: 'table',
      newItemDefaults:
        '{{ (index) => ({ [$values.props.labelKey]: `选项${index + 1}`, [$values.props.valueKey]: `value${index + 1}` }) }}'
    }
  },
  {
    component: 'Card',
    props: {
      // header: '远程数据'
    },
    designKey: 'id-pGeN',
    name: 'form-6vzT',
    hidden: '{{$values.props.mode==="static"}}',
    children: apiAttr
  }
]
