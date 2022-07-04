<template>
  <Master>
    <h1 class="title is-4 has-text-centered">Categories</h1>
    <div class="columns">
      <div class="column is-3">
        Start Date
        <input type="date" class="input" v-model="start_date" />
      </div>
      <div class="column is-3">
        End Date
        <input type="date" class="input" v-model="end_date" />
      </div>
      <div class="column is-4 mt-5">
        <input
          type="text"
          class="input"
          placeholder="Search"
          v-model="search"
        />
      </div>
      <div class="column is-2 mt-5">
        <button @click="searchFun" class="button is-small is-dark">
          Search
        </button>
      </div>
    </div>
    <div class="columns">
      <div class="column is-2"></div>
      <div class="column is-8">
        <table class="table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Receipes</th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in $page.props.category.data" :key="c.id">
              <td>{{ c.name }}</td>
              <td>
                <inertia-link
                  :href="`/view/category/receipe/${c.slug}`"
                  class="has-text-white"
                >
                  <span class="tag is-primary">
                    {{ c.receipe_count }}
                  </span>
                </inertia-link>
              </td>
              <td></td>
              <td v-show="isModOrAdmin">
                <span class="tag is-info">
                  <inertia-link
                    :href="`/edit/category/${c.slug}`"
                    class="has-text-white"
                  >
                    <i class="fa-solid fa-pen-to-square"></i>
                    Edit
                  </inertia-link>
                </span>
              </td>
              <td v-show="isModOrAdmin">
                <span class="tag is-danger">
                  <inertia-link
                    :href="`/delete/category/${c.slug}`"
                    class="has-text-white"
                  >
                    <i class="fa-solid fa-circle-minus"></i>
                    Delete
                  </inertia-link>
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="column is-2"></div>
    </div>
    <div class="columns">
      <div class="column">
        <Paginate :data="category.links"></Paginate>
      </div>
    </div>
  </Master>
</template>
<script>
import Paginate from "./Layout/Paginate.vue";
import Master from "./Layout/Master.vue";
export default {
  name: "Category",
  props: {
    category: Object,
    start_date: String,
    end_date: String,
  },
  data() {
    return {
      start_date: this.$page.props.start_date,
      end_date: this.$page.props.end_date,
      search: "",
    };
  },
  components: {
    Master,
    Paginate,
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
  methods: {
    searchFun() {
      if (this.search !== "") {
        if (
          this.start_date !== undefined &&
          this.end_date !== undefined &&
          this.start_date !== "" &&
          this.end_date !== ""
        ) {
          this.$inertia.get(
            `/date/category/search/${this.start_date}/${this.end_date}/${this.search}`
          );
        } else {
          this.$inertia.get(`/search/category/${this.search}`);
        }
      } else {
        if (
          this.start_date !== undefined &&
          this.end_date !== undefined &&
          this.start_date !== "" &&
          this.end_date !== ""
        ) {
          this.$inertia.get(`/category/${this.start_date}/${this.end_date}`);
        }
      }
    },
  },
};
</script>