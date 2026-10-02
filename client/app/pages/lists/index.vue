<script lang="ts" setup>
const { lists, fetchLists } = useLists();

await fetchLists();

definePageMeta({
  middleware: ['home-redirect'],
});
</script>

<template>
  <UContainer>
    <UPageHeader
      description="Organize your tasks, ideas and goals in one place."
      title="Lists"
    >
      <UButton
        aria-label="Create list"
        class="mt-4"
        icon="i-lucide-plus"
        label="Create list"
      />
    </UPageHeader>
    <UPageBody>
      <UPageGrid v-if="lists.length" class="lg:grid-cols-2">
        <UPageCard v-for="list in lists" :key="list.id" variant="subtle">
          <div class="flex items-start justify-between gap-2">
            <NuxtLink
              :to="`/lists/${list.id}`"
              class="min-w-0 flex-1 space-y-1"
            >
              <h3 class="flex items-center gap-2 font-medium">
                <UIcon name="i-lucide-list-checks" />
                {{ list.title }}
              </h3>
              <p class="text-sm text-muted">{{ list.description }}</p>
            </NuxtLink>
            <div class="flex items-center gap-1">
              <UButton
                aria-label="Edit list"
                color="neutral"
                icon="i-lucide-pencil"
                variant="ghost"
              />
              <UButton
                aria-label="Delete list"
                color="error"
                icon="i-lucide-trash-2"
                variant="ghost"
              />
            </div>
          </div>
        </UPageCard>
      </UPageGrid>
      <UEmpty
        v-else
        description="Create a list to get started"
        title="No lists yet"
      />
    </UPageBody>
  </UContainer>
</template>
