import type { SignUpData } from '~/utils/schemas/schemas.ts';

export const useAuth = () => {
  const api = useAPI();

  const csrf = () => api('/sanctum/csrf-cookie');

  const signUp = async (payload: SignUpData) => {
    await csrf();

    return api('/api/sign-up', { method: 'POST', body: payload });
  };

  return { signUp };
};
