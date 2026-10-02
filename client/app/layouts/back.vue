<script lang="ts" setup>
const router = useRouter();
</script>

<template>
  <div>
    <AppHeader />
    <UMain>
      <UContainer class="pt-6">
        <UButton
          class="rounded-full"
          color="neutral"
          icon="i-lucide-chevron-left"
          size="xl"
          variant="subtle"
          @click="router.back()"
        />
      </UContainer>
      <slot />
    </UMain>
    <AppFooter />
  </div>
</template>
