import type {
  ResetPasswordData,
  SignInData,
  SignUpData,
} from '~/utils/schemas/schemas.ts';

export const useAuth = () => {
  const api = useAPI();

  const user = useUser();

  const csrf = () => api('/sanctum/csrf-cookie');

  const signUp = async (payload: SignUpData) => {
    await csrf();

    await api('/api/sign-up', { method: 'POST', body: payload });

    return fetchUser();
  };

  const signIn = async (payload: SignInData) => {
    await csrf();

    await api('/api/sign-in', { method: 'POST', body: payload });

    return fetchUser();
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

  const fetchUser = async () => {
    try {
      user.value = await api<User>('/api/me');
    } catch (error) {
      user.value = null;
    }

    return user.value;
  };

  const signOut = async () => {
    try {
      await api('/api/sign-out', { method: 'POST' });
    } finally {
      user.value = null;

      await navigateTo('/sign-in');
    }
  };

  return {
    signUp,
    signIn,
    sendResetPasswordLink,
    resetPassword,
    fetchUser,
    signOut,
  };
};
