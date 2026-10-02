<script lang="ts" setup>
import type { FormSubmitEvent } from '@nuxt/ui';
import { type SignUpData, signUpSchema } from '~/utils/schemas/schemas.ts';
import { useAuth } from '~/composables/useAuth.ts';

const toast = useToast();

const { signUp } = useAuth();

const fields = [
  {
    name: 'username',
    type: 'text' as const,
    label: 'Username',
    placeholder: 'Enter your username',
    defaultValue: '',
  },
  {
    name: 'email',
    type: 'text' as const,
    label: 'E-mail',
    placeholder: 'Enter your e-mail',
    defaultValue: '',
  },
  {
    name: 'password',
    type: 'password' as const,
    label: 'Password',
    placeholder: 'Enter your password',
    defaultValue: '',
  },
  {
    name: 'password_confirmation',
    type: 'password' as const,
    label: 'Password confirmation',
    placeholder: 'Password confirmation',
    defaultValue: '',
  },
];

const onSubmit = async (event: FormSubmitEvent<SignUpData>) => {
  try {
    await signUp(event.data);

    await navigateTo('/');
  } catch (error: any) {
    toast.add({
      title: "Couldn't create your account",
      description:
        error?.data?.message ?? 'Something went wrong. Please try again.',
      color: 'error',
    });
  }
};

definePageMeta({
  layout: 'auth',
});
</script>

<template>
  <UAuthForm
    :fields="fields"
    :schema="signUpSchema"
    :submit="{ label: 'Sign-up' }"
    title="Sign-up"
    @submit="onSubmit"
  >
    <template #description>
      Already have an account?
      <ULink class="text-primary font-medium" to="/sign-in">Sign-in</ULink>.
    </template>
  </UAuthForm>
</template>
