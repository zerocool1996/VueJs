<template>
    <div>
        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Đăng nhập</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Email:</label>
                                <input type="text" class="form-control" v-model="user.email">
                                <div v-if="errors.email" class="text-danger">
                                    {{ errors.email[0] }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">password:</label>
                                <input type="password" class="form-control" v-model="user.password">
                                <div v-if="errors.password" class="text-danger">
                                    {{ errors.password[0] }}
                                </div>
                            </div>

                            <div v-if="errors.credentials" class="alert alert-danger">
                                {{ errors.credentials }}
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="login">Đăng nhập</button>
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <router-link :to="{name : 'signup'}" type="button" class="btn btn-link" data-dismiss="modal">Đăng kí</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
        user: {
            email: '',
            password: ''
        },
        errors: {}
        }
    },
    methods : {
        login() {
            this.$http.post('/api/auth/login', this.user).then(res => {
                window.localStorage.setItem('access_token', res.data.access_token)
                this.getUserLogin()
                this.$emit('loginSuccess', res.data.access_token)
                document.getElementById('login').click()
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors
                }
            })
        },
        getUserLogin() {
            this.$http.post('/api/auth/me').then(res => {
                window.localStorage.setItem('user', JSON.stringify(res.data))
            })
        },
    }
}
</script>
