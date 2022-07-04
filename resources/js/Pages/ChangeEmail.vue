<template>
  <Master>
    <div class="box">
      <div class="columns">
        <div class="column">
          <h1 class="title is-4 has-text-centered">Change Email</h1>
        </div>
      </div>
      <div
        class="notification is-danger has-text-centered"
        v-show="this.$page.props.errors.code"
      >
        <button class="delete" @click="delNoti"></button>
        {{ this.$page.props.errors.code }}
      </div>
      <div class="columns">
        <div class="column">
          <form @submit.prevent="sendcode">
            <div class="field">
              <label class="label">New Email</label>
              <div class="control">
                <input
                  v-model="email"
                  class="input"
                  type="email"
                  :class="{ 'is-danger': errors.email }"
                />
                <span class="has-text-danger" v-show="errors.email">
                  {{ errors.email }}
                </span>
              </div>
            </div>
            <div class="field">
              <button
                type="submit"
                class="button is-success mr-5"
                :class="{ 'is-loading': loading }"
              >
                <i class="fa-solid fa-angle-right mr-1"></i>
                Send Code
              </button>
              <inertia-link
                :href="`/profile`"
                type="button"
                class="button is-dark"
              >
                <i class="fa-solid fa-angle-left mr-1"></i>
                Back
              </inertia-link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </Master>
</template>
<script>
import Master from "./Layout/Master.vue";
export default {
  name: "ChangeEmail",
  components: {
    Master,
  },
  props: {
    errors: Object,
  },
  data() {
    return {
      email: "",
      loading: false,
    };
  },
  methods: {
    delNoti() {
      if (this.$page.props.errors.code) {
        this.$page.props.errors.code = null;
      }
    },
    sendcode() {
      this.loading = true;
      const data = new FormData();
      data.append("email", this.email);
      this.$inertia.post("/change/email/", data, {
        onStart: () => {
          this.loading = true;
        },
        onFinish: () => {
          this.loading = false;
        },
        onSuccess: () => {
          this.loading = false;
        },
      });
    },
  },
};
</script>