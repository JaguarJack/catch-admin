<template>
  <a-modal
    title="编辑用户"
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
          :labelCol="labelCol"
          :wrapperCol="wrapperCol"
        >
          <a-input type="password"/>
        </a-form-item>
        <a-form-item
          label="确认密码"
          :labelCol="labelCol"
          :wrapperCol="wrapperCol"
        >
          <a-input type="password"/>
        </a-form-item>
      </a-form>
    </a-spin>
  </a-modal>
</template>

<script>
import pick from 'lodash.pick'
import { validEmail } from '@/utils/validate'
import { update } from '@/api/user'

export default {
  name: 'EditUser',
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
      mdl: {},
      form: this.$form.createForm(this)
    }
  },
  methods: {
    edit (record) {
      console.log(record)
      this.visible = true
      const { form: { setFieldsValue } } = this
      setFieldsValue(pick({ username: '123' }))
    },
    handleEmail (rule, value, callback) {
      if (!validEmail(value)) {
        callback(new Error('邮箱地址不正确'))
      }
      callback()
    },
    handleSubmit () {
      const { form: { validateFields } } = this
      validateFields((errors, values) => {
        if (!errors) {
          this.confirmLoading = true
          update(values).then((res) => {
            this.$notification['success']({
              message: res.data.message,
              duration: 4
            })
            this.confirmLoading = false
            this.destroy()
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
      console.log(12312)
      // clear form & currentStep
      this.visible = false
    }
  }
}
</script>
