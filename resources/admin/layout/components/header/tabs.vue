<script setup lang="ts">
import { useNavTabStore } from '/admin/stores/modules/tabs'
import ContextMenu from './contextMenu.vue'
import { computed, ref, onMounted,onBeforeUnmount, watch } from 'vue'
const navTabStore = useNavTabStore()
const tabs = computed(() => navTabStore.getNavTabs)
</script>

<template>
    <div class="h-10 bg-white dark:bg-regal-dark px-1 sm:px-3 w-full flex gap-x-2" ref="container" v-if="tabs.length > 0">
        <ContextMenu>
            <el-tag
                class="mt-1.5 hover:cursor-pointer"
                v-for="(tag, index) in tabs" :key="index"
                :closable="!tag.meta.affix"
                :disable-transitions="false"
                :effect="tag.is_active ? 'dark' : 'plain'"
                @click.prevent="navTabStore.selectTab(tag)"
                @close="navTabStore.removeTab(index)"
            >
                {{ tag.meta.title }}
            </el-tag>
        </ContextMenu>
    </div>
</template>

<style scoped>

</style>
