<template>
  <UHeader>
    <template #left>
      <NuxtLink
        class="focus-visible:outline-3 outline-primary/25 rounded-md p-1 -ms-1"
        to="/"
      >
        Tasuku
      </NuxtLink>
    </template>
    <template #right>
      <UColorModeButton />
      <UButton
        class="lg:hidden"
        color="neutral"
        icon="i-lucide-log-in"
        to="/sign-in"
        variant="ghost"
      />
      <UButton
        class="hidden lg:inline-flex"
        color="neutral"
        label="Sign-in"
        to="/sign-in"
        variant="outline"
      />
      <UButton
        class="hidden lg:inline-flex"
        color="neutral"
        label="Sign-up"
        to="/sign-up"
        trailing-icon="i-lucide-arrow-right"
      />
    </template>
    <template #body>
      <UButton
        block
        class="mb-3"
        color="neutral"
        label="Sign-in"
        to="/sign-in"
        variant="subtle"
      />
      <UButton block color="neutral" label="Sign-up" to="/sign-up" />
    </template>
  </UHeader>
</template>
