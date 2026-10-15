export const useAuthUser = () =>
  useState<UserResponse | null>('auth-user', () => null);
