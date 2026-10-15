<script lang="ts" setup>
const { isAuthenticated } = useAuth();

const { signOut } = useSignOut();

const loading = ref(false);

const onSignOut = async () => {
  loading.value = true;

  await signOut();

  loading.value = false;
};
</script>

<template>
  <UHeader
    :toggle="false"
    :ui="{ title: 'items-center gap-2' }"
    class="fixed w-full"
    to="/"
  >
    <template #title>
      <UIcon class="size-5 text-primary" name="i-lucide-list-checks" />
      <h1>Tasuku</h1>
    </template>
    <template #right>
      <UColorModeButton />
      <UButton
        v-if="isAuthenticated"
        :loading="loading"
        aria-label="Sign out"
        color="neutral"
        icon="i-lucide-log-out"
        label="Sign out"
        variant="ghost"
        @click="onSignOut"
      />
      <UButton
        v-if="!isAuthenticated"
        aria-label="Sign in"
        color="neutral"
        icon="i-lucide-log-in"
        label="Sign in"
        to="/sign-in"
        variant="ghost"
      />
    </template>
  </UHeader>
</template>
