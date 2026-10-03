import type { List } from '~/types/list.ts';
import type { ListData } from '~/utils/schemas/schemas.ts';

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

  const createList = async (payload: ListData) => {
    return await api<List>('/api/lists', { method: 'POST', body: payload });
  };

  return { lists, fetchLists, createList };
};
