<script lang="ts" setup>
import {
  type ForgotPasswordData,
  forgotPasswordSchema,
} from '~/utils/schemas/schemas.ts';
import type { FormSubmitEvent } from '@nuxt/ui';

const fields = [
  {
    name: 'email',
    type: 'text' as const,
    label: 'E-mail',
    placeholder: 'Enter your e-mail',
    defaultValue: '',
  },
];

const toast = useToast();

const { sendResetPasswordLink } = useAuth();

const onSubmit = async (event: FormSubmitEvent<ForgotPasswordData>) => {
  try {
    await sendResetPasswordLink(event.data.email);

    toast.add({
      title: 'Check your inbox',
      description:
        "If an account exists for this e-mail, you'll receive a reset link shortly.",
      'onUpdate:open': (open) => {
        if (!open) {
          navigateTo('/');
        }
      },
    });
  } catch (error: any) {
    toast.add({
      title: "Couldn't send the e-mail",
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
    :schema="forgotPasswordSchema"
    :submit="{ label: 'Send reset link' }"
    description="Enter your e-mail and we'll send you a link to reset it."
    title="Forgot your password?"
    @submit="onSubmit"
  >
  </UAuthForm>
</template>
