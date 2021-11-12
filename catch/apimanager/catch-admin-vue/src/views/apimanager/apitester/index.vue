<template>
  <div class="app-container">
    <el-row :gutter="12">
      <el-col :span="6">
        <el-card shadow="never">
          <div slot="header" class="clearfix">
            <span>分类</span>
          </div>
          <div class="block">
            <el-tree
              :data="apicategory"
              :props="apicategoryProps"
              node-key="id"
              default-expand-all
              :expand-on-click-node="false"
              @node-click="getApicategoryData"
            />
          </div>
        </el-card>
      </el-col>
      <el-col :span="18">
        <div class="filter-container">
          <el-row>
            <el-input
              v-model="queryParam.api_title"
              placeholder="名称"
              clearable
              class="filter-item form-search-input"
            />
            <el-input
              v-model="queryParam.api_name"
              placeholder="标识"
              clearable
              class="filter-item form-search-input"
            />
            <el-select
              v-model="queryParam.type"
              clearable
              placeholder="请选择数据源类型"
              class="filter-item"
              style="margin-right: 5px"
            >
              <el-option value="1" label="remote" />
              <el-option value="2" label="local" />
            </el-select>
            <el-button
              class="filter-item fr"
              icon="el-icon-refresh"
              @click="handleRefresh"
            >
              重置
            </el-button>
            <el-button
              style="margin-right: 5px"
              class="filter-item fr search"
              icon="el-icon-search"
              @click="handleSearch"
            >
              搜索
            </el-button>
          </el-row>
          <el-row style="margin-top: 5px">
            <el-select
              class="filter-item "
              @change="changeUserenv"
              v-model="userenvid"
              placeholder="用户环境变量"
            >
              <el-option
                :value="env.id"
                :label="env.env_name"
                v-for="env in userenvs"
                :key="env.id"
              />
            </el-select>
            <el-button
              class="filter-item fr"
              type="primary"
              icon="el-icon-plus"
              @click="handleCreate"
            >
              新增
            </el-button>
          </el-row>
        </div>
        <el-button
          v-if="this.selectedIds.length"
          size="small"
          class="filter-item mb-5"
          type="danger"
          icon="el-icon-delete"
          @click="handleMultiDelete"
        >
          批量删除
        </el-button>
        <el-table
          ref="multipleTable"
          :data="data"
          tooltip-effect="dark"
          style="width: 100%"
          border
          fit
          @selection-change="handleSelectMulti"
        >
          <el-table-column
            type="selection"
            width="55"
            :selectable="selectInit"
          />
          <el-table-column label="名称">
            <template slot-scope="api">{{ api.row.api_title }}</template>
          </el-table-column>
          <el-table-column prop="methods" label="methods" />
          <el-table-column prop="api_name" label="标识" />
          <!--   <el-table-column prop="status" label="状态">
            <template slot-scope="api">
              <el-switch
                v-if="api.row.id === 0"
                v-model="api.row.status"
                disabled
                active-text="启用"
                :active-value="1"
              />
              <el-switch
                v-else
                v-model="api.row.status"
                active-text="启用"
                inactive-text="禁用"
                :active-value="1"
                :inactive-value="2"
                @change="disOrEnableUser(api.row)"
              />
            </template>
          </el-table-column> -->
          <el-table-column prop="type" label="数据源类型">
            <template slot-scope="api">
              <el-tag v-if="api.row.type === 1" type="success">remote</el-tag>
              <el-tag v-if="api.row.type === 2" type="danger">local</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="created_at" label="创建时间" />
          <el-table-column label="操作" fixed="right" width="300">
            <template slot-scope="api">
              <el-button
                type="primary"
                icon="el-icon-refresh"
                @click="testApi(api.row.id)"
                >测试</el-button
              >
              <el-button
                type="primary"
                icon="el-icon-edit"
                v-if="api.row.id === 0"
                disabled
              />
              <el-button
                type="primary"
                icon="el-icon-edit"
                v-else
                @click="beforeHandleUpdate(api.row)"
              />
              <el-button
                type="danger"
                icon="el-icon-edit"
                v-if="api.row.id === 0"
                disabled
              />
              <el-button
                type="danger"
                icon="el-icon-delete"
                v-else
                @click="handleDelete(api.row.id)"
              />
            </template>
          </el-table-column>
        </el-table>
        <el-pagination
          background
          class="pagination-container"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
          :current-page="paginate.current"
          hide-on-single-page
          :page-sizes="paginate.sizes"
          :page-size="paginate.limit"
          :layout="paginate.layout"
          :total="paginate.total"
        />
      </el-col>
    </el-row>
    <!----------------------------------- API ---------------------------------------------->
    <el-dialog
      :close-on-click-modal="false"
      :title="title"
      :visible.sync="formVisible"
      :destroy-on-close="true"
      @close="handleCancel()"
    >
      <el-form :ref="formName" :model="formFieldsData" :rules="rules">
        <el-row :gutter="12">
          <el-form-item
            label="分类"
            :label-width="formLabelWidth"
            prop="category_id"
          >
            <el-cascader
              v-model="formFieldsData.category_id"
              :options="treeCategory.data"
              :props="treeCategory.prop"
              :show-all-levels="false"
              style="width: 85%"
              clearable
            />
          </el-form-item>
          <el-form-item label="type" :label-width="formLabelWidth" prop="type">
            <el-select
              v-model="formFieldsData.type"
              style="width: 85%"
              placeholder="请选择数据源类型"
            >
              <el-option
                v-for="(item, key) in type"
                :key="key"
                :label="item"
                :value="key"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            label="名称"
            :label-width="formLabelWidth"
            prop="api_title"
          >
            <el-input
              v-model="formFieldsData.api_title"
              placeholder="请输入名称"
              autocomplete="off"
              clearable
            />
          </el-form-item>
          <el-form-item
            label="标识（路由name）"
            :label-width="formLabelWidth"
            prop="api_name"
          >
            <el-input
              v-model="formFieldsData.api_name"
              placeholder="请输入英文唯一标识（请与路由name字段一致）"
              autocomplete="off"
              clearable
            />
          </el-form-item>
          <el-form-item
            label="methods类型"
            :label-width="formLabelWidth"
            prop="methods"
          >
            <el-select
              v-model="formFieldsData.methods"
              placeholder="请选择methods类型"
            >
              <el-option
                v-for="(item, key) in methodsTypes"
                :key="key"
                :label="item"
                :value="key"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            label="api_url"
            :label-width="formLabelWidth"
            prop="api_url"
          >
            <el-input
              v-model="formFieldsData.api_url"
              placeholder="请输入api地址"
              autocomplete="off"
              clearable
            />
          </el-form-item>
          <el-form-item label="Header">
            <avue-crud
              ref="crudHeader"
              :option="tableOption"
              :data="headerTableData"
              @row-update="addUpdateHeader"
              @row-del="rowDelHeader"
              @row-save="rowSaveHeader"
            >
              <template slot-scope="{ row, index }" slot="menu">
                <el-button
                  type="text"
                  size="small"
                  @click="rowCellHeader(row, index)"
                  >{{ row.$cellEdit ? "自定义保存" : "自定义修改" }}</el-button
                >
              </template>
            </avue-crud>
          </el-form-item>
          <el-form-item label="Body">
            <avue-crud
              ref="crudBody"
              :option="tableOption"
              :data="bodyTableData"
              @row-update="addUpdateBody"
              @row-del="rowDelBody"
              @row-save="rowSaveBody"
            >
              <template slot-scope="{ row, index }" slot="menu">
                <el-button
                  type="text"
                  size="small"
                  @click="rowCellBody(row, index)"
                  >{{ row.$cellEdit ? "自定义保存" : "自定义修改" }}</el-button
                >
              </template>
            </avue-crud>
          </el-form-item>
          <el-form-item label="Query">
            <avue-crud
              ref="crudQuery"
              :option="tableOption"
              :data="queryTableData"
              @row-update="addUpdateQuery"
              @row-del="rowDelQuery"
              @row-save="rowSaveQuery"
            >
              <template slot-scope="{ row, index }" slot="menu">
                <el-button
                  type="text"
                  size="small"
                  @click="rowCellQuery(row, index)"
                  >{{ row.$cellEdit ? "自定义保存" : "自定义修改" }}</el-button
                >
              </template>
            </avue-crud>
          </el-form-item>
          <el-form-item label="Auth">
            <avue-crud
              ref="crudAuth"
              :option="tableOption"
              :data="authTableData"
              @row-update="addUpdateAuth"
              @row-del="rowDelAuth"
              @row-save="rowSaveAuth"
            >
              <template slot-scope="{ row, index }" slot="menu">
                <el-button
                  type="text"
                  size="small"
                  @click="rowCellAuth(row, index)"
                  >{{ row.$cellEdit ? "自定义保存" : "自定义修改" }}</el-button
                >
              </template>
            </avue-crud>
          </el-form-item>
          <el-form-item
            label="content-type"
            :label-width="formLabelWidth"
            prop="content_type"
          >
            <el-select
              v-model="formFieldsData.content_type"
              style="width: 85%"
              placeholder="请选择content_type类型"
            >
              <el-option
                v-for="(item, key) in content_types"
                :key="key"
                :label="item"
                :value="key"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            label="文档url"
            :label-width="formLabelWidth"
            prop="doc_url"
          >
            <el-input
              v-model="formFieldsData.doc_url"
              placeholder="请输入文档url地址"
              autocomplete="off"
              clearable
            />
          </el-form-item>
          <el-form-item
            label="文档"
            :label-width="formLabelWidth"
            prop="document"
          >
            <el-input
              type="textarea"
              :rows="5"
              v-model="formFieldsData.document"
              placeholder="请输入文档内容，markdown格式"
              autocomplete="off"
              clearable
            />
          </el-form-item>
          <el-form-item
            label="示例请求数据"
            :label-width="formLabelWidth"
            prop="sample_data"
          >
            <el-input
              type="textarea"
              :rows="5"
              v-model="formFieldsData.sample_data"
              placeholder="请输入示例请求数据"
              autocomplete="off"
              clearable
            />
          </el-form-item>
          <el-form-item
            label="示例返回数据"
            :label-width="formLabelWidth"
            prop="sample_result"
          >
            <el-input
              type="textarea"
              :rows="5"
              v-model="formFieldsData.sample_result"
              placeholder="请输入示例返回数据"
              autocomplete="off"
              clearable
            />
          </el-form-item>
          <el-form-item label="排序" :label-width="formLabelWidth" prop="sort">
            <el-input-number
              v-model="formFieldsData.sort"
              :min="1"
              :max="100000"
            />
          </el-form-item>
          <el-form-item label="状态" :label-width="formLabelWidth">
            <el-radio v-model="formFieldsData.status" :label="1" checked
              >已完成</el-radio
            >
            <el-radio v-model="formFieldsData.status" :label="2"
              >待开发</el-radio
            >
            <el-radio v-model="formFieldsData.status" :label="3"
              >开发中</el-radio
            >
            <el-radio v-model="formFieldsData.status" :label="4"
              >已废弃</el-radio
            >
          </el-form-item>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="handleCancel()">取 消</el-button>
        <el-button type="primary" @click="submit">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import formOperate from "@/layout/mixin/formOperate";
import { userenvList } from "@/api/userenv";
export default {
  name: "apimanager_apitester",
  mixins: [formOperate],
  data() {
    return {
      formName: "apis",
      // 刷新路由
      refreshRoute: true,
      apicategoryProps: {
        label: "category_title"
      },
      formLabelWidth: "120px",
      // api搜索
      queryParam: {
        api_title: "",
        api_name: "",
        status: "",
        type: "",
        category_id: ""
      },
      formVisible: false,
      formFieldsData: {
        api_title: "",
        api_name: "",
        api_url: "",
        category_id: 0,
        type: "",
        methods: "",
        auth: "",
        header: "",
        query: "",
        body: "",
        doc_url: "",
        document: "",
        sample_data: "",
        sample_result: "",
        sort: "",
        status: "",
        content_type: "",
        env_id: "",
        memo: ""
      },
      url: "apitester",
      data: [],
      // 分类
      treeCategory: {
        data: [],
        default: [],
        prop: {
          label: "category_title",
          value: "id",
          emitPath: false,
          checkStrictly: true
        }
      },
      // methods类型
      type: {
        1: "remote",
        2: "local"
      },
      // methods类型
      methodsTypes: {
        POST: "POST",
        GET: "GET",
        PUT: "PUT",
        PATCH: "PATCH",
        DELETE: "DELETE",
        COPY: "COPY",
        HEAD: "HEAD",
        OPTIONS: "OPTIONS"
      },
      content_types: {
        "application/x-www-form-urlencoded":
          "application/x-www-form-urlencoded",
        "application/json; charset=utf-8": "application/json; charset=utf-8",
        "multipart/form-data": "multipart/form-data",
        raw: "raw"
      },
      // 表单验证
      rules: {
        api_title: [
          { required: true, message: "请输入名称", trigger: "blur" },
          { min: 3, max: 20, message: "长度在 3 到 20 个字符", trigger: "blur" }
        ],
        api_name: [
          { required: true, message: "请输入英文唯一标识", trigger: "blur" }
        ]
      },
      // 分类
      apicategory: [],
      userenvs: [],
      userenvid: {},
      // ↓ api form 表单 ↓
      headerTableData: [],
      bodyTableData: [],
      queryTableData: [],
      authTableData: [],
      // ↓ api form 表单 Options ↓
      tableOption: {
        refreshBtn: false,
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
                message: "请输入Key值",
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
      }
    };
  },
  // 初始化数据
  mounted() {
    this.$http.get("apicategory").then(response => {
      this.apicategory = response.data;
    });
    userenvList().then(response => {
      if (response.data.length !== 0) {
        response.data.forEach(env => {
          if (env.selected) {
            this.userenvid = env.id;
          }
        });
      }
      this.userenvs = response.data;
    });
  },
  methods: {
    testApi(id) {
      this.$router.push({ path: "/apimanager/apirun", query: { id } });
    },
    // 获取分类API
    getApicategoryData(data, node, self) {
      this.queryParam.category_id = data.id;
      this.handleSearch();
    },
    // 禁用/启用
    disOrEnableApi(api) {
      this.$http.put("apitester/switch/status/" + api.id).then(response => {
        this.$message({
          message: response.message,
          type: "success"
        });
      });
    },
    beforeCreate() {
      this.$http.get("apicategory").then(response => {
        this.treeCategory.data = response.data;
      });
    },
    beforeHandleUpdate(api) {
      this.beforeCreate();
      this.$http.get(this.url + "/" + api.id).then(response => {
        const api = response.data;
        this.handleUpdate(api);
      });
    },
    selectInit(row, index) {
      return row.id !== 0;
    },
    submit() {
      this.handleSubmit();
    },
    onJsonChange(value) {
      console.log("value:", value);
    },
    onJsonSave(value) {
      console.log("value:", value);
    },
    changeUserenv(env) {
      this.$http
        .get("apiTesterUserenv/selectAPIenv/" + env)
        .then(response => {});
    },
    // ↓ Header 表格 行编辑 ↓
    rowCellHeader(row, index) {
      this.$refs.crudHeader.rowCell(row, index);
    },
    rowCellBody(row, index) {
      this.$refs.crudBody.rowCell(row, index);
    },
    rowCellQuery(row, index) {
      this.$refs.crudQuery.rowCell(row, index);
    },
    rowCellAuth(row, index) {
      this.$refs.crudAuth.rowCell(row, index);
    },
    // ↓ Header 表格 编辑行数据 ↓
    addUpdateHeader(form, index, done, loading) {
      loading();
      done();
    },
    addUpdateBody(form, index, done, loading) {
      loading();
      done();
    },
    addUpdateQuery(form, index, done, loading) {
      loading();
      done();
    },
    addUpdateAuth(form, index, done, loading) {
      loading();
      done();
    },
    afterCancel() {
      setTimeout(() => {
        this.headerTableData = [];
        this.bodyTableData = [];
        this.queryTableData = [];
        this.authTableData = [];
      }, 400);
      Object.keys(this.formFieldsData).forEach(k => {
        switch (k) {
          case "category_id":
            this.formFieldsData[k] = null;
            break;
          case "type":
            this.formFieldsData[k] = "1";
            break;
          default:
            break;
        }
      });
    },
    // ↓ Header 表格 保存行数据 ↓
    rowSaveHeader(form, done) {
      done();
      let json = this.handlerTable(this.headerTableData);
      this.formFieldsData.header = json;
    },
    rowSaveBody(form, done) {
      done();
      let json = this.handlerTable(this.bodyTableData);
      this.formFieldsData.body = json;
    },
    rowSaveQuery(form, done) {
      done();
      let json = this.handlerTable(this.queryTableData);
      this.formFieldsData.query = json;
    },
    rowSaveAuth(form, done) {
      done();
      let json = this.handlerTable(this.authTableData);
      this.formFieldsData.auth = json;
    },
    // ↓ Header 表格 删除行数据 ↓
    rowDelHeader(form, index, done) {
      this.headerTableData.splice(index, 1);
      let json = this.handlerTable(this.headerTableData);
      this.formFieldsData.header = json;
    },
    rowDelBody(form, index, done) {
      this.bodyTableData.splice(index, 1);
      let json = this.handlerTable(this.bodyTableData);
      this.formFieldsData.body = json;
    },
    rowDelQuery(form, index, done) {
      this.queryTableData.splice(index, 1);
      let json = this.handlerTable(this.queryTableData);
      this.formFieldsData.query = json;
    },
    rowDelAuth(form, index, done) {
      this.authTableData.splice(index, 1);
      let json = this.handlerTable(this.authTableData);
      this.formFieldsData.auth = json;
    },
    // ↓ 处理 ApiBaseInfo Json数据格式 返回 Object 格式 ↓
    JsonToObject(json) {
      if (json && json !== "") {
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
    initTableData(json) {
      let obj = this.JsonToObject(json);
      let arr = Object.entries(obj).map(item => {
        return { key: item[0], value: item[1], $cellEdit: false };
      });
      return arr;
    },
    handlerTable(arr) {
      let obj = {};
      if (arr) {
        arr.forEach(item => {
          return (obj[item.key] = item.value);
        });
      }
      if (Object.keys(obj).length) {
        return JSON.stringify(obj);
      } else {
        return "";
      }
    }
  },
  watch: {
    formFieldsData: {
      handler(data) {
        if (data.header) {
          this.headerTableData = this.initTableData(data.header);
        }
        if (data.body) {
          this.bodyTableData = this.initTableData(data.body);
        }
        if (data.auth) {
          this.authTableData = this.initTableData(data.auth);
        }
        if (data.query) {
          this.queryTableData = this.initTableData(data.query);
        }
      },
      deep: true
    }
  }
};
</script>
<style>
.custom-tree-node {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 14px;
  padding-right: 8px;
}
</style>
