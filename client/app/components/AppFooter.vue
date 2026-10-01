<template>
  <USeparator class="h-px" />
  <UFooter :ui="{ top: 'border-b border-default' }">
    <template #left>
      <p class="text-muted text-sm">
        Built with Nuxt UI • © {{ new Date().getFullYear() }}
      </p>
    </template>
    <template #right>
      <UButton
        aria-label="Nuxt UI on GitHub"
        color="neutral"
        icon="i-simple-icons-github"
        target="_blank"
        to="https://github.com/hzokbe/tasuku"
        variant="ghost"
      />
    </template>
  </UFooter>
</template>
