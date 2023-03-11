import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import alias from '@rollup/plugin-alias'
import vueJsx from '@vitejs/plugin-vue-jsx'
import { resolve } from 'path'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import Icons from 'unplugin-icons/vite'
const rootPath = resolve(__dirname)
import { createHtmlPlugin } from 'vite-plugin-html'

// https://vitejs.dev/config/
export default defineConfig(({ command, mode }) => {
  const env = loadEnv(mode, process.cwd(), '')
  return {
    plugins: [
      vue(),
      vueJsx(),
      createHtmlPlugin({
        minify: true,
        template: 'public/admin.html',
        inject: {
          data: {
            title: env.APP_NAME,
          },
        },
      }),
      alias({
        entries: [
          {
            find: '/admin',
            replacement: resolve(rootPath, 'resources/admin'),
          },
          {
            find: '@/module',
            replacement: resolve(rootPath, 'modules'),
          },
        ],
      }),
      AutoImport({
        imports: ['vue', 'vue-router', 'pinia', '@vueuse/core'],
        // resolvers: [ ElementPlusResolver({importStyle: 'sass'}) ]
      }),
      Components({
        dirs: ['resources/admin/components/', 'resources/admin/layout/'],

        extensions: ['vue'],

        deep: true,

        dts: true,

        include: [/\.vue$/, /\.vue\?vue/],

        exclude: [/[\\/]node_modules[\\/]/, /[\\/]\.git[\\/]/, /[\\/]\.nuxt[\\/]/],
        // resolvers: [ ElementPlusResolver({ importStyle: 'sass'}) ]
      }),
      Icons({
        compiler: 'vue3',
        autoInstall: true,
      }),
    ],
    publicDir: './resources/admin/public',
    define: {
      BASE_URL: env.BASE_URL,
    },
    preprocessorOptions: {
      scss: {
        // additionalData: `@use "@/assets/styles/element.scss" as *;`,
      },
    },
    server: {
      host: '127.0.0.1',
      port: 8000,
      open: true, // 自动打开浏览器
      cors: true, // 允许跨域
      strictPort: false, // 端口占用直接退出
      hmr: true,
      fs: {
        allow: ['./'],
      },
    },
    build: {
      chunkSizeWarningLimit: 2000,
      minify: 'terser',
      terserOptions: {
        compress: {
          drop_console: false,
          pure_funcs: ['console.log', 'console.info'],
          drop_debugger: true,
        },
      },
      // emptyOutDir: false,
      outDir: 'public/admin',
      assetsDir: 'assets',
      rollupOptions: {
        input: './public/admin.html',
        output: {
          chunkFileNames: 'assets/js/[name]-[hash].js',

          entryFileNames: 'assets/js/[name]-[hash].js',

          assetFileNames: 'assets/[ext]/[name]-[hash].[ext]',
        },
      },
    },
  }
})
