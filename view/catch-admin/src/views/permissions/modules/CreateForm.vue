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
          <a-input v-decorator="['username', {rules: [{required: true, min: 3, message: '请输入至少3个字符！'}]}]" />
        </a-form-item>
        <a-form-item
          label="邮箱"
          :labelCol="labelCol"
          :wrapperCol="wrapperCol"
        >
          <a-input v-decorator="['email', {rules: [{ validator: handleEmail }]}]" />
        </a-form-item>
        <a-form-item
          label="密码"
          :type="password"
          :labelCol="labelCol"
          :wrapperCol="wrapperCol"
        >
          <a-input type="password" v-decorator="['password', {rules: [{required: true, min: 5, message: '请输入密码'}]}]" />
        </a-form-item>
        <a-form-item
          label="确认密码"
          :labelCol="labelCol"
          :wrapperCol="wrapperCol"
        >
          <a-input type="password" v-decorator="['passwordConfirm', {rules: [{required: true, min: 5, message: '请确认密码'}]}]" />
        </a-form-item>
      </a-form>
    </a-spin>
  </a-modal>
</template>

<script>
import { validEmail } from '@/utils/validate'
import { store } from '@/api/user'

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

      form: this.$form.createForm(this)
    }
  },
  methods: {
    add () {
      this.visible = true
    },
    handleEmail (rule, value, callback) {
      if (!validEmail(value)) {
        callback(new Error('邮箱地址不正确'))
      }
      callback()
    },
    handleSubmit () {
      const { form: { validateFields } } = this
      this.confirmLoading = true
      validateFields((errors, values) => {
        if (!errors) {
          store(values).then((res) => {
            this.$notification['success']({
              message: res.data.message,
              duration: 4
            })
            this.confirmLoading = false
            this.handleCancel()
          })
            .catch(err => this.failed(err))
        }
      })
    },
    failed (errors) {
      this.$notification['error']({
        message: errors.message,
        duration: 4
      })
      this.confirmLoading = false
    },
    handleCancel () {
      this.visible = false
    }
  }
}
</script>
