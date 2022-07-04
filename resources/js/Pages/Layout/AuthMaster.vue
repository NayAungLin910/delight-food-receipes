<template>
  <div class="container mt-5">
    <div class="columns">
      <div class="column is-1 pl-5 has-text-centered">
        <inertia-link href="/">
          <img :src="`/image/delight_icon.png`" alt="" width="65" />
        </inertia-link>
      </div>
      <div class="column is-11 mt-3 ml-2">
        <nav class="breadcrumb is-medium" aria-label="breadcrumbs">
          <ul>
            <li>
              <inertia-link href="/" class="has-text-dark"> Home </inertia-link>
            </li>
            <li>
              <inertia-link href="/login" class="has-text-dark">
                <span class="icon">
                  <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </span>
                Login
              </inertia-link>
            </li>
            <li>
              <inertia-link href="/register" class="has-text-dark">
                <span class="icon">
                  <i class="fa-solid fa-user"></i>
                </span>
                Register
              </inertia-link>
            </li>
          </ul>
        </nav>
      </div>
    </div>
    <div class="columns">
      <div class="column is-4"></div>
      <div class="column is-4">
        <slot />
      </div>
      <div class="column is-4"></div>
    </div>
  </div>
</template>
<script>
import { useToast } from "vue-toastification";
export default {
  name: "AuthMaster",
  created() {
    const toast = useToast();
    if (this.$page.props.success) {
      toast.success(this.$page.props.success, {
        timeout: 2500,
      });
    }
    if (this.$page.props.info) {
      toast.info(this.$page.props.info, {
        timeout: 2500,
      });
    }
    if (this.$page.props.error) {
      toast.error(this.$page.props.error, {
        timeout: 2500,
      });
    }
  },
};
</script>