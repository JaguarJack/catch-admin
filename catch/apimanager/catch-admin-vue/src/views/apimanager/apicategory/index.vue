<template>
  <catch-table
    :ref="table.ref"
    :headers="table.headers"
    :border="true"
    :search="table.search"
    :filter-params="table.filterParams"
    :hide-pagination="true"
    :form-create="formCreate"
    :actions="table.actions"
    :api-route="table.apiRoute"
    :dialog-width="table.dialog.width"
    default-expand-all
    row-key="id"
    :tree-props="table.tree.props"
  />
</template>
<script>
import renderTable from '@/views/render-table-form'

export default {
  name:'apimanager_apicategory',
  mixins: [renderTable],
  data() {
      return {
        tableFrom: 'table/apimanager/ApiCategory',
      }
  },
  methods: {
    beforeSubmit(row) {
      if (row.form.parent_id instanceof Array) {
        row.form.parent_id = row.form.parent_id.length > 0 ? row.form.parent_id.pop() : 0
      }
      return row
    },
    afterHandleResponse() {
      this.$http.get('table/apimanager/ApiCategory', {params: { only: 'form'}}).then(response => {
        this.formCreate.rule = response.data.form
      })
    }
  }
}
</script>

