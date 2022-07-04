<template>
  <Master>
    <div class="box">
      <h1 class="title is-4 has-text-centered">Confirm Delete</h1>
      <p class="has-text-centered is-size-5">
        Are you sure about deleteting the receipe, "{{ receipe.name }}"
      </p>
      <div class="columns mt-4">
        <div class="column has-text-right">
          <button class="button is-success" :class="{ 'is-loading': loading }" @click="delRec">
            <i class="fa-solid fa-circle-check mr-1"></i>
            Confirm
          </button>
        </div>
        <div class="column">
          <inertia-link
            class="button is-dark"
            :href="`/edit/receipe/${receipe.slug}`"
          >
            <i class="fa-solid fa-angle-left mr-1"></i>
            Cancel
          </inertia-link>
        </div>
      </div>
    </div>
  </Master>
</template>
<script>
import Master from "../Layout/Master.vue";
export default {
  name: "ConfirmDeleteReceipe",
  components: {
    Master,
  },
  props: {
    receipe: Object,
  },
  data() {
    return {
      loading: false,
    };
  },
  methods: {
    delRec() {
      const data = new FormData();
      data.append("receipe_slug", this.receipe.slug);
      this.$inertia.post("/delete/receipe/", data, {
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