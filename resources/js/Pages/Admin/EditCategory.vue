<template>
  <Master>
    <h1 class="title is-4 has-text-centered">Edit Category</h1>
    <form @submit.prevent="edit">
      <div class="field">
        <label class="label">Edit category</label>
        <div class="control">
          <input
            v-model="name"
            class="input"
            type="text"
            :class="{ 'is-danger': errors.name }"
          />
          <span class="has-text-danger" v-show="errors.name">
            {{ errors.name }}
          </span>
        </div>
      </div>
      <div class="field is-grouped">
        <div class="control">
          <button
            type="submit"
            class="button is-dark"
            :class="{ 'is-loading': loading }"
          >
            Edit
          </button>
          <button type="button" class="button is-light ml-5">
            <inertia-link href="/category" class="has-text-black"> Back </inertia-link>
          </button>
        </div>
      </div>
    </form>
    <div class="notification is-primary mt-2" v-show="$page.props.success">
      <button class="delete" @click="close"></button>
      {{ $page.props.success }}
    </div>
  </Master>
</template>
<script>
import Master from "../Layout/Master.vue";
export default {
  name: "EditCategory",
  components: {
    Master,
  },
  props: {
    errors: Object,
    category: Object,
  },
  data() {
    return {
      name: "",
      loading: false,
      category: "",
    };
  },
  created(){
    this.name = this.$page.props.category.name;
    this.category = this.$page.props.category;
  },
  methods: {
    edit() {
      this.$inertia.post(
        "/edit/category",
        {
          name: this.name,
          slug: this.$page.props.category.slug,
        },
        {
          onStart: () => {
            this.loading = true;
          },
          onFinish: () => {
            this.loading = false;
          },
          onSuccess: () => {
            this.name = "";
            this.loading = false;
          },
        }
      );
    },
    close() {
      this.$page.props.success = null;
    },
  },
};
</script>