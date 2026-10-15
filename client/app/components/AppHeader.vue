<template>
  <UHeader
    :toggle="false"
    :ui="{ title: 'items-center gap-2' }"
    class="fixed w-full"
  >
    <template #title>
      <UIcon class="size-5 text-primary" name="i-lucide-list-checks" />
      <h1>Tasuku</h1>
    </template>
    <template #right>
      <UColorModeButton />
    </template>
  </UHeader>
</template>
