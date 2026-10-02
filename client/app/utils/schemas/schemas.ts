import { z } from 'zod';

export const signUpSchema = z
  .object({
    username: z
      .string()
      .min(3, 'Must be at least 3 characters')
      .max(16, 'Must be at most 16 characters')
      .regex(/^[a-zA-Z0-9_]+$/, 'Only letters, numbers and underscores'),
    email: z
      .email('Invalid e-mail')
      .max(254, 'Must be at most 254 characters')
      .transform((value) => value.toLowerCase()),
    password: z.string().min(8, 'Must be at least 8 characters'),
    password_confirmation: z.string(),
  })
  .refine((data) => data.password === data.password_confirmation, {
    path: ['password_confirmation'],
    message: 'Passwords do not match',
  });

export type SignUpData = z.infer<typeof signUpSchema>;

export const signInSchema = z.object({
  email: z.email('Invalid e-mail').transform((value) => value.toLowerCase()),
  password: z.string(),
});

export type SignInData = z.infer<typeof signInSchema>;

export const forgotPasswordSchema = z.object({
  email: z.email('Invalid e-mail').transform((value) => value.toLowerCase()),
});

export type ForgotPasswordData = z.infer<typeof forgotPasswordSchema>;

export const resetPasswordSchema = z
  .object({
    password: z.string().min(8, 'Must be at least 8 characters'),
    password_confirmation: z.string(),
  })
  .refine((data) => data.password === data.password_confirmation, {
    path: ['password_confirmation'],
    message: 'Passwords do not match',
  });

export type ResetPasswordData = z.infer<typeof resetPasswordSchema>;
