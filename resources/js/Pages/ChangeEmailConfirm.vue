<template>
  <Master>
    <div class="box">
      <div class="columns">
        <div class="column">
          <h1 class="title is-4 has-text-centered">Confirm Code</h1>
        </div>
      </div>
      <div class="columns">
        <div class="column">
          <form @submit.prevent="verifycode">
            <div class="field">
              <label class="label">Please write the code sent to mail</label>
              <div class="control">
                <input
                  v-model="code"
                  class="input"
                  type="text"
                  :class="{ 'is-danger': errors.code }"
                />
                <span class="has-text-danger" v-show="errors.code">
                  {{ errors.code }}
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
                Verify Code
              </button>
              <inertia-link
                :href="`/change/email`"
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
  name: "ChangeEmailConfirm",
  components: {
    Master,
  },
  props: {
    errors: Object,
    email: String,
  },
  data() {
    return {
      code: "",
      loading: false,
    };
  },
  methods: {
    verifycode() {
      this.loading = true;
      const data = new FormData();
      data.append("code", this.code);
      data.append("email", this.$page.props.email);
      this.$inertia.post("/change/email/verify", data, {
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