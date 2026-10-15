export const useSignOut = () => {
  const api = useAPI();

  const user = useAuthUser();

  const signOut = async () => {
    try {
      await api('/sign-out', { method: 'POST' });
    } catch (e) {
      console.error(e);
    } finally {
      user.value = null;
    }
  };

  return { signOut };
};
