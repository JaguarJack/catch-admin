<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="queryParam.env_name" placeholder="环境名称" clearable class="filter-item form-search-input" />
      <el-button class="filter-item search" icon="el-icon-search" @click="handleSearch">
        搜索
      </el-button>
      <el-button class="filter-item" icon="el-icon-refresh" @click="handleRefresh">
        重置
      </el-button>
      <el-button class="filter-item fr" type="primary" icon="el-icon-plus" @click="handleCreateEnv">
        新增
      </el-button>
    </div>
    <el-table ref="multipleTable" :data="data" tooltip-effect="dark" style="width: 100%" border fit @selection-change="handleSelectMulti">
      <el-table-column type="selection" width="55" />
      <el-table-column prop="env_name"   label="环境名称" />
      <el-table-column prop="selected"   label="当前环境" />
      <el-table-column prop="creator" label="创建人" />
      <el-table-column prop="created_at" label="创建时间" />
      <el-table-column prop="updated_at" label="更新时间" />
      <el-table-column label="操作" fixed="right" width="300">
        <template slot-scope="module">
          <el-button type="primary" icon="el-icon-refresh" @click="selectAPIenv(module.row.id)" >切换</el-button>
          <el-button type="primary" icon="el-icon-edit" @click="handleUpdate(module.row)" />
          <el-button type="danger" icon="el-icon-delete" @click="handleDelete(module.row.id)" />
        </template>
      </el-table-column>
    </el-table>
    <el-pagination
            background
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
            :current-page="paginate.current"
            hide-on-single-page
            :page-sizes="paginate.sizes"
            :page-size="paginate.limit"
            :layout="paginate.layout"
            :total="paginate.total"/>
    <!----------------------------------- 编辑 ---------------------------------------------->
    <el-dialog :close-on-click-modal="false" :title="title" :visible.sync="formVisible" @close="handleCancel">
      <el-form label-position="top" :ref="formName" :model="formFieldsData" :rules="rules">
        <el-form-item label="env_name" :label-width="formLabelWidth" prop="env_name">
          <el-input v-model="formFieldsData.env_name" placeholder="请输入环境名称" autocomplete="off" clearable />
        </el-form-item>
       <!-- <el-form-item label="appid" :label-width="formLabelWidth" prop="appid">
          <el-input v-model="formFieldsData.appid" placeholder="请输入appid" autocomplete="off" clearable />
        </el-form-item>
        <el-form-item label="project_id" :label-width="formLabelWidth" prop="project_id">
          <el-input v-model="formFieldsData.project_id" placeholder="请输入project_id" autocomplete="off" clearable />
        </el-form-item> -->
        <el-form-item  label="env_json" :label-width="formLabelWidth" prop="env_json">
          <avue-crud
              ref="crudJSON"
              :option="tableOption"
              :data="jsonTableData"
              @row-update="addUpdateJSON"
              @row-del="rowDelJSON"
              @row-save="rowSaveJSON"
            >
              <template slot-scope="{ row, index }" slot="menu">
                <el-button
                  type="text"
                  size="small"
                  @click="rowCellJSON(row, index)"
                  >{{ row.$cellEdit ? "自定义保存" : "自定义修改" }}</el-button
                >
              </template>
          </avue-crud>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="handleCancel">取 消</el-button>
        <el-button type="primary" @click="handleSubmit">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
  import formOperate from '@/layout/mixin/formOperate'
  import { parseTime } from '@/utils'
  export default {
    name:'apimanager_apienv',
    mixins: [formOperate],
    data() {
      return {
        formName: 'apiEnv',
        formLabelWidth: '120px',
        // 刷新路由
        refreshRoute: true,
        // 用户搜索
        queryParam: {
          env_name: '',
        },
        formVisible: false,
        formFieldsData: {
          env_name: '',
          env_json: ''
        },
        url: 'apiTesterUserenv',
        // 表单验证
        rules: {
          env_name: [
            { required: true, message: '请输入环境名称' }
          ],
          env_json: [
            { required: true, message: '请输入环境变量' }
          ]
        },
        jsonTableData:[],
        tableOption: {
          refreshBtn:false,
          addBtn: false,
          editBtn: false,
          addRowBtn: true,
          cancelBtn: false,
          border: true,
          column: [
            {
              label: "Key",
              prop: "key",
              cell: true,
              rules: [
                {
                  required: true,
                  message: "Key值示例:{{KeyName}}",
                  trigger: "blur"
                }
              ]
            },
            {
              label: "Value",
              prop: "value",
              cell: true,
              rules: [
                {
                  required: true,
                  message: "请输入Value值",
                  trigger: "blur"
                }
              ]
            }
          ]
        },
      }
    },
    watch:{
      formFieldsData:{
        deep:true,
        handler(data){
          if(data.env_json){
           let obj =  this.JsonToObject(data.env_json)
           let arr = Object.entries(obj).map(item => {
            return { key: item[0], value: item[1], $cellEdit: false };
          });
          this.jsonTableData = arr;
         }
        }
      }
    },
    methods: {
      handleCreateEnv(){
        this.jsonTableData = []
      this.handleCreate()
      },
      selectAPIenv(id) {
        this.$http.get( 'apiTesterUserenv/selectAPIenv/' + id).then(response => {
          this.$message.success(response.message)
          this.handleRefresh()
        })
      },
            // ↓ 处理 ApiBaseInfo Json数据格式 返回 Object 格式 ↓
      JsonToObject(json) {
        if (json) {
          let flag = /\'/.test(json);
          if (flag) {
            return JSON.parse(json.replace(/\'/gi, '"'));
          } else {
            return JSON.parse(json);
          }
        } else {
          return null;
        }
      },
          // ↓ JSON 表格 行编辑 ↓
      rowCellJSON(row, index) {
        this.$refs.crudJSON.rowCell(row, index);
      },
        // ↓ JSON 表格 编辑行数据 ↓
      addUpdateJSON(form, index, done, loading) {
        loading();
        done();
      },
          // ↓ JSON 表格 保存行数据 ↓
      rowSaveJSON(form, done) {
        done();
        this.formFieldsData.env_json = this.handlerJson(this.jsonTableData);
      },
          // ↓ JSON 表格 删除行数据 ↓
      rowDelJSON(form, index, done) {
        this.jsonTableData.splice(index, 1);
        this.formFieldsData.env_json = this.handlerJson(this.jsonTableData);
      },
      handlerJson(arrData){
        let cache = {};
        arrData.forEach(item => {  
            cache[item.key] = item.value;
        });
        if(Object.keys(cache).length){
          return JSON.stringify(cache);
        }else{
          return null;
        }
      }
    }
  }
</script>
