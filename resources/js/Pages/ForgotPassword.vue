<template>
    <div class="container mt-5">
        <div class="columns">
            <div class="column is-4"></div>
            <div class="column is-4">
                <div class="card">
                    <center><img :src="`/image/delight_icon.png`"  width="150" alt=""></center>
                    <p class="is-size-3 has-text-centered has-text-weight-semibold">Forgot Password</p>
                    <form @submit.prevent="verify" v-show="!isData && !isFinalData">
                        <div class="card-content">
                            <div class="field">
                                <label for="label">Please enter email where verification code will be sent</label>
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
                                <inertia-link href="/login">
                                    Back to login
                                </inertia-link>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button 
                              type="submit" 
                              class="button is-dark card-footer-item"
                              :class="{'is-loading': loading}"
                            >
                                Send Code
                            </button>
                        </div>
                    </form>
                    <form @submit.prevent="verifyCode" v-show="isData && !isFinalData">
                        <div class="card-content">
                            <div class="field">
                                <label for="label">Please enter code sent via mail</label>
                                <div class="control">
                                    <input
                                      v-model="code" 
                                      class="input" 
                                      type="text"
                                      :class="{'is-danger':codeError}"
                                    >
                                    <span class="has-text-danger" v-show="codeError">
                                        {{ codeError }}
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <inertia-link href="/login">
                                    Back to login
                                </inertia-link>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button 
                              type="submit" 
                              class="button is-dark card-footer-item"
                              :class="{'is-loading': loading}"
                            >
                                Verify Code
                            </button>
                        </div>
                    </form>
                    <form @submit.prevent="changePassword" v-show="isFinalData">
                        <div class="card-content">
                            <div class="field">
                                <label for="label">New Password</label>
                                <div class="control">
                                    <input
                                      v-model="password" 
                                      class="input" 
                                      type="password"
                                      :class="{'is-danger':finalError}"
                                    >
                                    <span class="has-text-danger" v-show="finalError">
                                        {{ finalError }}
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <label for="label">Confirm New Password</label>
                                <div class="control">
                                    <input
                                      v-model="password_confirm" 
                                      class="input" 
                                      type="password"
                                      :class="{'is-danger':finalConfirmError}"
                                    >
                                    <span class="has-text-danger" v-show="finalConfirmError">
                                        {{ finalConfirmError }}
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <inertia-link href="/login">
                                    Back to login
                                </inertia-link>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button 
                              type="submit" 
                              class="button is-dark card-footer-item"
                              :class="{'is-loading': loading}"
                            >
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="column is-4"></div>
        </div>
    </div>
</template>
<script>
import { useToast } from 'vue-toastification';
export default {
    name: "ForgotPassword",
    props: {
        errors: Object,
        data: Object,
        codeError: String,
        finalData: Object,
        finalError: String,
        finalConfirmError: String,
    },
    data(){
        return{
            email: "",
            loading: false,
            code: "",
            password: "",
            password_confirm: "",
        }
    },
    created(){
        const toast = useToast();
        if(this.$page.props.success){
            toast.success(this.$page.props.success, {
                timeout: 2500
            })
        }
        if(this.$page.props.info){
            toast.info(this.$page.props.info, {
                timeout: 2500
            })
        }
        if(this.$page.props.error){
            toast.error(this.$page.props.error, {
                timeout: 2500
            })
        }
    },
    methods: {
        verify(){
            const data = new FormData();
            data.append('email', this.email);

            this.$inertia.post('/forgot/password', data, {
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
        },
        verifyCode(){
            const codeData = new FormData();

            this.$inertia.post('/forgot/password/verify', {
                code: this.code,
                data: this.data,
                email: this.data.email,
            }, {
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
        },
        changePassword(){
            const formData = new FormData();
            formData.append("password", this.password);
            formData.append("password_confirm", this.password_confirm);
            formData.append("finalData", this.finalData);
            formData.append("email", this.email);

            this.$inertia.post('/change/password', formData, {
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
    },
    computed: {
        isData(){
            if(this.data){
                return true;
            }else{
                return false;
            }
        },
        isFinalData(){
            if(this.finalData){
                return true;
            }else{
                return false;
            }
        }
    }
}
</script>