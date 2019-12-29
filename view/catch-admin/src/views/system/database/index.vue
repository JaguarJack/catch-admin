<template>
  <a-card :bordered="false">
    <div class="table-page-search-wrapper">
      <a-form layout="inline">
        <a-row :gutter="48">
          <a-col :md="4" :sm="24">
            <a-input allowClear v-model="queryParam.tablename" placeholder="请输入表名"/>
          </a-col>
          <a-col :md="4" :sm="24">
            <a-select allowClear v-model="queryParam.engine" placeholder="请选择引擎" default-value="0">
              <a-select-option value="MyISAM">MyISAM</a-select-option>
              <a-select-option value="InnoDB">InnoDB</a-select-option>
            </a-select>
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

    <div class="table-operator" v-if="selectTables.length > 0">
      <a-button type="primary" icon="safety" @click="optimizeTables()">优化</a-button>
      <a-button type="primary" icon="database" @click="backupTables">备份</a-button>
    </div>

    <s-table
      ref="table"
      size="default"
      rowKey="name"
      :bordered="true"
      :columns="columns"
      :data="loadData"
      :alert="options.alert"
      :rowSelection="options.rowSelection"
      showPagination="auto"
    >
      <span slot="action" slot-scope="text, record">
        <template>
          <a @click="$refs.tableModal.add(record.name)">查看</a>
        </template>
      </span>
    </s-table>
    <table-view ref="tableModal" @ok="handleOk" />
  </a-card>
</template>

<script>
import { STable } from '@/components'
import { getTables, optimize, backup } from '@/api/database'
import TableView from './table'

export default {
  name: 'Database',
  components: {
    STable,
    TableView
  },
  data () {
    return {
      // 查询参数
      queryParam: {},
      // 表头
      columns: [
        {
          title: '表名',
          dataIndex: 'name'
        },
        {
          title: '表引擎',
          dataIndex: 'engine'
        },
        {
          title: '字符集',
          dataIndex: 'collation'
        },
        {
          title: '数据行数',
          dataIndex: 'rows',
          sorter: true
        },
        {
          title: '索引大小',
          dataIndex: 'index_length',
          sorter: true
        },
        {
          title: '数据大小',
          dataIndex: 'data_length',
          sorter: true
        },
        {
          title: '表注释',
          dataIndex: 'comment',
          sorter: true
        },
        {
          title: '创建时间',
          dataIndex: 'create_time',
          sorter: true
        },
        {
          title: '操作',
          dataIndex: 'action',
          width: '70px',
          scopedSlots: { customRender: 'action' }
        }
      ],
      // 加载数据方法 必须为 Promise 对象
      loadData: parameter => {
        return getTables(Object.assign(parameter, this.queryParam))
          .then(res => {
            return res
          })
      },
      selectTables: [],
      // custom table alert & rowSelection
      options: {
        alert: { show: false, clear: () => { this.selectTables = [] } },
        rowSelection: {
          selectedRowKeys: this.selectTables,
          onChange: this.onSelectChange
        }
      }
    }
  },
  methods: {
    optimizeTables () {
      optimize({ data: this.selectTables }).then(res => {
        this.$notification['success']({
          message: res.message,
          duration: 4
        })
        this.selectTables = []
        this.selectedRowKeys = []
      })
    },
    backupTables () {
      backup({ data: this.selectTables }).then(res => {
        this.$notification['success']({
          message: res.message,
          duration: 4
        })
        this.selectTables = []
        this.selectedRowKeys = []
      })
    },
    handleOk () {
      this.$refs.table.refresh(true)
    },
    onSelectChange (selectedRowKeys, selectedRows) {
      this.selectTables = selectedRowKeys
    },
    resetSearchForm () {
      this.queryParam = {}
      this.handleOk()
    }
  }
}
</script>
