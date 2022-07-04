<template>
  <Master>
    <div>
      <h1 class="title is-4 has-text-centered">Edit Receipe</h1>
      <form @submit.prevent="edit">
        <div class="field">
          <div class="notification is-success" v-show="$page.props.success">
            <button
              class="delete"
              type="button"
              @click="closeSuccessNoti"
            ></button>
            {{ $page.props.success }}
          </div>
        </div>
        <div class="field">
          <div class="notification is-danger is-small" v-show="isError">
            Editing failed. Please check the error and select the new steps
            images again! Please reload the page again if the images are not
            selectable.
          </div>
        </div>
        <div class="field">
          <label class="label">Title</label>
          <div class="control">
            <input
              class="input"
              v-model="title"
              type="text"
              placeholder="Enter title"
              :class="{ 'is-danger': errors.title }"
            />
            <span class="has-text-danger" v-show="errors.title">
              {{ errors.title }}
            </span>
          </div>
        </div>
        <div class="field">
          <label class="label">Description</label>
          <div class="control">
            <vue-editor v-model="description" :editor-toolbar="customToolbar" />
          </div>
          <span class="has-text-danger" v-show="errors.description">
            {{ errors.description }}
          </span>
        </div>
        <div class="field">
          <div class="file is-dark has-name">
            <label class="file-label">
              <input
                class="file-input"
                type="file"
                name="changeMainImage"
                @change="chooseImage"
              />
              <span class="file-cta">
                <span class="file-icon">
                  <i class="fas fa-upload"></i>
                </span>
                <span class="file-label"> New Receipe image </span>
              </span>
              <span class="file-name">
                {{ changeMainImage.name }}
              </span>
            </label>
          </div>
          <span class="has-text-danger" v-show="errors.changeMainImage">
            {{ errors.changeMainImage }}
          </span>
        </div>
        <div class="field mt-4">
          <img :src="receipe.image" width="400" alt="" />
        </div>
        <div class="columns">
          <div class="column">
            <button
              @click="delCat(c.id)"
              class="button is-info is-small m-1"
              type="button"
              v-for="c in categories"
              :key="c.id"
            >
              <span class="icon">
                <i class="fa-solid fa-circle-minus"></i>
              </span>
              <span>
                {{ c.name }}
              </span>
            </button>
          </div>
        </div>
        <div class="field">
          <div class="select is-multiple">
            <label class="label">Edit Categories</label>
            <select multiple size="5" v-model="categories">
              <option
                v-for="c in $page.props.category"
                :key="c.id"
                :value="{
                  name: c.name,
                  id: c.id,
                }"
              >
                {{ c.name }}
              </option>
            </select>
          </div>
        </div>
        <span class="has-text-danger" v-show="errors.categories">
          {{ errors.categories }}
        </span>
        <div class="box" v-for="(step, index) in steps" :key="index">
          <div class="field">
            <h1 class="title is-5 has-text-centered">Step-{{ index + 1 }}</h1>
            <label class="label">Title</label>
            <div class="control">
              <input
                type="text"
                v-model="step.name"
                class="input"
                :class="{ 'is-danger': errors[`steps.${index}.name`] }"
              />
            </div>
            <span
              class="has-text-danger"
              v-show="errors[`steps.${index}.name`]"
            >
              {{ errors[`steps.${index}.name`] }}
            </span>
            <label class="label">Description</label>
            <div class="control">
              <vue-editor
                v-model="step.description"
                :editor-toolbar="customToolbar"
              />
            </div>
            <span
              class="has-text-danger"
              v-show="errors[`steps.${index}.description`]"
            >
              {{ errors[`steps.${index}.description`] }}
            </span>
            <div class="file is-dark has-name mt-2">
              <label class="file-label">
                <input
                  class="file-input"
                  type="file"
                  name="resume"
                  @change="chooseImageStep($event, index)"
                />
                <span class="file-cta">
                  <span class="file-icon">
                    <i class="fas fa-upload"></i>
                  </span>
                  <span class="file-label"
                    >Change step-{{ index + 1 }} image
                  </span>
                </span>
                <span class="file-name">
                  {{ changeImage[index].image.name }}
                </span>
              </label>
            </div>
            <span
              class="has-text-danger"
              v-show="errors[`changeImage.${index}.image`]"
            >
              {{ errors[`changeImage.${index}.image`] }}
            </span>
            <div class="field mt-4">
              <img :src="step.image" width="300" alt="" />
            </div>
            <div class="container mt-4">
              <div class="columns">
                <div class="column" v-show="index !== 0">
                  <button
                    type="button"
                    class="button is-danger"
                    @click="delStep(step.slug)"
                  >
                    <span class="icon">
                      <i class="fa-solid fa-circle-minus"></i>
                    </span>
                    <div class="container">Delete Step-{{ index + 1 }}</div>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="box" v-for="(new_step, index) in new_steps" :key="index">
          <div class="field">
            <h1 class="title is-5 has-text-centered">
              New Step-{{ index + 1 }}
            </h1>
            <label class="label">Title</label>
            <div class="control">
              <input
                type="text"
                v-model="new_step.name"
                class="input"
                :class="{ 'is-danger': errors[`new_steps.${index}.name`] }"
              />
            </div>
            <span
              class="has-text-danger"
              v-show="errors[`new_steps.${index}.name`]"
            >
              {{ errors[`new_steps.${index}.name`] }}
            </span>
            <label class="label">Description</label>
            <div class="control">
              <vue-editor
                v-model="new_step.desc"
                :editor-toolbar="customToolbar"
              />
            </div>
            <span
              class="has-text-danger"
              v-show="errors[`new_steps.${index}.desc`]"
            >
              {{ errors[`new_steps.${index}.desc`] }}
            </span>
            <div class="file is-dark has-name mt-2">
              <label class="file-label">
                <input
                  class="file-input"
                  type="file"
                  name="resume"
                  @change="chooseImageNewStep($event, index)"
                />
                <span class="file-cta">
                  <span class="file-icon">
                    <i class="fas fa-upload"></i>
                  </span>
                  <span class="file-label"
                    >New Step-{{ index + 1 }} image
                  </span>
                </span>
                <span class="file-name">
                  {{ new_step.image.name }}
                </span>
              </label>
            </div>
            <span
              class="has-text-danger"
              v-show="errors[`new_steps.${index}.image`]"
            >
              {{ errors[`new_steps.${index}.image`] }}
            </span>
            <div class="container mt-4">
              <div class="columns">
                <div class="column">
                  <button
                    type="button"
                    class="button is-danger"
                    @click="delNewTask(index)"
                  >
                    <span class="icon">
                      <i class="fa-solid fa-circle-minus"></i>
                    </span>
                    <div class="container">Delete New Step-{{ index + 1 }}</div>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="columns">
          <div class="column">
            <button type="button" class="button is-info" @click="addTask">
              <i class="fa-solid fa-circle-plus mr-2"></i>
              Add New Step
            </button>
          </div>
        </div>
        <div class="columns">
          <div class="column">
            <inertia-link class="button is-dark" :href="`/view/receipe/${receipe.slug}`">
              <i class="fa-solid fa-angle-left mr-2"></i>
              Back
            </inertia-link>
          </div>
          <div class="column">
            <button
              type="submit"
              class="button is-success"
              :class="{ 'is-loading': loading }"
            >
              <i class="fa-solid fa-floppy-disk mr-2"></i>
              Save Receipe
            </button>
          </div>
          <div class="column has-text-right">
            <inertia-link class="button is-danger" :href="`/delete/receipe/${receipe.slug}`">
              <i class="fa-solid fa-circle-minus mr-1"></i>
              Delete Receipe
            </inertia-link>
          </div>
        </div>
      </form>
    </div>
  </Master>
</template>
<script>
import Master from "../Layout/Master.vue";
import { VueEditor } from "vue3-editor";
export default {
  name: "EditReceipe",
  components: {
    Master,
    VueEditor,
  },
  props: {
    errors: Object,
    receipe: Object,
    steps: Object,
    changeImage: Object,
  },
  data() {
    return {
      title: "",
      description: "",
      mainImage: "",
      changeMainImage: "",
      categories: "",
      customToolbar: [
        ["bold", "italic", "underline", "blockquote"],
        [{ align: "" }, { align: "center" }, { align: "right" }],
        [{ list: "ordered" }, { list: "bullet" }, { list: "check" }],
        [{ background: [] }, { color: [] }],
        ["link"],
        ["strike"],
        ["clean"],
      ],
      steps: "",
      new_steps: [],
      loading: false,
    };
  },
  created() {
    this.title = this.$page.props.receipe.name;
    this.description = this.$page.props.receipe.description;
    this.mainImage = this.$page.props.receipe.image;
    this.categories = this.$page.props.receipe.category;
    this.steps = this.$page.props.steps;
  },
  methods: {
    delStep(slug) {
      this.$inertia.get(`/delete/step/${slug}`);
    },
    closeSuccessNoti() {
      this.$page.props.success = null;
    },
    delCat(id) {
      for (let i = 0; i < this.categories.length; i++) {
        if (this.categories[i]["id"] == id) {
          this.categories.splice(i, 1);
        }
      }
    },
    chooseImage(e) {
      this.changeMainImage = e.target.files[0];
    },
    chooseImageStep($event, num) {
      this.changeImage[num].image = $event.target.files[0];
    },
    chooseImageNewStep($event, num) {
      this.new_steps[num].image = $event.target.files[0];
    },
    addTask() {
      this.new_steps.push({
        name: "",
        desc: "",
        image: "",
      });
    },
    delNewTask(index) {
      this.new_steps.splice(index, 1);
    },
    edit() {
      this.loading = true;
      const data = new FormData();
      data.append("title", this.title);
      data.append("description", this.description);
      data.append("changeMainImage", this.changeMainImage);
      data.append("slug", this.receipe.slug);

      for (let i = 0; i < this.categories.length; i++) {
        for (let key of Object.keys(this.categories[i])) {
          data.append(`categories[${i}][${key}]`, this.categories[i][key]);
        }
      }

      for (let i = 0; i < this.steps.length; i++) {
        for (let key of Object.keys(this.steps[i])) {
          data.append(`steps[${i}][${key}]`, this.steps[i][key]);
        }
      }

      for (let i = 0; i < this.new_steps.length; i++) {
        for (let key of Object.keys(this.new_steps[i])) {
          data.append(`new_steps[${i}][${key}]`, this.new_steps[i][key]);
        }
      }

      for (let i = 0; i < this.changeImage.length; i++) {
        for (let key of Object.keys(this.changeImage[i])) {
          data.append(`changeImage[${i}][${key}]`, this.changeImage[i][key]);
        }
      }

      this.$inertia.post("/edit/receipe", data, {
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
  computed: {
    isError() {
      if (Object.keys(this.$page.props.errors).length) {
        return true;
      } else {
        return false;
      }
    },
  },
};
</script>