import { appendResponseHeader } from 'h3';

export const useAPI = () => {
  const config = useRuntimeConfig();

  const requestHeaders = import.meta.server
    ? useRequestHeaders(['cookie'])
    : {};

  const event = import.meta.server ? useRequestEvent() : null;

  return $fetch.create({
    baseURL: config.public.apiUrl,
    credentials: 'include',
    headers: {
      Accept: 'application/json',
      Referer: config.public.appUrl,
      ...requestHeaders,
    },
    onRequest({ options }) {
      if (import.meta.client) {
        const xsrfToken = useCookie('XSRF-TOKEN').value;

        if (xsrfToken) {
          const headers = new Headers(options.headers);

          headers.set('X-XSRF-TOKEN', xsrfToken);

          options.headers = headers;
        }
      }
    },
    onResponse({ response }) {
      if (import.meta.server && event) {
        for (const cookie of response.headers.getSetCookie()) {
          appendResponseHeader(event, 'set-cookie', cookie);
        }
      }
    },
  });
};
