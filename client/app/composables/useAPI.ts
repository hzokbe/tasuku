export const useAPI = () => {
  const config = useRuntimeConfig();

  return $fetch.create({
    baseURL: config.public.apiUrl,
    credentials: 'include',
    headers: {
      Accept: 'application/json',
    },
    onRequest({ options }) {
      const xsrfToken = useCookie('XSRF-TOKEN').value;

      if (xsrfToken) {
        const headers = new Headers(options.headers);

        headers.set('X-XSRF-TOKEN', xsrfToken);

        options.headers = headers;
      }
    },
  });
};
