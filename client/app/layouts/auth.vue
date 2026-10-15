<template>
  <UMain class="flex flex-col items-center justify-center min-h-dvh">
    <UButton
      class="absolute left-8 top-8 rounded-full"
      color="neutral"
      icon="i-lucide-chevron-left"
      size="xl"
      to="/"
      variant="subtle"
    />
    <UPageCard variant="subtle" class="min-w-xs">
      <slot />
    </UPageCard>
  </UMain>
</template>
