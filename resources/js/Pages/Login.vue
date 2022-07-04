<template>
    <div>
        <AuthMaster>
            <div> 
                <div class="card">
                    <center><img :src="`/image/delight_icon.png`"  width="150" alt=""></center>
                    <p class="is-size-3 has-text-centered has-text-weight-semibold">Please Login</p>
                    <form @submit.prevent="login">
                    <div class="card-content">
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
                    <div class="field" v-show="errors.errors">
                        <div class="notification is-danger">
                            {{ errors.errors }}
                        </div>
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
                    <div class="field">
                        <inertia-link href="/forgot/password">
                            Forgot Password?
                        </inertia-link>
                    </div>
                    </div>
                    <div class="card-footer">
                        <button 
                          type="submit" 
                          class="button is-dark card-footer-item"
                          :class="{'is-loading': loading}"
                        >
                            Login
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthMaster>
    </div>
</template>
<script>
import AuthMaster from './Layout/AuthMaster.vue';
export default {
    name: "Login",
    components: {
        AuthMaster,
    },
    props: {
        errors: Object,
    },
    data(){
        return{
            email: "",
            password: "",
            remember: false,
            loading: false,
        } 
    },
    methods: {
        login(){
            const data = new FormData();
            data.append('email', this.email);
            data.append('password', this.password);
            data.append('remember', this.remember);

            this.$inertia.post('/login', data, { 
                onStart:() => {
                    this.loading = true;
                },
                onFinish:() => {
                    this.loading = false;
                },
                onSuccess:() => {
                    this.loading = false;
                }
            });
        }
    }
}
</script>