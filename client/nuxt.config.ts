export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  modules: ['@nuxt/ui'],
  css: ['~/assets/css/main.css'],
  ssr: true,
  runtimeConfig: {
    public: {
      apiUrl: 'http://localhost:8000',
      appUrl: 'http://localhost:3000',
    },
  },
});
