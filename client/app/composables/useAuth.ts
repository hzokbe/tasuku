import type {
  ResetPasswordData,
  SignInData,
  SignUpData,
} from '~/utils/schemas/schemas.ts';

export const useAuth = () => {
  const api = useAPI();

  const csrf = () => api('/sanctum/csrf-cookie');

  const signUp = async (payload: SignUpData) => {
    await csrf();

    return api('/api/sign-up', { method: 'POST', body: payload });
  };

  const signIn = async (payload: SignInData) => {
    await csrf();

    return api('/api/sign-in', { method: 'POST', body: payload });
  };

  const sendResetPasswordLink = async (email: string) => {
    await csrf();

    return api('/api/recover-password', {
      method: 'POST',
      body: {
        email,
      },
    });
  };

  const resetPassword = async (token: string, data: ResetPasswordData) => {
    await csrf();

    return api(`/api/reset-password/${token}`, {
      method: 'POST',
      body: data,
    });
  };

  return { signUp, signIn, sendResetPasswordLink, resetPassword };
};
