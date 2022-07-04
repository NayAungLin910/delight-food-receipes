<template>
    <AuthMaster>
        <div> 
                <div class="card">
                    <center><img :src="`/image/delight_icon.png`"  width="150" alt=""></center>
                    <p class="is-size-3 has-text-centered has-text-weight-semibold">Please Reigster</p>
                    <form @submit.prevent="register">
                    <div class="card-content">
                    <div class="field">
                        <label for="label">Name</label>
                        <div class="control">
                            <input
                              v-model="name" 
                              class="input" 
                              type="text"
                              :class="{'is-danger':errors.name}"
                            >
                            <span class="has-text-danger" v-show="errors.name">
                                {{ errors.name }}
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label for="label">Email</label>
                        <div class="control">
                            <input
                              v-model="email" 
                              class="input" 
                              type="email"
                              :class="{'is-danger':errors.email}"
                            >
                            <span class="has-text-danger" v-show="errors.email">
                                {{ errors.email }}
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label for="label">Password</label>
                        <div class="control">
                            <input
                              v-model="password" 
                              class="input" 
                              type="password"
                              :class="{'is-danger':errors.password}"
                            >
                            <span class="has-text-danger" v-show="errors.password">
                                {{ errors.password }}
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label for="label">Confirm Password</label>
                        <div class="control">
                            <input
                              v-model="password_confirmation" 
                              class="input" 
                              type="password"
                              :class="{'is-danger':errors.password_confirmation}"
                            >
                            <span class="has-text-danger" v-show="errors.password_confirmation">
                                {{ errors.password_confirmation }}
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <div class="file has-name has-name is-boxed is-small is-dark">
                            <label class="file-label">
                               <input 
                                  class="file-input" 
                                  type="file" 
                                  name="resume"
                                  @change="chooseImage"
                                >
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <i class="fas fa-upload"></i>
                                    </span>
                                    <span class="file-label">
                                        Choose an image
                                    </span>
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
                        <label for="checkbox">
                            <input 
                              type="checkbox"
                              v-model="remember"
                            >
                            Remember me
                        </label>
                    </div>
                    </div>
                    <div class="card-footer">
                        <button 
                          type="submit" 
                          class="button is-dark card-footer-item"
                          :class="{'is-loading': loading}"
                        >
                            Register
                        </button>
                        </div>
                    </form>
                </div>
            </div>
    </AuthMaster>
</template>
<script>
import AuthMaster from './Layout/AuthMaster.vue'
export default {
    name: "Register",
    components: {
        AuthMaster,
    },
    props: {
        errors: Object,
    },
    data(){
        return{
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
            image: "",
            loading: false,
            remember: false,
        }
    },
    methods: {
        chooseImage(e){
            this.image = e.target.files[0];
        },
        register(){
            this.loading = true;
            const data = new FormData();
            data.append('name', this.name);
            data.append("email", this.email);
            data.append("password", this.password);
            data.append("password_confirmation", this.password_confirmation);
            data.append("image", this.image);
            data.append("remember", this.remember);

            this.$inertia.post('/register', data, {
                onStart:() => {
                    this.loading = true;
                },
                onFinish:() => {
                    this.loading = false;
                },
                onSuccess:() => {
                    this.loading = false;
                    this.name = "";
                    this.email = "";
                    this.password = "";
                    this.password_confirmation = "";
                    this.image = "";
                }
            });
        }
    }
}
</script>