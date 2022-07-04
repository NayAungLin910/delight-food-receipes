<template>
  <Master>
    <div class="box">
      <div class="columns">
        <div class="column has-text-centered">
          <img :src="`${this.$page.props.auth.image}`" width="170" alt="" />
          <h1 class="title is-3">{{ this.$page.props.auth.name }}</h1>
          <p class="is-size-6">{{ this.$page.props.auth.email }}</p>
        </div>
        <div class="column">
          <table class="table">
            <tbody>
              <tr>
                <td>
                  <span class="tag is-link">
                    <i class="fa-solid fa-people-group mr-1"></i>
                    <h1 class="title is-5 has-text-white">Role</h1>
                  </span>
                </td>
                <td>
                  <span class="tag is-light">
                    <h1 class="title is-5">{{ role }}</h1>
                  </span>
                </td>
              </tr>
              <tr>
                <td>
                  <inertia-link
                    :href="`/view/favourtie/receipe`"
                    class="has-text-white"
                  >
                    <span class="tag is-danger">
                      <i class="fa-solid fa-heart mr-1"></i>
                      <h1 class="title is-5 has-text-white">Favourites</h1>
                    </span>
                  </inertia-link>
                </td>
                <td>
                  <inertia-link
                    :href="`/view/favourtie/receipe`"
                    class="has-text-black"
                  >
                    <span class="tag is-light">
                      <h1 class="title is-5">
                        {{ this.$page.props.favourite.length }}
                      </h1>
                    </span>
                  </inertia-link>
                </td>
              </tr>
              <tr v-show="isModOrAdmin">
                <td>
                  <inertia-link
                    :href="`/view/own/receipe`"
                    class="has-text-white"
                  >
                    <span class="tag is-info">
                      <i class="fa-solid fa-book mr-1"></i>
                      <h1 class="title is-5 has-text-white">Receipes</h1>
                    </span>
                  </inertia-link>
                </td>
                <td>
                  <inertia-link
                    :href="`/veiw/own/receipe`"
                    class="has-text-black"
                  >
                    <span class="tag is-light">
                      <h1 class="title is-5">
                        {{ this.$page.props.my_receipe.length }}
                      </h1>
                    </span>
                  </inertia-link>
                </td>
              </tr>
              <tr>
                <td>
                  <inertia-link :href="`/edit/profile/`" class="has-text-white">
                    <span class="tag is-dark">
                      <i class="fa-solid fa-pen-to-square mr-1"></i>
                      <h1 class="title is-6 has-text-white">Edit Profile</h1>
                    </span>
                  </inertia-link>
                </td>
              </tr>
              <tr>
                <td>
                  <inertia-link
                    :href="`/profile/password`"
                    class="has-text-white"
                  >
                    <span class="tag is-dark">
                      <i class="fa-solid fa-key mr-1"></i>
                      <h1 class="title is-6 has-text-white">Change Password</h1>
                    </span>
                  </inertia-link>
                </td>
              </tr>
              <tr>
                <td>
                  <inertia-link
                    :href="`/change/email`"
                    class="has-text-white"
                  >
                    <span class="tag is-dark">
                      <i class="fa-solid fa-envelope mr-1"></i>
                      <h1 class="title is-6 has-text-white">Change Email</h1>
                    </span>
                  </inertia-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div
      class="box"
      v-if="this.$page.props.auth && this.$page.props.auth.role == 2"
    >
      <div class="columns">
        <div class="column">
          <h1 class="title is-5 has-text-centered">Search Users By Email</h1>
        </div>
      </div>
      <div class="columns">
        <div class="column is-offset-3">
          <div class="field has-addons">
            <div class="control">
              <input
                class="input"
                type="text"
                placeholder="Find user by email"
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
      <div class="columns" v-if="searched_users !== undefined">
        <div class="column is-3 has-text-centered">
          <img :src="`${searched_users.image}`" width="100" alt="" />
          <h1 class="title is-5">{{ searched_users.name }}</h1>
        </div>
        <div class="column is-2 mt-4">
          <span class="tag is-link">{{ searched_users_role }}</span>
        </div>
        <div
          class="column is-2 mt-4"
          v-if="searched_users !== undefined && searched_users.role !== '0'"
        >
          <inertia-link
            :href="`/view/searchuser/receipe/${searched_users.id}'`"
          >
            <span class="tag is-info"
              >{{ searched_users.receipe.length }} receipe(s)</span
            >
          </inertia-link>
        </div>
        <div
          class="column is-2 mt-4"
          v-if="searched_users !== undefined && searched_users.role == '0'"
        >
          <inertia-link :href="`/make/moderator/${searched_users.id}'`">
            <span class="tag is-success">Give Moderator role</span>
          </inertia-link>
        </div>
        <div
          class="column is-2 mt-4"
          v-if="searched_users !== undefined && searched_users.role == '1'"
        >
          <inertia-link :href="`/make/user/${searched_users.id}'`">
            <span class="tag is-warning">Give User role</span>
          </inertia-link>
        </div>
      </div>
    </div>
  </Master>
</template>
<script>
import Master from "./Layout/Master.vue";
export default {
  name: "Profile",
  components: {
    Master,
  },
  props: {
    searched_users: Object,
    search: String,
  },
  created() {
    if (this.$page.props.auth !== null) {
      if (this.$page.props.auth.role == "2") {
        this.role = "Admin";
      } else if (this.$page.props.auth.role == "1") {
        this.role = "Moderator";
      } else if (this.$page.props.auth.role == "0") {
        this.role = "User";
      }
    }
    if (this.$page.props.searched_users !== undefined) {
      if (this.$page.props.searched_users.role == "2") {
        this.searched_users_role = "Admin";
      } else if (this.$page.props.searched_users.role == "1") {
        this.searched_users_role = "Moderator";
      } else if (this.$page.props.searched_users.role == "0") {
        this.searched_users_role = "User";
      }
    }
    this.search = this.$page.props.search;
  },
  data() {
    return {
      role: "",
      search: "",
      searched_users_role: "",
    };
  },
  methods: {
    searchWord() {
      if (this.search !== "" && this.search !== undefined) {
        this.$inertia.get(`/user/search/${this.search}`);
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
    isUser() {
      if (this.$page.props.searched_users !== undefined) {
        if (this.$page.props.searched_users.role == 0) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    },
  },
};
</script>