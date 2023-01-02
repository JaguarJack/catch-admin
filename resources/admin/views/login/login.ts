import { reactive, ref } from 'vue'
import type { FormInstance } from 'element-plus'
import { useUserStore } from '/admin/stores/modules/user'
import router from '/admin/router'
import { t } from '/admin/support/helper'

export const useLogin = () => {
  const params = reactive({
    email: 'catch@admin.com',
    password: 'catchadmin',
    remember: false,
  })

  const loading = ref(false)

  const rules = reactive({
    email: [
      { required: true, message: t('login.verify.email.required'), trigger: 'blur' },
      { type: 'email', message: t('login.verify.email.invalid'), trigger: 'blur' },
    ],
    password: [{ required: true, message: t('login.verify.password.required'), trigger: 'blur' }],
  })

  const form = ref<FormInstance>()

  const submit = (loginForm: FormInstance | undefined) => {
    if (!loginForm) return

    loginForm.validate(valid => {
      if (valid) {
        loading.value = true
        const store = useUserStore()

        store
          .login(params)
          .then(() => {
            loading.value = false
            router.push({ path: '/' })
          })
          .catch(e => {
            loading.value = false
          })
      } else {
        return false
      }
    })
  }

  return {
    params,

    rules,

    loading,

    submit,

    form,
  }
}
