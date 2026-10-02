export const useUser = () => useState<User | null>('user', () => null);

export interface User {
  id: string;
}
