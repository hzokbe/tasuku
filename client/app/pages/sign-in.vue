<script lang="ts" setup>
import * as z from 'zod';
import type { FormSubmitEvent } from '@nuxt/ui';

definePageMeta({
  layout: 'auth',
  middleware: 'guest',
});

useSeoMeta({
  title: 'Sign-in',
});

const fields = [
  {
    name: 'email',
    type: 'text' as const,
    label: 'E-mail',
    placeholder: 'Enter your e-mail',
    required: true,
  },
  {
    name: 'password',
    label: 'Password',
    type: 'password' as const,
    placeholder: 'Enter your password',
    required: true,
  },
];

const schema = z.object({
  email: z
    .string({ error: 'E-mail is required.' })
    .pipe(z.email('E-mail is not valid.')),
  password: z
    .string({ error: 'Password is required.' })
    .min(1, 'Password is required.'),
});

type Schema = z.output<typeof schema>;

const form = useTemplateRef('form');

function resetForm() {
  const state = form.value?.state;

  if (state) {
    state.email = '';

    state.password = '';
  }

  form.value?.formRef?.clear();
}

const { signIn } = useSignIn();

const { fetchUser } = useAuth();

const onSubmit = async (payload: FormSubmitEvent<Schema>) => {
  const success = await signIn(payload.data.email, payload.data.password);

  resetForm();

  if (success) {
    await fetchUser();

    await navigateTo('/');
  }
};
</script>

<template>
  <UAuthForm
    ref="form"
    :fields="fields"
    :schema="schema"
    :submit="{ label: 'Sign in' }"
    icon="i-lucide-lock"
    title="Welcome back"
    @submit="onSubmit"
  />
</template>
