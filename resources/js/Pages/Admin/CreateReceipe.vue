<template>
  <Master>
    <div>
      <h1 class="title is-4 has-text-centered">Create Receipe</h1>
      <form @submit.prevent="create">
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
          <div class="notification is-danger" v-show="isError">
            Receipe creation failed. Please check and fill the form again!
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
                name="mainImage"
                @change="chooseImage"
              />
              <span class="file-cta">
                <span class="file-icon">
                  <i class="fas fa-upload"></i>
                </span>
                <span class="file-label"> Receipe image </span>
              </span>
              <span class="file-name">
                {{ mainImage.name }}
              </span>
            </label>
          </div>
          <span class="has-text-danger" v-show="errors.mainImage">
            {{ errors.mainImage }}
          </span>
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
            <label class="label">Select Categories</label>
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
              <input type="text" v-model="step.name" class="input" :class="{'is-danger': errors[`steps.${index}.name`]}" />
            </div>
            <span
              class="has-text-danger"
              v-show="errors[`steps.${index}.name`]"
            >
              {{ errors[`steps.${index}.name`] }}
            </span>
            <label class="label">Description</label>
            <div class="control">
              <vue-editor v-model="step.desc" :editor-toolbar="customToolbar" />
            </div>
            <span
              class="has-text-danger"
              v-show="errors[`steps.${index}.desc`]"
            >
              {{ errors[`steps.${index}.desc`] }}
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
                  <span class="file-label">Step-{{ index + 1 }} image </span>
                </span>
                <span class="file-name">
                  {{ step.image.name }}
                </span>
              </label>
            </div>
            <span
              class="has-text-danger"
              v-show="errors[`steps.${index}.image`]"
            >
              {{ errors[`steps.${index}.image`] }}
            </span>
            <div class="container mt-4">
              <div class="columns">
                <div class="column" v-show="index == steps.length - 1">
                  <button
                    type="button"
                    class="button is-light"
                    @click="addTask"
                  >
                    <span class="icon">
                      <i class="fa-solid fa-circle-plus"></i>
                    </span>
                    <div class="container">Add Another Step</div>
                  </button>
                </div>
                <div class="column" v-show="index !== 0">
                  <button
                    type="button"
                    class="button is-danger"
                    @click="delTask(index)"
                  >
                    <span class="icon">
                      <i class="fa-solid fa-circle-minus"></i>
                    </span>
                    <div class="container">Delete Step</div>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <button
          type="submit"
          class="button is-success"
          :class="{ 'is-loading': loading }"
        >
          Create Receipe
        </button>
      </form>
    </div>
  </Master>
</template>
<script>
import Master from "../Layout/Master.vue";
import { VueEditor } from "vue3-editor";
export default {
  name: "CreateReceipe",
  components: {
    Master,
    VueEditor,
  },
  props: {
    errors: Object,
  },
  data() {
    return {
      title: "",
      description: "",
      mainImage: "",
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
      steps: [
        {
          name: "",
          desc: "",
          image: "",
        },
      ],
      loading: false,
    };
  },
  methods: {
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
      this.mainImage = e.target.files[0];
    },
    chooseImageStep($event, num) {
      this.steps[num].image = $event.target.files[0];
    },
    addTask() {
      this.steps.push({
        name: "",
        desc: "",
        image: "",
      });
    },
    delTask(index) {
      this.steps.splice(index, 1);
    },
    create() {
      this.loading = true;
      const data = new FormData();
      data.append("title", this.title);
      data.append("description", this.description);
      data.append("mainImage", this.mainImage);

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

      this.$inertia.post("/create/receipe", data, {
        onStart: () => {
          this.loading = true;
        },
        onFinish: () => {
          this.loading = false;
        },
        onSuccess: () => {
          this.loading = false;
          this.title = "";
          this.description = "";
          (this.categories = ""), (this.mainImage = "");
          this.steps = [
            {
              name: "",
              desc: "",
              image: "",
            },
          ];
        },
      });
    },
  },
  computed: {
    isError(){
      if(Object.keys(this.$page.props.errors).length){
        return true;
      }else{
        return false;
      }
    }
  }
};
</script>