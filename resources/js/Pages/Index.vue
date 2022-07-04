<template>
  <Master>
    <div class="columns">
      <div class="column is-offset-3">
        <div class="field has-addons">
          <div class="control">
            <input
              class="input"
              type="text"
              placeholder="Find a receipe"
              v-model="search"
            />
          </div>
          <div class="control">
            <a @click="searchWord" class="button is-info">
              <i class="fa-solid fa-magnifying-glass"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-4" v-for="r in receipe.data" :key="r.id">
        <div class="card">
          <img :src="`${r.image}`" />
          <p class="has-text-weight-bold-semibold has-text-centered">{{ r.name }}</p>
          <p class="has-text-centered">
            <span class="tag is-danger">
              <span class="has-text-weight-bold mr-1">{{
                r.favourite.length
              }}</span>
              <i class="fa-solid fa-heart"></i>
          </span>
          </p>
          
          <div class="has-text-centered mb-1"></div>
          <footer class="card-footer">
            <inertia-link
              class="button card-footer-item is-light is-small"
              :href="`/view/receipe/${r.slug}`"
            >
              <i class="fa-solid fa-eye"></i>
            </inertia-link>
            <inertia-link
              v-show="isLogin"
              :href="`/index/like/${r.id}`"
              class="button card-footer-item is-light is-small"
              :class="{ 'has-text-danger': isFavourite(r.id) }"
            >
              <i class="fa-solid fa-heart"></i>
            </inertia-link>
            <inertia-link
              :href="`/edit/receipe/${r.slug}`"
              class="button card-footer-item is-success is-light is-small"
              v-show="isModOrAdmin"
            >
              <i class="fa-solid fa-file-pen"></i>
            </inertia-link>
          </footer>
        </div>
      </div>
    </div>
    <div class="columns">
      <div class="column">
        <Paginate :data="receipe.links"></Paginate>
      </div>
    </div>
  </Master>
</template>
<script>
import Master from "./Layout/Master.vue";
import Paginate from "./Layout/Paginate.vue";
export default {
  name: "Index",
  components: { Master, Paginate },
  props: {
    receipe: Object,
    keyword: String,
  },
  data() {
    return {
      search: this.$page.props.keyword,
    };
  },
  methods: {
    searchWord() {
      this.$inertia.get(`/index/search/${this.search}`);
    },
    isFavourite($id) {
      if (this.$page.props.favourite !== null) {
        for (let i = 0; i < this.$page.props.favourite.length; i++) {
          if (this.$page.props.favourite[i]["receipe"]) {
            if (this.$page.props.favourite[i]["receipe"]["id"] == $id) {
              return true;
            }
          }
        }
        return false;
      }
    },
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
    isLogin() {
      if (this.$page.props.auth !== null) {
        return true;
      } else {
        return false;
      }
    },
  },
};
</script>