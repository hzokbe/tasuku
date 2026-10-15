export const useAPI = () => {
  const config = useRuntimeConfig();

  const headers = useRequestHeaders(['cookie']);

  return $fetch.create({
    baseURL: config.public.apiURL,
    headers: {
      ...headers,
      Accept: 'application/json',
    },
    retry: 0,
  });
};
