<template>
  <a-modal
    title="新建用户"
    :width="640"
    :visible="visible"
    :confirmLoading="confirmLoading"
    @ok="handleSubmit"
    @cancel="handleCancel"
  >
    <a-spin :spinning="confirmLoading">
      <a-form :form="form">
        <a-form-item
          label="用户名"
          type="text"
          :labelCol="labelCol"
          :wrapperCol="wrapperCol"
        >
          <a-input allowClear v-decorator="['username', {rules: [{required: true, min: 3, message: '请输入至少3个字符！'}]}]" />
        </a-form-item>
        <a-form-item
          label="邮箱"
          :labelCol="labelCol"
          :wrapperCol="wrapperCol"
        >
          <a-input allowClear v-decorator="['email', {rules: [{ required: true, validator: handleEmail }]}]" />
        </a-form-item>
        <a-form-item
          label="密码"
          :labelCol="labelCol"
          :wrapperCol="wrapperCol"
        >
          <a-input allowClear allotype="password" v-decorator="['password', {rules: [{required: required, min: 5, message: '请输入密码'}]}]" />
        </a-form-item>
        <a-form-item
          label="确认密码"
          :labelCol="labelCol"
          :wrapperCol="wrapperCol"
        >
          <a-input allowClear type="password" v-decorator="['passwordConfirm', {rules: [{required: required, min: 5, message: '请确认密码'}]}]" />
        </a-form-item>
        <a-form-item
          label="选择角色"
          :labelCol="labelCol"
          :wrapperCol="wrapperCol"
        >
          <a-tree-select
            style="width: 320px"
            :dropdownStyle="{ maxHeight: '400px', overflow: 'auto' }"
            :treeData="roles"
            placeholder="请选择角色"
            allowClear
            treeCheckable
            treeDefaultExpandAll
            @change="onChange"
            :showCheckedStrategy="SHOW_PARENT"
            v-decorator="['roles', {initialValue: roleids},{rules: [{required: true, message: '请选择角色'}]}]"
          >
          </a-tree-select>
        </a-form-item>
      </a-form>
    </a-spin>
  </a-modal>
</template>

<script>
import { validEmail } from '@/utils/validate'
import { store, update, read } from '@/api/user'
import { getRoleList } from '@/api/role'
import pick from 'lodash.pick'
import { TreeSelect } from 'ant-design-vue'
const SHOW_PARENT = TreeSelect.SHOW_PARENT
export default {
  data () {
    return {
      labelCol: {
        xs: { span: 24 },
        sm: { span: 7 }
      },
      wrapperCol: {
        xs: { span: 24 },
        sm: { span: 13 }
      },
      visible: false,
      confirmLoading: false,
      id: null,
      form: this.$form.createForm(this),
      roles: [],
      defaultRoles: [],
      roleids: [],
      SHOW_PARENT,
      required: true
    }
  },
  methods: {
    add () {
      this.visible = true
      this.getRoles()
    },
    edit (record) {
      this.visible = true
      this.required = false
      this.id = record.id
      this.getRoles()
      this.getUser(this.id)
      console.log(this.defaultRoles)
      const { form: { setFieldsValue } } = this
      this.$nextTick(() => {
        setFieldsValue(pick(record, ['username', 'email']))
      })
    },
    handleEmail (rule, value, callback) {
      if (!validEmail(value)) {
        callback(new Error('邮箱地址不正确'))
      }
      callback()
    },
    // 获取角色
    getRoles () {
      getRoleList().then(res => {
        this.roles = this.resetRoles(res.data)
      })
    },
    // 获取用户角色
    getUser (id) {
      read(id).then(res => {
        const roles = res.data.roles
        roles.map(item => {
          this.roleids.push(item.id)
          this.defaultRoles.push(item.role_name)
        })
      })
    },
    // 重组树结构
    resetRoles (roles) {
      roles.map(item => {
        item.title = item.role_name
        item.value = item.id
        if (item.children) {
          this.resetRoles(item.children)
        }
      })
      return roles
    },
    handleSubmit () {
      const { form: { validateFields } } = this
      if (this.id) {
        validateFields(['username', 'email', 'roles'], (errors, values) => {
          if (!errors) {
            this.confirmLoading = true
            values['roles'] = this.roleids
            update(this.id, values).then((res) => {
              this.refresh(res.message)
            }).catch(err => this.failed(err))
          }
        })
      } else {
        validateFields((errors, values) => {
          if (!errors) {
            this.confirmLoading = true
            store(values).then((res) => {
              this.refresh(res.message)
            }).catch(err => this.failed(err))
          }
        })
      }
    },
    failed (errors) {
      this.$notification['error']({
        message: errors.message,
        duration: 4
      })
      this.handleCancel()
    },
    handleCancel () {
      this.id = null
      this.visible = false
      this.required = true
      this.confirmLoading = false
      this.form.resetFields()
      this.roleids = []
      this.defaultRoles = []
      this.roles = []
    },
    refresh (message) {
      this.$notification['success']({
        message: message,
        duration: 4
      })
      this.handleCancel()
      this.$parent.$parent.handleOk()
    },
    onChange (value, node, extra) {
      this.roleids = value
    }
  }
}
</script>
