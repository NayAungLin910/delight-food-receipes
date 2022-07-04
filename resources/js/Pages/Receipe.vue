<template>
  <Master>
    <div>
      <h1 class="title is-4 has-text-centered">{{ receipe.name }}</h1>
      <div class="columns">
        <div class="column has-text-centered">
          <img :src="receipe.image" width="400" />
        </div>
      </div>
      <div class="columns">
        <div class="column">
          <div class="" v-html="receipe.description"></div>
        </div>
      </div>
      <div class="columns is-gapless is-multiline">
        <div class="column is-2">
          <span class="tag is-warning is-large"
            >{{ receipe.step.length }} steps
          </span>
        </div>
        <div
          class="column is-1 ml-2 mr-4 mt-3"
          v-for="c in receipe.category"
          :key="c.id"
        >
          <inertia-link
            :href="`/view/category/receipe/${c.slug}`"
            class="tag is-info is-normal"
            >{{ c.name }}
          </inertia-link>
        </div>
      </div>
      <div class="" v-for="(s, index) in receipe.step" :key="s.id">
        <div class="columns">
          <div class="column">
            <h1 class="title is-5 has-text-centered">
              Step-{{ index + 1 }}: {{ s.name }}
            </h1>
          </div>
        </div>
        <div class="columns">
          <div class="column">
            <div class="" v-html="s.description"></div>
          </div>
        </div>
        <div class="columnns">
          <div class="column has-text-centered">
            <img :src="s.image" width="300" />
          </div>
        </div>
      </div>
      <br />
      <div class="columns">
        <div class="column">
          <div class="columns">
            <div class="column">
              <h1 class="title is-6">Author</h1>
            </div>
          </div>
          <div class="columns">
            <div class="column is-2">
              <img :src="`${receipe.user.image}`" width="100" alt="" />
            </div>
            <div class="column is-2">
              {{ receipe.user.name }}
            </div>
            <div class="column is-2">
              <inertia-link :href="`/view/author/receipe/${receipe.user.id}`">
                <span class="tag is-info">
                  <i class="fa-solid fa-book mr-1"></i>
                  Receipes
                </span>
              </inertia-link>
            </div>
          </div>
        </div>
      </div>
      <div class="columns" v-show="isModOrAdmin">
        <div class="column">
          <inertia-link
            class="button is-success"
            :href="`/edit/receipe/${receipe.slug}`"
          >
            <i class="fa-solid fa-pen-to-square mr-1"></i>
            Edit Receipe
          </inertia-link>
        </div>
      </div>
    </div>
  </Master>
</template>
<script>
import Master from "./Layout/Master.vue";
export default {
  name: "Receipe",
  components: {
    Master,
  },
  props: {
    receipe: Object,
  },
  computed: {
    isModOrAdmin() {
      if (this.$page.props.auth !== null) {
        if (
          this.$page.props.auth.role == "1" ||
          this.$page.props.auth.role == "2"
        ) {
          return true;
        } else {
          return false;
        }
      }
    },
  },
};
</script>