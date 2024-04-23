import { defineComponent } from 'vue'
import { ElTableColumn } from 'element-plus'
import { Column } from './ctable'

export default defineComponent({
  name: 'DynamicTable',
  props: {
    columns: {
      type: Array<Column>,
      default: []
    },
    api: {
      type: String,
      default: ''
    }
  },
  setup(props, { slots }) {
    const renderColumns = (columns: Array<Column>) => {
      return columns.map(column => {
        if (column.children) {
          return (
            <ElTableColumn label="{column.label}" key="{column.label}">
              {renderColumns(column.children)}
            </ElTableColumn>
          )
        } else {
          return (
            <ElTableColumn label="{column.label}" prop="{column.prop}" key="{column.prop}">
              {{
                default: (row: any) => {
                  // 使用默认插槽
                  return row[column.prop as string]
                },
                customSlot: (row: any) => {
                  // 使用具名插槽 customSlot
                  return slots.customSlot ? slots.customSlot({ column, row }) : null
                }
              }}
            </ElTableColumn>
          )
        }
      })
    }

    return renderColumns(props.columns)
    // return () => <ElTable data="{props.data}">{renderColumns(props.columns)}</ElTable>
  }
})
