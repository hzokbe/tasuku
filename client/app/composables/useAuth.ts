export const useAuth = () => {
  const api = useAPI();

  const user = useAuthUser();

  const isAuthenticated = computed(() => user.value != null);

  const fetchUser = async () => {
    try {
      user.value = await api<UserResponse>('/me');
    } catch {
      user.value = null;
    }

    return user.value;
  };

  const clearUser = () => {
    user.value = null;
  };

  return { user, isAuthenticated, fetchUser, clearUser };
};
