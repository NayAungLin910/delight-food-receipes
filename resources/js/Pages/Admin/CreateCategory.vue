<template>
    <Master>
        <h1 class="title is-4 has-text-centered">Create Category</h1>
        <form @submit.prevent="create">
            <div class="field">
                <label class="label">Enter new category</label>
                <div class="control">
                    <input 
                      v-model="name" 
                      class="input" 
                      type="text" 
                      placeholder="Category"
                      :class="{'is-danger': errors.name}"
                    >
                    <span class="has-text-danger" v-show="errors.name">
                        {{ errors.name }}
                    </span>
                </div>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <button 
                      type="submit" 
                      class="button is-dark"
                      :class="{'is-loading': loading}"
                    >
                        Create
                    </button>
                </div>
            </div>
        </form>
        <div class="notification is-primary mt-2" v-show="$page.props.success">
                    <button class="delete" @click="close"></button>
                    {{ $page.props.success }}
        </div>
    </Master>
</template>
<script>
import Master from '../Layout/Master.vue'
export default {
    name: "CreateCategory",
    components: {
        Master,
    },
    props: {
        errors: Object,
    },
    data(){
        return{
            name: "",
            loading: false,
        }
    },
    methods: {
        create(){
            this.$inertia.post('/create/category', {
                "name": this.name
            }, {
                onStart:() => {
                    this.loading = true;
                },
                onFinish:() => {
                    this.loading = false;
                },
                onSuccess:() => {
                    this.name = "";
                    this.loading = false;
                }
            });
        },
        close(){
            this.$page.props.success = null;
        }
    }
}
</script>