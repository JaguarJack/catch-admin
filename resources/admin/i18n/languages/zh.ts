const zh = {
  system: {
    name: 'CatchAdmin 管理系统',
    chinese: '中文',
    english: '英文',
    confirm: '确定',
    cancel: '取消',
    warning: '警告',
    next: '下一步',
    prev: '上一步',
    yes: '是',
    no: '否',
    add: '新增',
    edit: '编辑',
    finish: '完成',
    back: '返回',
    update: '更新',
  },

  login: {
    email: '邮箱',
    password: '密码',
    sign_in: '登录',
    welcome: '👏欢迎回来',
    lost_password: '忘记密码?',
    remember: '记住我',
    verify: {
      email: {
        required: '请先输入邮箱',
        invalid: '邮箱地址无效',
      },

      password: {
        required: '请先输入密码',
      },
    },
  },

  register: {
    sign_up: '注册',
  },
  generate: {
    schema: {
      title: '创建数据表',
      name: '表名称',
      name_verify: '请输入表名称',
      engine: {
        name: '表引擎',
        verify: '请选择表引擎',
        placeholder: '选择表引擎',
      },
      default_field: {
        name: '默认字段',
        created_at: '创建时间',
        updated_at: '更新时间',
        creator: '创建人',
        delete_at: '软删除',
      },
      comment: {
        name: '表注释',
        verify: '请填写表注释/说明',
      },

      structure: {
        title: '创建数据结构',
        field_name: {
          name: '字段名称',
          verify: '请填写字段名称',
        },
        length: '长度',
        type: {
          name: '类型',
          placeholder: '选择字段类型',
          verify: '请先选择字段类型',
        },
        form_label: '表单 Label',
        form_component: '表单组件',
        list: '列表',
        form: '表单',
        unique: '唯一',
        search: '查询',
        search_op: {
          name: '搜索操作符',
          placeholder: '选择搜索操作符',
        },
        nullable: 'nullable',
        default: '默认值',
        rules: {
          name: '验证规则',
          placeholder: '选择验证规则',
        },
        operate: '操作',
        comment: '字段注释',
      },
    },
    code: {
      title: '生成代码',
      module: {
        name: '模块',
        placeholder: '请选择模块',
        verify: '请选择模块',
      },
      controller: {
        name: '控制器',
        placeholder: '请输入控制器名称',
        verify: '请输入控制器名称',
      },
      model: {
        name: '模型',
        placeholder: '请输入模型名称',
        verify: '请输入模型名称',
      },
      paginate: '分页',
      menu: {
        name: '菜单名称',
        placeholder: '请输入菜单名称',
        verify: '请输入菜单名称',
      },
    },
  },

  module: {
    create: '创建模块',
    update: '更新模块',
    form: {
      name: {
        title: '模块名称',
        required: '请输入模块名称',
      },

      path: {
        title: '模块目录',
        required: '请输入模块目录',
      },

      desc: {
        title: '模块描述',
      },

      keywords: {
        title: '模块关键字',
      },

      dirs: {
        title: '默认目录',
        Controller: 'Controller 目录',
        Model: 'Model 目录',
        Database: 'Database 目录',
        Request: 'Request 目录',
      },
    },
  },
}

export default zh
