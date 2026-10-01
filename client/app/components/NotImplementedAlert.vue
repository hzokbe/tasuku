<script lang="ts" setup>
defineProps<{
  title?: string;

  description?: string;
}>();
</script>

<template>
  <UContainer class="py-16">
    <UAlert
      :description="description ?? 'The page is not implemented yet.'"
      :title="title ?? 'Coming soon'"
      color="warning"
      icon="i-lucide-construction"
      variant="subtle"
    />
  </UContainer>
</template>
