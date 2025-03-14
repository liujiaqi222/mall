<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const responseData = ref<any>(null)
const error = ref<string | null>(null)


onMounted(() => {
  jumpToWechatMiniProgram()
})

const handleConversion = async () => {
  try {
    error.value = null
    const response = await axios.post('https://s.ylle.cn/api/v1/conversion', {
      event_type: 'active',
      callback: route.query.clickid,

    })
    console.log(response.data)
    responseData.value = response.data
  } catch (err: any) {
    console.error('Error tracking conversion:', err)
    error.value = err.response?.data?.error || err.message || 'Failed to track conversion'
    responseData.value = null
  }
}

// 跳转到微信小程序的函数SS
const jumpToWechatMiniProgram = () => {
  try {


    window.location.href = 'weixin://dl/business/?appid=wx6e9bee57a65e5276&path=pages/extension/news_details/index&query=id=2&env_version=release'
  } catch (err: any) {
    console.error('Error jumping to WeChat Mini Program:', err)
    error.value = '跳转到微信小程序失败，请确保已安装微信'
  }
}
</script>

<template> <button @click="jumpToWechatMiniProgram"
    class="bg-green-500 hover:bg-green-700 text-white font-bold px-4 py-2 rounded">
    跳转到微信小程序
  </button>
  <main class="p-4">
    <div class="space-y-4">
      <div>
        <h2 class="text-lg font-semibold">url路径</h2>
        <p class="text-gray-600">{{ route.path }}</p>
      </div>

      <div>
        <h2 class="text-lg font-semibold">参数</h2>
        <pre class="bg-gray-100 rounded p-2">{{ JSON.stringify(route.query) }}</pre>
      </div>

      <div v-if="route.query.clickid" class="space-y-4">
        <div class="flex space-x-4">
          <button @click="handleConversion"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded">
            点击上传
          </button>


        </div>

        <div v-if="responseData" class="mt-4">
          <h2 class="text-lg font-semibold text-green-600">API 响应:</h2>
          <pre class="bg-green-50 p-2 rounded mt-2 overflow-auto">
            {{ JSON.stringify(responseData, null, 2) }}
          </pre>
        </div>

        <div v-if="error" class="mt-4">
          <h2 class="text-lg font-semibold text-red-600">错误:</h2>
          <pre class="bg-red-50 p-2 rounded mt-2 text-red-600">
            {{ error }}
          </pre>
        </div>
      </div>

    </div>

  </main>

</template>
