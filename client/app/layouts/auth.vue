<template>
  <div class="h-screen flex items-center justify-center px-4">
    <UButton
      class="absolute left-8 top-8 rounded-full z-10"
      color="neutral"
      icon="i-lucide-chevron-left"
      size="xl"
      to="/"
      variant="subtle"
    />
    <UPageCard class="max-w-sm w-full" variant="subtle">
      <slot />
    </UPageCard>
  </div>
</template>
