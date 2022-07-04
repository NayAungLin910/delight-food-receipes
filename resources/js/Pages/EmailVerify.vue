<template>
    <div class="container mt-5">
        <div class="columns">
            <div class="column is-4"></div>
            <div class="column is-4">
                <div class="card">
                    <center><img :src="`/image/delight_icon.png`"  width="150" alt=""></center>
                    <p class="is-size-3 has-text-centered has-text-weight-semibold">Please Confirm Email</p>
                    <form @submit.prevent="verify">
                        <div class="card-content">
                            <div class="field">
                                <label for="label">Please enter code sent to your email</label>
                                <div class="control">
                                    <input
                                      v-model="vercode" 
                                      class="input" 
                                      type="text"
                                      :class="{'is-danger':errors.vercode}"
                                    >
                                    <span class="has-text-danger" v-show="errors.vercode">
                                        {{ errors.vercode }}
                                    </span>
                                </div>      
                            </div>
                            <div class="field">
                                 <inertia-link href="/email/verify/resend" @click="showNoti">
                                    Send code again
                                </inertia-link>
                            </div>
                            <div class="field">
                                <inertia-link href="/email/verify/cancel">
                                    Back to Register
                                </inertia-link>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button 
                              type="submit" 
                              class="button is-dark card-footer-item"
                              :class="{'is-loading': loading}"
                            >
                                Confirm
                            </button>
                        </div>
                    </form>
                    <div class="notification is-primary mt-2" v-show="noti">
                        <button class="delete" @click="closeNoti"></button>
                        Please wait for awhile for the code to be arrived.
                    </div>
                </div>
            </div>
            <div class="column is-4"></div>
        </div>
    </div>
</template>
<script>
import { useToast } from 'vue-toastification';
export default {
    name: "EmailVeify",
    props: {
        errors: Object,
    },
    data(){
        return{
            vercode: "",
            loading: false,
            noti: false,
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
            data.append('vercode', this.vercode);

            this.$inertia.post('/email/verify', data, {
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
        showNoti(){
            this.noti = true;
        },
        closeNoti(){
            this.noti = false;
        }
    }
}
</script>