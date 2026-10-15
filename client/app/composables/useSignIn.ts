import { FetchError } from 'ofetch';

export const useSignIn = () => {
  const api = useAPI();

  const toast = useToast();

  const signIn = async (email: string, password: string) => {
    try {
      await api('/sign-in', {
        method: 'POST',
        body: {
          email: email,
          password: password,
        },
      });

      return true;
    } catch (e) {
      console.error(e);

      if (e instanceof FetchError && e.status != 502) {
        toast.add({
          id: 'sign-in-error',
          title: 'Sign in failed',
          description: e.data.message,
          color: 'error',
          icon: 'i-lucide-circle-x',
        });
      }
    }

    return false;
  };

  return { signIn };
};
