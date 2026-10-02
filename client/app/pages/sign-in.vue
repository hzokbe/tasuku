<script lang="ts" setup>
import type { FormSubmitEvent } from '@nuxt/ui';
import { type SignInData, signInSchema } from '~/utils/schemas/schemas.ts';
import { useAuth } from '~/composables/useAuth.ts';

const toast = useToast();

const { signIn } = useAuth();

const fields = [
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
];

const onSubmit = async (event: FormSubmitEvent<SignInData>) => {
  try {
    await signIn(event.data);

    await navigateTo('/');
  } catch (error: any) {
    if (error.status === 401) {
      toast.add({
        title: "Couldn't sign in",
        description: error?.data?.message ?? 'Invalid e-mail or password',
        color: 'error',
      });

      return;
    }

    toast.add({
      title: "Couldn't sign in",
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
    :schema="signInSchema"
    :submit="{ label: 'Sign-in' }"
    title="Sign-in"
    @submit="onSubmit"
  >
    <template #description>
      Don't have an account?
      <ULink class="text-primary font-medium" to="/sign-up">Sign-up</ULink>.
    </template>
    <template #footer>
      Forgot your password?
      <ULink class="text-primary font-medium" to="/forgot-password">
        Reset your password
      </ULink>
    </template>
  </UAuthForm>
</template>
