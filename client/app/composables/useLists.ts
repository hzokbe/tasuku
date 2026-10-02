import type { List } from '~/types/list.ts';

export const useLists = () => {
  const api = useAPI();

  const lists = ref<List[]>([]);

  const fetchLists = async () => {
    try {
      lists.value = await api<List[]>('/api/lists');
    } catch (error: any) {
      error.value = error as Error;
    }
  };

  return { lists, fetchLists };
};
