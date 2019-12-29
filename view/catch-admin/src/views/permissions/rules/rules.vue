<template>
  <a-card :bordered="false">
    <div class="table-page-search-wrapper">
      <a-form layout="inline">
        <a-row :gutter="48">
          <a-col :md="4" :sm="24">
            <a-input allowClear v-model="queryParam.permission_name" placeholder="请输入菜单名名称"/>
          </a-col>
          <a-col :md="4" :sm="24">
            <span class="table-page-search-submitButtons">
              <a-button icon="search" type="primary" @click="$refs.table.refresh(true)">查询</a-button>
              <a-button icon="sync" style="margin-left: 8px" @click="resetSearchForm()">重置</a-button>
            </span>
          </a-col>
        </a-row>
      </a-form>
    </div>

    <div class="table-operator">
      <a-button type="primary" icon="plus" @click="$refs.permissionModal.add()">新建</a-button>
    </div>

    <s-table
      ref="table"
      size="default"
      rowKey="id"
      :bordered="true"
      :columns="columns"
      :data="loadData"
      :showPagination="false"
    >
      <span slot="action" slot-scope="text, record">
        <template>
          <a @click="handleEdit(record)">编辑</a>
          <a-divider type="vertical" />
          <a-dropdown>
            <a-menu slot="overlay">
              <a-menu-item><a @click="handleAddSon(record)">新增子菜单</a></a-menu-item>
              <a-menu-item><a @click="handleDel(record)">删除</a></a-menu-item>
            </a-menu>
            <a>更多<a-icon type="down"/></a>
          </a-dropdown>
        </template>
      </span>
    </s-table>
    <create-permission ref="permissionModal" @ok="handleOk" />
  </a-card>
</template>

<script>
import { STable } from '@/components'
import CreatePermission from './form/create'
import { getPermissionList, del } from '@/api/permission'

export default {
  name: 'Permissions',
  components: {
    STable,
    CreatePermission
  },
  data () {
    return {
      // 查询参数
      queryParam: {},
      // 表头
      columns: [
        {
          title: '菜单名称',
          dataIndex: 'permission_name'
        },
        {
          title: '菜单路由',
          dataIndex: 'route'
        },
        {
          title: '菜单标识',
          dataIndex: 'permission_mark'
        },
        {
          title: '请求方法',
          dataIndex: 'method'
        },
        {
          title: '类型',
          dataIndex: 'type',
          customRender: this.renderType
        },
        {
          title: '创建时间',
          dataIndex: 'created_at',
          sorter: true
        },
        {
          title: '更新时间',
          dataIndex: 'updated_at',
          sorter: true
        },
        {
          title: '操作',
          dataIndex: 'action',
          width: '150px',
          scopedSlots: { customRender: 'action' }
        }
      ],
      // 加载数据方法 必须为 Promise 对象
      loadData: parameter => {
        return getPermissionList(Object.assign(parameter, this.queryParam))
          .then(res => {
            return res
          })
      }
    }
  },
  methods: {
    handleEdit (record) {
      this.$refs.permissionModal.edit(record)
    },
    handleAddSon (record) {
      this.$refs.permissionModal.addSon(record)
    },
    handleDel (record) {
      this.$confirm({
        title: '确定删除' + record.username + '吗?',
        okText: '确定',
        okType: 'danger',
        cancelText: '取消',
        onOk: () => {
          del(record.id).then((res) => {
            this.$notification['success']({
              message: res.message,
              duration: 4
            })
            this.handleOk()
          }).catch(err => this.failed(err))
        },
        onCancel () {}
      })
    },
    handleOk () {
      this.$refs.table.refresh(true)
    },
    failed (errors) {
      this.$notification['error']({
        message: errors.message,
        duration: 4
      })
      this.handleCancel()
    },
    resetSearchForm () {
      this.queryParam = {}
      this.handleOk()
    },
    renderType (value, row, index) {
      return value === 1 ? <a-button type="normal" size="small">菜单</a-button> : <a-button type="danger" size="small">按钮</a-button>
    }
  }
}
</script>
