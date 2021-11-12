<template>
  <div class="run-container">
    <el-card class="box-card">
      <el-row style="margin-bottom:5px">
        <el-button
          @click="dialogTableVisible = true"
          class="filter-item fr"
          type="primary"
          icon="el-icon-s-grid"
        >
        </el-button>
        <el-select
          style="margin-right:5px"
          class="filter-item fr"
          @change="changeUserenv"
          v-model="currentEnvId"
          placeholder="用户环境变量"
        >
          <el-option
            :value="env.id"
            :label="env.env_name"
            v-for="env in userEnvInfos"
            :key="env.id"
          />
        </el-select>
      </el-row>
      <el-input
        placeholder="请输入内容"
        v-model="currentInputUrl"
        class="input-with-select"
        :disabled="userable"
      >
        <el-select
          class="method_select"
          :disabled="userable"
          v-model="currentSelectMethod"
          slot="prepend"
          placeholder="请选择"
        >
          <el-option
            v-for="(mth, index) in apiMethods"
            :key="index"
            :label="mth"
            :value="mth"
          ></el-option>
        </el-select>
        <el-button
          type="primary"
          class="apisend"
          slot="append"
          icon="el-icon-s-promotion"
          @click="_runapi"
          :disabled="sendAble"
          >发送</el-button
        >
      </el-input>
      <el-tabs class="mt30 tab-liut" type="border-card">
        <el-tab-pane label="Header">
          <el-table :data="headerTableData">
            <el-table-column width="50">
              <template slot-scope="{ row }">
                <el-checkbox v-model="row.open"></el-checkbox>
              </template>
            </el-table-column>
            <el-table-column label="KEY">
              <template slot-scope="{ row }">
                <item-btn v-model="row.key" />
              </template>
            </el-table-column>
            <el-table-column label="VALUE">
              <template slot-scope="{ row }">
                <item-btn :selectshow="true" v-model="row.value" />
              </template>
            </el-table-column>
            <el-table-column width="50">
              <template slot="header" slot-scope="scope">
                <el-button
                  type="primary"
                  icon="el-icon-plus"
                  circle
                  @click="addRow(headerTableData, scope)"
                ></el-button>
              </template>
              <template slot-scope="{ row }">
                <el-button
                  icon="el-icon-delete"
                  circle
                  type="danger"
                  @click="delRow(row, headerTableData)"
                ></el-button>
              </template>
            </el-table-column>
          </el-table>
        </el-tab-pane>
        <el-tab-pane label="Query">
          <el-table :data="queryTableData">
            <el-table-column width="50">
              <template slot-scope="{ row }">
                <el-checkbox v-model="row.open"></el-checkbox>
              </template>
            </el-table-column>
            <el-table-column label="KEY">
              <template slot-scope="{ row }">
                <item-btn v-model="row.key" />
              </template>
            </el-table-column>
            <el-table-column label="VALUE">
              <template slot-scope="{ row }">
                <item-btn :selectshow="true" v-model="row.value" />
              </template>
            </el-table-column>
            <el-table-column width="50">
              <template slot="header" slot-scope="scope">
                <el-button
                  type="primary"
                  icon="el-icon-plus"
                  circle
                  @click="addRow(queryTableData, scope)"
                ></el-button>
              </template>
              <template slot-scope="{ row }">
                <el-button
                  icon="el-icon-delete"
                  circle
                  type="danger"
                  @click="delRow(row, queryTableData)"
                ></el-button>
              </template>
            </el-table-column>
          </el-table>
        </el-tab-pane>
        <el-tab-pane label="Body">
          <el-radio-group v-model="radio">
            <el-radio :label="0">none</el-radio>
            <el-radio :label="1">form-data</el-radio>
            <el-radio :label="2">x-www-form-urlencoded</el-radio>
            <el-radio :label="3">json</el-radio>
            <el-radio :label="4">raw(json)</el-radio>
          </el-radio-group>
          <vue-json-editor
            class="vjd"
            v-if="radio === 4"
            v-model="rawJson"
            :mode="'code'"
            lang="zh"
          ></vue-json-editor>
          <el-table
            v-else
            v-loading="loading"
            element-loading-text="This request dose not have a body"
            element-loading-spinner="el-icon-warning"
            element-loading-background="rgba(0, 0, 0, 0.8)"
            :data="bodyTableData"
          >
            <el-table-column width="50">
              <template slot-scope="{ row }">
                <el-checkbox v-model="row.open"></el-checkbox>
              </template>
            </el-table-column>
            <el-table-column label="KEY">
              <template slot-scope="{ row }">
                <item-btn v-model="row.key" />
              </template>
            </el-table-column>
            <el-table-column label="VALUE">
              <template slot-scope="{ row }">
                <item-btn :selectshow="true" v-model="row.value" />
              </template>
            </el-table-column>
            <el-table-column width="50">
              <template slot="header" slot-scope="scope">
                <el-button
                  type="primary"
                  icon="el-icon-plus"
                  circle
                  @click="addRow(bodyTableData, scope)"
                ></el-button>
              </template>
              <template slot-scope="{ row }">
                <el-button
                  icon="el-icon-delete"
                  circle
                  type="danger"
                  @click="delRow(row, bodyTableData)"
                ></el-button>
              </template>
            </el-table-column>
          </el-table>
        </el-tab-pane>
      </el-tabs>
      <el-card v-if="json" class="box-card mt30">
        <json-view :data="json" />
      </el-card>
    </el-card>
    <el-dialog :title="currentUserEnvName" :visible.sync="dialogTableVisible">
      <el-table :data="currentUserEnvJson" border style="width: 100%">
        <el-table-column prop="key" label="变量" fit> </el-table-column>
        <el-table-column prop="value" label="值" fit> </el-table-column>
      </el-table>
    </el-dialog>
  </div>
</template>
<script>
import vueJsonEditor from "vue-json-editor";
import { userenvList } from "@/api/userenv";
import ItemBtn from "./itemBtn.vue";
import jsonView from "vue-json-views";
import axios from "axios";
import qs from "qs";
export default {
  components: {
    jsonView,
    ItemBtn,
    vueJsonEditor
  },
  data() {
    return {
      // ↓ 是否允许用户发送请求 ↓
      sendAble: false,
      // ↓ 是否允许用户编辑 ↓
      userable: false,
      // ↓ 用户变量对话框展示与隐藏 ↓
      dialogTableVisible: false,
      // ↓ Api Mthods ↓
      apiMethods: [
        "POST",
        "GET",
        "PUT",
        "PATCH",
        "DELETE",
        "COPY",
        "HEAD",
        "OPTIONS"
      ],
      // ↓ Api 响应 ↓
      json: null,
      // ↓ api接口基本信息 ↓
      apiBaseInfo: {},
      // ↓ 用户所有环境变量 ↓
      userEnvInfos: [],
      // ↓ 当前用户选择的环境变量id ↓
      currentEnvId: null,
      // ↓ 用户当前输入的Url ↓
      currentInputUrl: "",
      // ↓ 用户当前选择的Api Method ↓
      currentSelectMethod: "GET",
      regEnv: /\{\{(.+?)\}\}/g,
      // ↓ 请求 body 下的 数据发送格式 0:none 1:form-data 2:x-www-form-urlencoded ↓
      radio: 2,
      checked: true,
      input: "",
      // ↓ 请求Body ↓
      bodyTableData: [],
      headerTableData: [],
      queryTableData: [],
      radioLabel: ["none", "form-data", "x-www-form-urlencoded", "json"],
      rawJson: {},
      headers: null,
      params: null
    };
  },
  computed: {
    // ↓ 用户当前选择环境变量信息 ↓
    currentUserEnvInfo() {
      if (this.userEnvInfos.length !== 0) {
        return this.userEnvInfos.filter(env => env.id === this.currentEnvId)[0];
      } else {
        return null;
      }
    },
    // ↓ 用户当前选择环境变量信息键值对模型 ↓
    currentUserEnvJson() {
      if (this.currentUserEnvInfo && this.currentUserEnvInfo.env_json) {
        let obj = JSON.parse(this.currentUserEnvInfo.env_json);
        return Object.entries(obj).map(item => {
          return { key: item[0], value: item[1] };
        });
      } else {
        return null;
      }
    },
    // ↓ 用户当前选择的环境变量名称 ↓
    currentUserEnvName() {
      if (this.currentUserEnvInfo && this.currentUserEnvInfo.env_name) {
        return this.currentUserEnvInfo.env_name;
      } else {
        return "未定义名称";
      }
    },
    // ↓ 用户当前选择环境变量信息Map数据模型 ↓
    currentUserEnvMap() {
      if (this.currentUserEnvInfo && this.currentUserEnvInfo.env_json) {
        let obj = JSON.parse(this.currentUserEnvInfo.env_json);
        return obj;
      } else {
        return null;
      }
    },
    // ↓ Api接口url (base_url + url) ↓
    currentApiUrl() {
      // ↓ reg formula ↓
      let regEnv = /\{\{(.+?)\}\}/g;
      // ↓ 拿到用户当前地址栏看见的 Url ↓
      let curInputUrl = this.currentInputUrl;
      // ↓ 判断用户是否使用了变量环境字符串 ↓
      let flag = regEnv.test(curInputUrl);
      if (flag) {
        if (this.currentUserEnvMap) {
          let new_url = this.replaceUserenv(curInputUrl); //curInputUrl.replace(regEnv,this.currentUserEnvMap["{{host}}"]);
          return new_url;
        } else {
          return null;
        }
      } else {
        return curInputUrl;
      }
    },
    loading() {
      return !Boolean(this.radio);
    }
  },
  mounted() {
    this.init();
  },
  methods: {
    init() {
      let id = this.$route.query.id;
      this.$http.get("apitester/" + id).then(response => {
        this.apiBaseInfo = response.data;
        this.apiBaseInfo.body = this.apiBaseInfo.body.replace(/'/g, '"');
        if (this.apiBaseInfo.body) {
          // let resstr = this.apiBaseInfo.body
          //   .replace(/\\/g, "")
          //   .replace(/"{/g, "{")
          //   .replace(/}"/g, "}");
          this.rawJson = JSON.parse(this.apiBaseInfo.body);
        } else {
          this.rawJson = {};
        }
        this.resetMethodAndUrl();
        this.initTable();
      });
      userenvList().then(response => {
        this.userEnvInfos = response.data;
        if (response.data.length !== 0) {
          response.data.forEach(env => {
            if (env.selected) {
              this.currentEnvId = env.id;
            }
          });
        }
      });
    },
    /**@dis 初始化table表格 */
    initTable() {
      let { header, query, body, content_type } = this.apiBaseInfo;
      this.radio = this.radioLabel.findIndex(el => {
        if (content_type.indexOf(el) !== -1) return true;
        return false;
      });
      let resTable = [header, query, body].map(el => {
        return el ? JSON.parse(el) : false;
      });
      [this.headerTableData, this.queryTableData, this.bodyTableData].some(
        (el, index) => {
          if (resTable[index] === false) return false;
          for (const [key, value] of Object.entries(resTable[index])) {
            if (typeof value === "string") {
              el.push({ open: true, key: key.trim(), value: value.trim() });
            } else if (typeof value === "object") {
              el.push({
                open: true,
                key: key.trim(),
                value: JSON.stringify(value)
              });
            } else {
              el.push({ open: true, key: key.trim(), value });
            }
          }
        }
      );
    },
    watchParam(arr) {
      let regEnv = /\{\{(.+?)\}\}/g;
      let watchCeche = {};
      arr.forEach(item => {
        let regFlag = regEnv.test(item.value);
        let cacheFlag = this.currentUserEnvMap[item.value];
        if (regFlag && cacheFlag) {
          watchCeche[item.key] = cacheFlag;
        }
      });
      return watchCeche;
    },
    // ↓ 执行Api 核心业务逻辑 ↓
    _runapi() {
      this.requestBeforeHook();
      switch (this.currentSelectMethod) {
        case "POST":
          this.apiPost();
          break;
        case "GET":
          this.apiGet();
          break;
        case "PUT":
          this.apiPut();
          break;
        case "DELETE":
          this.apiDelete();
          break;
        case "PATCH":
        case "COPY":
        case "HEAD":
        case "OPTIONS":
          this.$notify({
            title: "消息",
            message: "通知开发人员进行扩展",
            type: "info"
          });
          break;
        default:
          this.$notify({
            title: "消息",
            message: "平台版本暂时不支持该请求方法",
            type: "info"
          });
          break;
      }
    },
    headFactory(ctype, args) {
      let product = null;
      switch (ctype) {
        case "x-www-form-urlencoded":
          product = qs.stringify(args);
          break;
        case "form-data":
          let data = new FormData();
          for (const key in args) {
            data.append(key, args[key]);
          }
          product = data;
          break;
        case "raw":
          this.$notify({
            title: "消息",
            message: "平台版本暂时还未支持raw数据格式",
            type: "info"
          });
          product = args;
          break;
        case "json":
        default:
          product = args;
          break;
      }
      return product;
    },
    tbDataToObj(tb) {
      const params = {};
      tb.filter(el => el.open)
        .map(el =>
          Object.defineProperty({}, el.key, {
            value: el.value,
            writable: true,
            enumerable: true,
            configurable: true
          })
        )
        .forEach(el => Object.assign(params, el));
      return params;
    },
    async apiGet() {
      let { status, data } = await axios
        .get(this.currentApiUrl, {
          headers: this.headers,
          params: this.params
        })
        .catch(err => {
          this.json = err;
          this.$notify({
            title: "失败",
            message: "请求发送失败",
            type: "error"
          });
        });
      if (status === 200) {
        this.json = data;
        this.$notify({
          title: "成功",
          message: "请求发送成功",
          type: "success"
        });
      }
    },
    async apiPost() {
      const data =
        this.radio === 4
          ? this.rawJson
          : this.headFactory(
              this.radioLabel[this.radio],
              this.tbDataToObj(this.bodyTableData)
            );
      let res = await axios
        .post(this.currentApiUrl, data, {
          headers: this.headers,
          params: this.params
        })
        .catch(err => {
          this.json = {};
          this.$notify({
            title: "失败",
            message: "请求发送失败",
            type: "error"
          });
        });
      if (res.status === 200) {
        this.json = res.data;
        this.$notify({
          title: "成功",
          message: "请求发送成功",
          type: "success"
        });
      }
    },
    async apiPut() {
      const data = this.headFactory(
        this.radioLabel[this.radio],
        this.tbDataToObj(this.bodyTableData)
      );

      let res = await axios
        .put(this.currentApiUrl, data, {
          headers: this.headers,
          params: this.params
        })
        .catch(err => {
          this.json = err;
          this.$notify({
            title: "失败",
            message: "请求发送失败",
            type: "error"
          });
        });
      if (res.status === 200) {
        this.json = res.data;
        this.$notify({
          title: "成功",
          message: "请求发送成功",
          type: "success"
        });
      }
    },
    async apiDelete() {
      const data =
        this.radio === 4
          ? this.rawJson
          : this.headFactory(
              this.radioLabel[this.radio],
              this.tbDataToObj(this.bodyTableData)
            );
      let res = await axios
        .delete(this.currentApiUrl, {
          data,
          headers: this.headers,
          params: this.params
        })
        .catch(err => {
          this.json = err;
          this.$notify({
            title: "失败",
            message: "请求发送失败",
            type: "error"
          });
        });
      if (res.status === 200) {
        this.json = res.data;
        this.$notify({
          title: "成功",
          message: "请求发送成功",
          type: "success"
        });
      }
    },
    requestBeforeHook() {
      this.headers = this.watchParam(this.headerTableData);
      this.params = Object.assign(
        this.tbDataToObj(this.queryTableData),
        this.watchParam(this.queryTableData)
      );
    },
    // ↓ 修改用户环境变量 ↓
    changeUserenv(env) {
      this.$http.get("apiTesterUserenv/selectAPIenv/" + env).then(response => {
        this.$message.success(response.message);
      });
    },
    // ↓ 重置 Api Methods 与 Api Url ↓
    resetMethodAndUrl() {
      this.currentInputUrl = this.apiBaseInfo.api_url;
      this.currentSelectMethod = this.apiBaseInfo.methods.toLocaleUpperCase();
    },
    //循环用户选中的环境变量进行替换
    replaceUserenv(orgStr) {
      let userEnv = this.currentUserEnvJson;
      for (let envelement of userEnv) {
        orgStr = orgStr.replace(envelement.key, envelement.value);
      }
      return orgStr;
    },
    /**@dis 删除行 */
    delRow(row, _table) {
      let index = _table.findIndex(_row => row === _row);
      _table.splice(index, 1);
    },
    addRow(_table) {
      _table.push({ open: false, key: "KEY", value: "VALUE" });
    }
  },
  watch: {
    // ↓ 监听经过处理的 Api Url 控制发送按钮是否开启 ↓
    currentApiUrl(url) {
      let flag = /http|https/.test(url);
      if (url && flag) {
        this.sendAble = false;
      } else {
        this.sendAble = true;
      }
    }
  }
};
</script>
<style lang="scss" scoped>
.run-container {
  margin: 20px;
  .method_select {
    width: 100px;
  }
  .apisend {
    background-color: #70b9eb;
    color: white;
  }
  .mt30 {
    margin-top: 30px;
  }
  .tab-liut {
    min-height: 200px;
  }
}
</style>
<style lang="scss">
.vjd {
  .jsoneditor-vue {
    height: 430px;
  }
  .jsoneditor-poweredBy {
    display: none;
  }
}
</style>
