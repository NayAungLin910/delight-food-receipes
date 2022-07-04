<template>
  <Master>
    <div class="box">
      <div class="columns">
        <div class="column has-text-centered">
          <img :src="`${$page.props.auth.image}`" width="200" alt="" />
        </div>
      </div>
      <div class="columns">
        <div class="column">
          <form @submit.prevent="edit">
            <div class="field">
              <label class="label">Username</label>
              <div class="control">
                <input
                  v-model="name"
                  class="input"
                  type="text"
                  placeholder="Text input"
                  :class="{ 'is-danger': errors.name }"
                />
                <span class="has-text-danger" v-show="errors.name">
                  {{ errors.name }}
                </span>
              </div>
            </div>
            <div class="field">
              <label class="label">Choose a new profile</label>
              <div class="file is-small is-dark has-name is-boxed">
                <label class="file-label">
                  <input
                    @change="chooseImage"
                    class="file-input"
                    type="file"
                    name="resume"
                  />
                  <span class="file-cta">
                    <span class="file-icon">
                      <i class="fas fa-upload"></i>
                    </span>
                    <span class="file-label"> Choose a new profile </span>
                  </span>
                  <span class="file-name">
                    {{ image.name }}
                  </span>
                </label>
              </div>
              <span class="has-text-danger" v-show="errors.image">
                {{ errors.image }}
              </span>
            </div>
            <div class="field">
              <button
                type="submit"
                class="button is-success mr-5"
                :class="{ 'is-loading': loading }"
              >
                <i class="fa-solid fa-floppy-disk mr-1"></i>
                Save
              </button>
              <inertia-link :href="`/profile`" class="button is-dark">
                <i class="fa-solid fa-angle-left"></i>
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
  name: "EditProfile",
  components: { Master },
  props: {
    errors: Object,
  },
  data() {
    return {
      name: "",
      image: "",
      loading: false,
    };
  },
  created() {
    this.name = this.$page.props.auth.name;
  },
  methods: {
    chooseImage(e) {
      this.image = e.target.files[0];
    },
    edit() {
      this.loading = true;

      const data = new FormData();
      data.append("name", this.name);
      data.append("image", this.image);

      this.$inertia.post("/edit/profile", data, {
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