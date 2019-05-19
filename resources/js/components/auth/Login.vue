<template>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height:70vh">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4"><i class="fas fa-envelope"></i> Betterprotect</h3>
                        <form autocomplete="off" @submit.prevent="login" method="post">
                            <div class="form-group">
                                <label for="username"><i class="fas fa-user"></i> Benutzer</label>
                                <input :class="{ 'is-invalid': error }" type="text" id="username" class="form-control" v-model="username" autofocus @click="clearError">

                                <b-form-invalid-feedback>
                                    {{ this.error }}
                                </b-form-invalid-feedback>
                            </div>

                            <div class="form-group">
                                <label for="password"><i class="fas fa-key"></i> Passwort</label>
                                <input type="password" id="password" class="form-control" v-model="password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                username: null,
                password: null,
                error: false
            }
        },

        methods: {
            clearError() {
                this.error = false;
            },
            login(){
                this.$auth.login({
                    data: {
                        username: this.username,
                        password: this.password
                    },
                    success: function () {
                        this.clearError();
                    },
                    error: (error) => {
                        this.error = error.response.data.message;
                    },
                    rememberMe: true,
                    redirect: '/',
                    fetchUser: true,
                });
            },
        }
    }
</script>