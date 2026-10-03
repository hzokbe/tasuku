<script lang="ts" setup>
import type { FormSubmitEvent } from '@nuxt/ui';
import type { List } from '~/types/list';
import { type ListData, listSchema } from '~/utils/schemas/schemas.ts';

const props = defineProps<{
  list?: List | null;
}>();

const emit = defineEmits<{
  submitted: [data: ListData];
}>();

const open = defineModel<boolean>('open', { default: false });

const state = reactive<Partial<ListData>>({
  title: '',
  description: '',
});

const isEditing = computed(() => !!props.list);

watch(open, (value) => {
  if (!value) {
    return;
  }

  Object.assign(state, {
    title: props.list?.title ?? '',
    description: props.list?.description ?? '',
  });
});

function onSubmit(event: FormSubmitEvent<ListData>) {
  emit('submitted', event.data);

  open.value = false;
}
</script>

<template>
  <UModal
    v-model:open="open"
    :title="isEditing ? 'Edit list' : 'New list'"
    description="Fill in the list details."
  >
    <template #body>
      <UForm
        id="list-form"
        :schema="listSchema"
        :state="state"
        class="space-y-4"
        @submit="onSubmit"
      >
        <UFormField label="Title" name="title" required>
          <UInput
            v-model="state.title"
            class="w-full"
            placeholder="List title"
          />
        </UFormField>
        <UFormField label="Description" name="description">
          <UTextarea
            v-model="state.description"
            class="w-full"
            placeholder="List description"
          />
        </UFormField>
      </UForm>
    </template>
    <template #footer="{ close }">
      <UButton
        color="neutral"
        label="Cancel"
        variant="outline"
        @click="close"
      />
      <UButton
        :label="isEditing ? 'Save' : 'Create'"
        form="list-form"
        type="submit"
      />
    </template>
  </UModal>
</template>
