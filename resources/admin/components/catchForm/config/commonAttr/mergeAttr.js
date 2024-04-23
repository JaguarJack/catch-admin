const mergeAttr = (attrConfig) => {
  const { basic = [], high = [], linkage = [] } = attrConfig

  const attrs = [
    {
      component: 'Collapse',
      name: 'mergeAttr',
      children: [
        {
          title: '常用属性',
          name: 'basic',
          checked: true,
          children: basic
        },
        {
          title: '高级属性',
          name: 'high',
          children: high
        },
        {
          title: '联动规则',
          name: 'linkage',
          children: linkage
        }
      ]
    }
  ]

  return attrs
}

export default mergeAttr
