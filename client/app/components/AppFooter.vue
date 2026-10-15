<template>
  <USeparator class="h-px" />
  <UFooter>
    <template #left>
      <p class="text-muted text-sm">
        © {{ new Date().getFullYear() }} Tasuku. All rights reserved.
      </p>
    </template>
    <template #right>
      <UButton
        aria-label="Tasuku on GitHub"
        color="neutral"
        icon="i-simple-icons-github"
        target="_blank"
        to="https://github.com/hzokbe/tasuku"
        variant="ghost"
      />
    </template>
  </UFooter>
</template>
