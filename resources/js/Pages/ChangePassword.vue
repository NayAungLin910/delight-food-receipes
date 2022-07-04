<template>
  <Master>
    <div class="box">
      <h1 class="title is-4 has-text-centered">Change Password</h1>
      <div class="columns">
        <div class="column">
          <form @submit.prevent="change">
            <div class="field">
              <label class="label">Current Password</label>
              <div class="control">
                <input
                  class="input"
                  type="password"
                  v-model="cur_password"
                  :class="{ 'is-danger': errors.cur_password }"
                />
              </div>
              <span class="has-text-danger" v-show="errors.cur_password">
                {{ errors.cur_password }}
              </span>
            </div>
            <div class="field">
              <label class="label">New Password</label>
              <div class="control">
                <input
                  class="input"
                  type="password"
                  v-model="new_password"
                  :class="{ 'is-danger': errors.new_password }"
                />
              </div>
              <span class="has-text-danger" v-show="errors.new_password">
                {{ errors.new_password }}
              </span>
            </div>
            <div class="field">
              <label class="label">Confirm New Password</label>
              <div class="control">
                <input
                  class="input"
                  type="password"
                  v-model="confirm_new_password"
                  :class="{ 'is-danger': errors.confirm_new_password }"
                />
              </div>
              <span class="has-text-danger" v-show="errors.confirm_new_password">
                {{ errors.confirm_new_password }}
              </span>
            </div>
            <div class="field">
              <button
                type="submit"
                class="button is-success mr-5"
                :class="{ 'is-loading': loading }"
              >
                <i class="fa-solid fa-key mr-1"></i>
                Chanage
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
  name: "ChangePassword",
  components: {
    Master,
  },
  props: {
    errors: Object,
  },
  data() {
    return {
      cur_password: "",
      new_password: "",
      confirm_new_password: "",
      loading: false,
    };
  },
  methods: {
    change() {
      this.loading = true;
      const data = new FormData();
      data.append("cur_password", this.cur_password);
      data.append("new_password", this.new_password);
      data.append("confirm_new_password", this.confirm_new_password);
      this.$inertia.post("/profile/password", data, {
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