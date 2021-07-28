<template>
  <div class="item-btn-container">
    <el-row>
      <el-col v-show="!selectValue" :span="18">
        <el-button @click="clickBtn" v-if="isShow">{{ value }}</el-button>
        <el-input
          ref="inputRef"
          placeholder="请输入内容"
          v-else
          :value="value"
          @blur="inputBlur"
          @input="value => this.$emit('input', value)"
        ></el-input>
      </el-col>
      <el-col v-show="selectValue" :span="18">
        <el-tag v-if="filename" @close="delFile" closable type="success">{{
          filename
        }}</el-tag>
        <el-upload
          v-else
          action="/upload/image"
          :limit="1"
          ref="upload"
          :show-file-list="false"
          :http-request="uploadGuard"
        >
          <el-button size="small" type="primary">点击上传</el-button>
        </el-upload>
      </el-col>
      <el-col :span="6">
        <el-select v-if="selectshow" v-model="selectValue">
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          >
          </el-option>
        </el-select>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: [String, File, Number],
      default() {
        return null;
      }
    },
    selectshow: {
      value: Boolean,
      default() {
        return false;
      }
    }
  },
  data() {
    return {
      isShow: true,
      options: [
        {
          value: false,
          label: "Text"
        },
        {
          value: true,
          label: "File"
        }
      ],
      selectValue: false,
      filename: ""
    };
  },
  computed: {
    uploadFile() {
      return this.$refs.upload;
    }
  },
  methods: {
    /**@dis 切换状态 */
    clickBtn() {
      this.isShow = false;
      this.$nextTick(() => {
        this.$refs.inputRef.focus();
      });
    },
    inputBlur() {
      this.isShow = true;
    },
    uploadGuard({ file }) {
      this.filename = file.name;
      this.$emit("input", file);
    },
    delFile() {
      this.filename = "";
      this.$emit("input", null);
    }
  }
};
</script>

<style lang="scss">
.item-btn-container {
  .el-button {
    max-width: 100%;
    overflow: hidden;
    text-align: left;
  }
}
</style>
