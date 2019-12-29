<template>
  <a-modal
    :title="title"
    :width="640"
    :visible="visible"
    :confirmLoading="confirmLoading"
    @ok="handleSubmit"
    @cancel="handleCancel"
  >
    <a-spin :spinning="confirmLoading">
      <a-form :form="form">
        <a-form-item
          label="角色名称"
          type="text"
          :labelCol="labelCol"
          :wrapperCol="wrapperCol"
        >
          <a-input v-decorator="['role_name', {rules: [{required: true, min: 3, message: '请输入至少3个字符！'}]}]" />
        </a-form-item>
        <a-form-item
          label="描述"
          type="textarea"
          :labelCol="labelCol"
          :wrapperCol="wrapperCol"
        >
          <a-textarea v-decorator="['description']" />
        </a-form-item>
        <a-form-item
          label="权限"
          :labelCol="labelCol"
          :wrapperCol="wrapperCol">
          <a-tree
            checkable
            checkStrictly
            :treeData="permissions"
            @check="this.onCheck"
            :checkedKeys="permissionids"
          >
          </a-tree>
        </a-form-item>
      </a-form>
    </a-spin>
  </a-modal>
</template>

<script>
import { store, update, read } from '@/api/role'
import pick from 'lodash.pick'
import { getPermissionList } from '@/api/permission'

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
      title: '新建角色',
      confirmLoading: false,
      id: null,
      parent_id: 0,
      form: this.$form.createForm(this),
      permissions: [],
      permissionids: []
    }
  },
  methods: {
    add () {
      this.visible = true
      this.title = '新增角色'
      this.getPermissions()
    },
    edit (record) {
      this.visible = true
      this.title = '编辑角色'
      const { form: { setFieldsValue } } = this
      this.id = record.id
      this.getRolePermissions(this.id)
      this.getPermissions(record.parent_id > 0 ? { role_id: record.parent_id } : {})
      this.$nextTick(() => {
        setFieldsValue(pick(record, ['role_name', 'description', 'permissions']))
      })
    },
    addSon (record) {
      this.visible = true
      this.title = '新增子角色 (' + record.role_name + ')'
      this.parent_id = record.id
      this.getPermissions({ role_id: this.parent_id })
    },
    // 获取角色权限
    getRolePermissions (id) {
      read(id).then((res) => {
        const permissions = res.data.permissions
        permissions.map(item => {
          this.permissionids.push(item.id)
        })
      })
    },
    getPermissions (params) {
      getPermissionList(params).then(res => {
        this.permissions = this.resetPermissions(res.data)
      })
    },
    handleSubmit () {
      const { form: { validateFields } } = this
      this.confirmLoading = true
      if (this.id) {
        validateFields((errors, values) => {
          if (!errors) {
            values['permissions'] = this.permissionids
            update(this.id, values).then((res) => {
              this.refresh(res.message)
            }).catch(err => this.failed(err))
          }
        })
      } else {
        validateFields((errors, values) => {
          if (!errors) {
            values['permissions'] = this.permissionids
            if (this.parent_id > 0) {
              values['parent_id'] = this.parent_id
            }
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
      this.visible = false
      this.id = null
      this.confirmLoading = false
      this.parent_id = 0
      this.permissionids = []
      this.form.resetFields()
    },
    refresh (message) {
      this.$notification['success']({
        message: message,
        duration: 4
      })
      this.handleCancel()
      this.$parent.$parent.handleOk()
    },
    // 重组树结构
    resetPermissions (permissions) {
      permissions.map(item => {
        item.title = item.permission_name
        item.key = item.id
        if (item.children) {
          this.resetPermissions(item.children)
        }
      })
      return permissions
    },
    onCheck (checkedKeys, info) {
      const data = info.node.dataRef
      const ids = []
      ids.push(data.id)
      if (data.hasOwnProperty('children')) {
        this.getAllLeaf(data.children, ids)
      }
      if (info.checked) {
        this.permissionids = this.permissionids.concat(ids)
      } else {
        this.permissionids = this.permissionids.filter(function (v) { return ids.indexOf(v) === -1 })
      }
    },
    getAllLeaf (data, ids = []) {
      data.forEach(item => {
        ids.push(item.id)
        if (item.hasOwnProperty('children')) {
          this.getAllLeaf(item.children, ids)
        }
      })
    }
  }
}
</script>
