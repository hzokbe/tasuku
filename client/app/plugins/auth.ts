export default defineNuxtPlugin(async () => {
  const user = useUser();

  if (!user.value) {
    await useAuth().fetchUser();
  }
});
