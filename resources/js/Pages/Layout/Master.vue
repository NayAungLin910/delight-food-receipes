<template>
  <div class="container mt-5 pl-2">
    <div class="columns">
      <div class="column is-1 pl-5 has-text-centered">
        <inertia-link href="/">
          <img :src="`/image/delight_icon.png`" alt="" width="65" />
        </inertia-link>
      </div>
      <div class="column is-11 mt-3 ml-2 has-text-centered">
        <nav class="breadcrumb is-medium" aria-label="breadcrumbs">
          <ul>
            <li>
              <inertia-link href="/" class="has-text-dark mt-1">
                <i class="fa-solid fa-house mr-1"></i>
                Home
              </inertia-link>
            </li>
            <li>
              <div
                class="dropdown ml-3 mr-3"
                :class="{ 'is-active': clickCategory }"
                @click="clickFunCategory"
              >
                <div class="dropdown-trigger">
                  <button
                    class="button"
                    aria-haspopup="true"
                    aria-controls="dropdown-menu"
                  >
                    <span class="icon">
                      <i class="fa-solid fa-drumstick-bite"></i>
                    </span>
                    <span>Category</span>
                    <span class="icon is-small">
                      <i class="fas fa-angle-down" aria-hidden="true"></i>
                    </span>
                  </button>
                </div>
                <div class="dropdown-menu" id="dropdown-menu" role="menu">
                  <div class="dropdown-content">
                    <div v-show="isModOrAdmin">
                      <inertia-link
                        href="/create/category"
                        class="dropdown-item has-text-black"
                      >
                        <i class="fa-solid fa-circle-plus mr-1"></i>
                        Create Category
                      </inertia-link>
                      <hr class="dropdown-divider" />
                    </div>
                    <inertia-link
                      class="dropdown-item has-text-black"
                      href="/category"
                    >
                      <i class="fa-solid fa-drumstick-bite mr-1"></i>
                      Categories
                    </inertia-link>
                  </div>
                </div>
              </div>
            </li>
            <li v-show="isModOrAdmin">
              <div
                class="dropdown ml-3 mr-3"
                :class="{ 'is-active': clickReceipe }"
                @click="clickFunReceipe"
              >
                <div class="dropdown-trigger">
                  <button
                    class="button"
                    aria-haspopup="true"
                    aria-controls="dropdown-menu"
                  >
                    <span class="icon">
                      <i class="fa-solid fa-book"></i>
                    </span>
                    <span>Receipe</span>
                    <span class="icon is-small">
                      <i class="fas fa-angle-down" aria-hidden="true"></i>
                    </span>
                  </button>
                </div>
                <div class="dropdown-menu" id="dropdown-menu" role="menu">
                  <div class="dropdown-content">
                    <div v-show="isModOrAdmin">
                      <inertia-link
                        href="/create/receipe"
                        class="dropdown-item has-text-black"
                      >
                        <i class="fa-solid fa-circle-plus mr-1"></i>
                        Create Receipe
                      </inertia-link>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li v-if="$page.props.auth">
              <div
                class="dropdown ml-3 mr-3"
                :class="{ 'is-active': clickProfile }"
                @click="clickFunProfile"
              >
                <div class="dropdown-trigger">
                  <button
                    class="button"
                    aria-haspopup="true"
                    aria-controls="dropdown-menu2"
                  >
                    <img :src="`${$page.props.auth.image}`" width="60" alt="" />
                    <span class="icon is-small">
                      <i class="fas fa-angle-down" aria-hidden="true"></i>
                    </span>
                  </button>
                </div>
                <div class="dropdown-menu" id="dropdown-menu2" role="menu">
                  <div class="dropdown-content">
                    <inertia-link
                      href="/profile"
                      class="dropdown-item has-text-black"
                    >
                      <span class="icon">
                        <i class="fa-solid fa-address-card"></i>
                      </span>
                      Profile
                    </inertia-link>
                    <hr class="dropdown-divider" />
                    <inertia-link
                      href="/logout"
                      class="dropdown-item has-text-black"
                    >
                      <span class="icon">
                        <i class="fa-solid fa-circle-xmark"></i>
                      </span>
                      Logout
                    </inertia-link>
                  </div>
                </div>
              </div>
            </li>
            <li v-show="!$page.props.auth">
              <inertia-link href="/login" class="has-text-dark">
                <span class="icon">
                  <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </span>
                Login
              </inertia-link>
            </li>
            <li v-show="!$page.props.auth">
              <inertia-link href="/register" class="has-text-dark">
                <span class="icon">
                  <i class="fa-solid fa-user"></i>
                </span>
                Register
              </inertia-link>
            </li>
          </ul>
        </nav>
      </div>
    </div>
    <div class="columns">
      <div class="column is-3"></div>
      <div class="column is-6">
        <slot />
      </div>
      <div class="column is-3"></div>
    </div>
  </div>
</template>
<script>
import { useToast } from "vue-toastification";
export default {
  name: "Master",
  created() {
    const toast = useToast();
    if (this.$page.props.success) {
      toast.success(this.$page.props.success, {
        timeout: 2500,
      });
    }
    if (this.$page.props.info) {
      toast.info(this.$page.props.info, {
        timeout: 2500,
      });
    }
    if (this.$page.props.error) {
      toast.error(this.$page.props.error, {
        timeout: 2500,
      });
    }
  },
  data() {
    return {
      clickProfile: false,
      clickCategory: false,
      clickReceipe: false,
    };
  },
  methods: {
    clickFunProfile() {
      if (this.clickProfile == true) {
        this.clickProfile = false;
      } else {
        this.clickProfile = true;
      }
    },
    clickFunCategory() {
      if (this.clickCategory == true) {
        this.clickCategory = false;
      } else {
        this.clickCategory = true;
      }
    },
    clickFunReceipe() {
      if (this.clickReceipe == true) {
        this.clickReceipe = false;
      } else {
        this.clickReceipe = true;
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
  },
};
</script>