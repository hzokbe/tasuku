<script lang="ts" setup>
import type { PageFeatureProps } from '@nuxt/ui/components/PageFeature.vue';
import type { ButtonProps } from '@nuxt/ui/components/Button.vue';

const features: PageFeatureProps[] = [
  {
    title: 'Organized by lists',
    description:
      'Group related tasks into lists and keep every project in its place.',
    icon: 'i-lucide-list-checks',
  },
  {
    title: 'Labeled with tags',
    description:
      'Categorize your tasks with tags and find them again in seconds.',
    icon: 'i-lucide-tag',
  },
  {
    title: 'Find anything fast',
    description: 'Search by title and filter by priority, status or tag.',
    icon: 'i-lucide-search',
  },
];

const links: ButtonProps[] = [
  {
    label: 'Sign-up',
    to: '/sign-up',
    trailingIcon: 'i-lucide-arrow-right',
  },
  {
    label: 'Sign-in',
    to: '/sign-in',
    color: 'neutral',
    variant: 'subtle',
  },
];

definePageMeta({
  middleware: ['lists-redirect'],
});
</script>

<template>
  <UPageHero
    description="Tasuku helps you group tasks into lists, label them with tags and track what is done, all in one place."
    title="Organize your tasks with clarity"
  ></UPageHero>
  <UPageSection
    :features="features"
    description="Simple tools to plan, categorize and complete your work."
    orientation="horizontal"
    title="Everything you need to stay on track"
  >
  </UPageSection>
  <UPageCTA
    :links="links"
    description="Create your free account and start turning your to-dos into done."
    title="Ready to get organized?"
  ></UPageCTA>
</template>
