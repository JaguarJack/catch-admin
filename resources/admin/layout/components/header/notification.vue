<template>
  <!-- 通知 -->
  <div class="w-10 h-10 grid place-items-center rounded-full mt-2 hover:cursor-pointer" ref="messageRef" v-click-outside="onClickOutside">
    <el-badge :value="3">
      <Icon name="bell" />
    </el-badge>
    <el-popover ref="popoverRef" :virtual-ref="messageRef" trigger="hover" virtual-triggering :width="300">
      <el-tabs model-value="message">
        <el-tab-pane label="消息(8)" name="message">
          <div>
            <div class="flex flex-row w-full border-b border-b-slate-300 dark:border-b-slate-500 mt-2" v-for="(message, key) in messages" :key="key">
              <div>
                <el-avatar src="https://cube.elemecdn.com/0/88/03b0d39583f48206768a7534e55bcpng.png" class="w-2 h-2" />
              </div>
              <div class="ml-2 h-10 mt-2">
                {{ message }}
              </div>
            </div>
          </div>
        </el-tab-pane>
        <el-tab-pane label="通知(1)" name="notice">
          <div>
            <div class="flex flex-row w-full border-b border-b-slate-300 dark:border-b-slate-500 mt-2" v-for="(message, key) in messages" :key="key">
              <div>
                <el-avatar src="https://cube.elemecdn.com/0/88/03b0d39583f48206768a7534e55bcpng.png" class="w-2 h-2" />
              </div>
              <div class="ml-2 h-10 mt-2">
                {{ message }}
              </div>
            </div>
          </div>
        </el-tab-pane>
      </el-tabs>
    </el-popover>
  </div>
</template>
<script setup lang="ts">
import { ref, unref } from 'vue'
import { ClickOutside as vClickOutside } from 'element-plus'
const messageRef = ref()
const popoverRef = ref()
const onClickOutside = () => {
  unref(popoverRef).popperRef?.delayHide?.()
}

const messages = ref()

messages.value = ['你收到 catchadmin 的好友申请', '你收到 catchadmin pro 的 license 授权', '你收到 catchadmin 通知']
</script>
