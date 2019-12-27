<template>
  <a-modal
    bordered
    title="表结构"
    :width="1200"
    rowKey="field"
    :visible="visible"
    @cancel="handleCancel"
  >
    <a-table
      :columns="columns"
      :dataSource="fields"
      :pagination="false"
    >
    </a-table>
  </a-modal>
</template>
<script>
import { read } from '@/api/database'

export default {
  data () {
    return {
      columns: [
        {
          title: '字段名称',
          dataIndex: 'field'
        },
        {
          title: '类型',
          dataIndex: 'type'
        },
        {
          title: '字符集',
          dataIndex: 'collation'
        },
        {
          title: 'Null',
          dataIndex: 'null'
        },
        {
          title: '索引',
          dataIndex: 'key'
        },
        {
          title: '默认值',
          dataIndex: 'default'
        },
        {
          title: '权限',
          dataIndex: 'privileges'
        },
        {
          title: '注释',
          dataIndex: 'comment'
        }
      ],
      labelCol: {
        xs: { span: 24 },
        sm: { span: 7 }
      },
      wrapperCol: {
        xs: { span: 24 },
        sm: { span: 13 }
      },
      visible: false,
      fields: []
    }
  },
  methods: {
    add (name) {
      this.visible = true
      read(name).then(res => {
        this.fields = res.data
        console.log(this.fields)
      })
    },
    handleCancel () {
      this.visible = false
    }
  }
}
</script>
