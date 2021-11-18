
<template>
  <div class="app-container">
  <el-form ref="form" :model="queryParam" :inline="true">
      <el-form-item prop="rule" label="rule" :label-width="formLabelWidth">
        <el-input v-model="queryParam.rule" placeholder="rule" type="input"></el-input>
      </el-form-item><el-form-item prop="route" label="route" :label-width="formLabelWidth">
        <el-input v-model="queryParam.route" placeholder="route" type="input"></el-input>
      </el-form-item><el-form-item prop="method" label="method" :label-width="formLabelWidth">
        <el-input v-model="queryParam.method" placeholder="method" type="input"></el-input>
      </el-form-item><el-form-item prop="name" label="name" :label-width="formLabelWidth">
        <el-input v-model="queryParam.name" placeholder="name" type="input"></el-input>
      </el-form-item><el-form-item prop="domain" label="domain" :label-width="formLabelWidth">
        <el-input v-model="queryParam.domain" placeholder="domain" type="input"></el-input>
      </el-form-item><el-form-item prop="option" label="option" :label-width="formLabelWidth">
        <el-input v-model="queryParam.option" placeholder="option" type="input"></el-input>
      </el-form-item><el-form-item prop="pattern" label="pattern" :label-width="formLabelWidth">
        <el-input v-model="queryParam.pattern" placeholder="pattern" type="input"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button icon="el-icon-search" type="primary" @click="handleSearch">
          查询
        </el-button>
      </el-form-item>
    </el-form>
    <el-divider content-position="center"></el-divider>
    <div class="filter-container">
      <el-row>
        <el-col :span="12">
          <div class="grid-content">
            <el-button class="filter-item" icon="el-icon-refresh" @click="handleRefresh">刷新</el-button>
           <!-- <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreate()">添加</el-button> -->
            <el-button type="primary"  class="filter-item" icon="el-icon-refresh" @click="sync">
              同步至数据库
            </el-button>
            <el-button v-if="this.selectedIds.length" size="small" class="filter-item mb-5" type="danger" icon="el-icon-delete" @click="handleMultiDelete">批量删除</el-button>
            <el-button @click="clearFilter">清除所有过滤器</el-button>
          </div>
        </el-col>
        <el-col :span="12">
          <el-button icon="el-icon-info" circle @click="templateVersion" style="float: right; padding: 3px 0"></el-button>
          <el-dropdown @command="handleTableCommand" style="float: right; padding: 3px 0">
            <span class="el-dropdown-link">
               <i class="el-icon-more el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item command="a">配置表格</el-dropdown-item>
              <el-dropdown-item command="b" divided>移除</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
          <div class="grid-content">
            <el-dropdown @command="handleDropdownCommand" style="float: right; padding: 3px 0">
              <span class="el-dropdown-link">
               <i class="el-icon-menu el-icon--right"></i>
              </span>
              <el-dropdown-menu slot="dropdown">
                <el-checkbox-group v-model="checkList">
                  <el-dropdown-item command="a"><el-checkbox label="rule"></el-checkbox></el-dropdown-item><el-dropdown-item command="a"><el-checkbox label="route"></el-checkbox></el-dropdown-item><el-dropdown-item command="a"><el-checkbox label="method"></el-checkbox></el-dropdown-item><el-dropdown-item command="a"><el-checkbox label="name"></el-checkbox></el-dropdown-item><el-dropdown-item command="a"><el-checkbox label="domain"></el-checkbox></el-dropdown-item><el-dropdown-item command="a"><el-checkbox label="option"></el-checkbox></el-dropdown-item><el-dropdown-item command="a"><el-checkbox label="pattern"></el-checkbox></el-dropdown-item>
                </el-checkbox-group>
              </el-dropdown-menu>
            </el-dropdown>
          </div>

        </el-col>
      </el-row>
    </div>

    <el-table ref="multipleTable" :data="data" tooltip-effect="dark" style="width: 100%" fit @selection-change="handleSelectMulti">
      <el-table-column type="selection" width="55" v-if="true"></el-table-column>
      <el-table-column prop="rule" label="rule" sortable="true" v-if="true"></el-table-column>
      <el-table-column prop="route" label="route" sortable="true" v-if="true"></el-table-column>
      <el-table-column prop="method" label="method" sortable="true" v-if="true"></el-table-column>
      <el-table-column prop="name" label="name" sortable="true" v-if="true"></el-table-column>
      <el-table-column prop="domain" label="domain" sortable="true" v-if="true"></el-table-column>
      <el-table-column prop="option" label="option" sortable="true" v-if="true"></el-table-column>
      <el-table-column prop="pattern" label="pattern" sortable="true" v-if="true"></el-table-column>
      <el-table-column prop="creator" label="创建人" v-if="true"></el-table-column>
      <el-table-column prop="created_at" label="创建时间" v-if="true"></el-table-column>
      <el-table-column prop="updated_at" label="更新时间" v-if="true"></el-table-column>
      <el-table-column label="操作" v-if="true" fixed="right">
        <template slot-scope="module">
          <el-button type="primary" icon="el-icon-stopwatch" @click="testApi(module.row.name)">API测试</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="paginate.current" hide-on-single-page :page-sizes="paginate.sizes" :page-size="paginate.limit" :layout="paginate.layout" :total="paginate.total"></el-pagination>
    <!----------------------------------- 新增/编辑 ---------------------------------------------->
    <el-drawer ref="drawer" size="60%" :title="drawerTitle" :visible.sync="formVisible" :before-close="handleClose" direction="rtl" @close="handleCancel">
      <div class="demo-drawer__content">
      <el-form :ref="formName" :model="formFieldsData" :rules="rules">
        <el-form-item prop="rule" label="rule" :label-width="formLabelWidth">
          <el-input v-model="formFieldsData.rule" placeholder="rule" autocomplete="off" clearable type="input"></el-input>
        </el-form-item><el-form-item prop="route" label="route" :label-width="formLabelWidth">
          <el-input v-model="formFieldsData.route" placeholder="route" autocomplete="off" clearable type="input"></el-input>
        </el-form-item><el-form-item prop="method" label="method" :label-width="formLabelWidth">
          <el-input v-model="formFieldsData.method" placeholder="method" autocomplete="off" clearable type="input"></el-input>
        </el-form-item><el-form-item prop="name" label="name" :label-width="formLabelWidth">
          <el-input v-model="formFieldsData.name" placeholder="name" autocomplete="off" clearable type="input"></el-input>
        </el-form-item><el-form-item prop="domain" label="domain" :label-width="formLabelWidth">
          <el-input v-model="formFieldsData.domain" placeholder="domain" autocomplete="off" clearable type="input"></el-input>
        </el-form-item><el-form-item prop="option" label="option" :label-width="formLabelWidth">
          <el-input v-model="formFieldsData.option" placeholder="option" autocomplete="off" clearable type="input"></el-input>
        </el-form-item><el-form-item prop="pattern" label="pattern" :label-width="formLabelWidth">
          <el-input v-model="formFieldsData.pattern" placeholder="pattern" autocomplete="off" clearable type="input"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="drawer__footer">
        <el-button @click="handleCancel">取 消</el-button>
	<el-button type="primary" @click="handleSubmit" :loading="loading">{{ loading ? '提交中 ...' : '确 定' }}</el-button>
      </div>
      </div>
    </el-drawer>
  </div>
</template>
<script>
  import formOperate from '@/layout/mixin/formOperate'

  export default {
    name:'apimanager_routeList',
    mixins: [formOperate],
    data() {
      return {
        url: 'routeList',
        formName: 'route_list',
        formLabelWidth: '120px',
        // 用户搜索
        queryParam: {
          rule:'',route:'',method:'',name:'',domain:'',option:'',pattern:'',
        },
        formVisible: false,
        formFieldsData: {
          rule:'',route:'',method:'',name:'',domain:'',option:'',pattern:'',
        },
        loading: false,
        checkList: [],
        search: '',
	drawerTitle: 'route_list',
        form: {rule:'',route:'',method:'',name:'',domain:'',option:'',pattern:'', },
        timer: null,
        // 表单验证
        rules: {
        }
      }
    },
      mounted() {},
      methods: {
        testApi(name) {
          let api_name = name.replace(/\\/g,"\\\\")   //数据库存了含反斜杠的字段查询时转义成四个反斜杠
          this.$router.push({ path: "/apitester", query: { api_name} });
        },
        sync() {
          this.$http.post('apimanager/routelist/sync').then(res => {
            this.$message.success(res.message)
            this.handleRefresh()
          })
        },
      },
  }
</script>
