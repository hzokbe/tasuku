<script lang="ts" setup>
import {
  type ResetPasswordData,
  resetPasswordSchema,
} from '~/utils/schemas/schemas.ts';
import type { FormSubmitEvent } from '@nuxt/ui';

const fields = [
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

const route = useRoute();

const { resetPassword } = useAuth();

const toast = useToast();

const onSubmit = async (event: FormSubmitEvent<ResetPasswordData>) => {
  try {
    await resetPassword(route.params.token as string, event.data);

    toast.add({
      title: 'Password updated',
      description: 'You can now sign in with your new password.',
      'onUpdate:open': (open) => {
        if (!open) {
          navigateTo('/');
        }
      },
    });
  } catch (error: any) {
    toast.add({
      title: "Couldn't reset your password",
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
    :schema="resetPasswordSchema"
    :submit="{ label: 'Reset password' }"
    description="Choose a new password for your account."
    title="Reset password"
    @submit="onSubmit"
  >
  </UAuthForm>
</template>
