import type { SignInData, SignUpData } from '~/utils/schemas/schemas.ts';

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

  return { signUp, signIn };
};
