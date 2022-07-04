<template>
  <div>
    <nav
      class="pagination is-rounded"
      role="navigation"
      aria-label="pagination"
    >
      <inertia-link class="pagination-previous" :href="data[0].url" :class="{ 'is-disabled': data[0].url == null }"
        >Previous</inertia-link
      >
      <inertia-link class="pagination-next" :href="data[data.length - 1].url" :class="{ 'is-disabled': data[data.length-1].url == null }"
        >Next page</inertia-link
      >
      <ul class="pagination-list">
        <li
          v-for="(d, index) in data"
          :key="index"
        >
          <inertia-link
            class="pagination-link"
            :href="d.url"
            v-show="index !== 0 && index !== data.length - 1"
            :class="{ 'is-disabled': d.url == null, 'is-current': d.active }"
          >
            {{ d.label }}
          </inertia-link>
        </li>
      </ul>
    </nav>
  </div>
</template>
<script>
export default {
  name: "Paginate",
  props: {
    data: Array,
  },
};
</script>