<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const error = ref<string | null>(null)


onMounted(() => {
  // 检查URL中是否有id参数
  if (route.query.id) {
    jumpToWeChatMiniProgram()
  }
})

// 监听路由参数变化
watch(() => route.query.id, (newId) => {
  if (newId) {
    jumpToWeChatMiniProgram()
  }
})

// 跳转到微信小程序的函数
const jumpToWeChatMiniProgram = () => {
  // 从route.query中获取id参数
  const id = route.query.id
  console.log('从route获取到的ID:', id)
  try {
    window.location.href = `weixin://dl/business/?appid=wx6e9bee57a65e5276&path=pages/extension/news_details/index&query=id=${id}&env_version=release`
  } catch (err: any) {
    console.error('Error jumping to WeChat Mini Program:', err)
    error.value = '跳转到微信小程序失败，请确保已安装微信'
  }
}
</script>

<template>
  <div class="min-h-screen bg-white flex flex-col items-center justify-start p-4 pt-8 text-xs">
    <!-- SSL安全提示 -->
    <div class="flex items-center text-[#07C160] mb-8">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd"
          d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
          clip-rule="evenodd" />
      </svg>
      <span class='text-[#666666]'>本链接经过<span class='text-[#333] font-semibold'>SSL安全加密</span>，请放心点击！</span>
    </div>

    <!-- 微信图标 -->
    <div class="mb-2">
      <svg t="1741943376757" class="icon w-24" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"
        p-id="5727" width="180" height="180">
        <path
          d="M664.250054 368.541681c10.015098 0 19.892049 0.732687 29.67281 1.795902-26.647917-122.810047-159.358451-214.077703-310.826188-214.077703-169.353083 0-308.085774 114.232694-308.085774 259.274068 0 83.708494 46.165436 152.460344 123.281791 205.78483l-30.80868 91.730191 107.688651-53.455469c38.558178 7.53665 69.459978 15.308661 107.924012 15.308661 9.66308 0 19.230993-0.470721 28.752858-1.225921-6.025227-20.36584-9.521864-41.723264-9.521864-63.862493C402.328693 476.632491 517.908058 368.541681 664.250054 368.541681zM498.62897 285.87389c23.200398 0 38.557154 15.120372 38.557154 38.061874 0 22.846334-15.356756 38.156018-38.557154 38.156018-23.107277 0-46.260603-15.309684-46.260603-38.156018C452.368366 300.994262 475.522716 285.87389 498.62897 285.87389zM283.016307 362.090758c-23.107277 0-46.402843-15.309684-46.402843-38.156018 0-22.941502 23.295566-38.061874 46.402843-38.061874 23.081695 0 38.46301 15.120372 38.46301 38.061874C321.479317 346.782098 306.098002 362.090758 283.016307 362.090758zM945.448458 606.151333c0-121.888048-123.258255-221.236753-261.683954-221.236753-146.57838 0-262.015505 99.348706-262.015505 221.236753 0 122.06508 115.437126 221.200938 262.015505 221.200938 30.66644 0 61.617359-7.609305 92.423993-15.262612l84.513836 45.786813-23.178909-76.17082C899.379213 735.776599 945.448458 674.90216 945.448458 606.151333zM598.803483 567.994292c-15.332197 0-30.807656-15.096836-30.807656-30.501688 0-15.190981 15.47546-30.477129 30.807656-30.477129 23.295566 0 38.558178 15.286148 38.558178 30.477129C637.361661 552.897456 622.099049 567.994292 598.803483 567.994292zM768.25071 567.994292c-15.213493 0-30.594809-15.096836-30.594809-30.501688 0-15.190981 15.381315-30.477129 30.594809-30.477129 23.107277 0 38.558178 15.286148 38.558178 30.477129C806.808888 552.897456 791.357987 567.994292 768.25071 567.994292z"
          fill="#07BD5D" p-id="5728"></path>
      </svg>
    </div>

    <!-- 加载提示 -->
    <div class="text-gray-600 mb-8 text-center">
      <p>正在跳转中...</p>
      <p class="text-sm mt-2">如未自动打开微信请点击下方按钮</p>
    </div>

    <!-- 跳转按钮 -->
    <button @click="jumpToWeChatMiniProgram"
      class="bg-[#07C160] hover:bg-[#06B054] text-white font-medium py-3 px-12 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#07C160] focus:ring-opacity-50  text-sm">
      点击立即前往微信
    </button>

    <!-- 错误提示 -->
    <div v-if="error" class="mt-4 text-red-500 text-center">
      {{ error }}
    </div>
  </div>
</template>
