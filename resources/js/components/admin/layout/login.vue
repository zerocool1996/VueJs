<template>
    <div class="container-fluid">
        <div class="d-flex justify-content-center pt-5">
        <div class="card">
            <div class="card-header">
                <h3>Sign In</h3>
            </div>
            <div class="card-body">
                <form>
                    <div class="input-group form-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="fas fa-user"></i>
                        </span>
                    </div>
                    <input v-model="user.email" type="email" class="form-control" placeholder="Enter Email" />
                    </div>
                    <div v-if="errors.email" class="text-danger">
                        {{ errors.email[0] }}
                    </div>
                    <div class="input-group form-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="fas fa-key"></i>
                        </span>
                    </div>
                    <input v-model="user.password" type="password" class="form-control" placeholder="Enter Password" />
                    </div>
                    <div v-if="errors.password" class="text-danger">
                    {{ errors.password[0] }}
                    </div>
                    <div v-if="errors.credentials" class="text-danger">
                    {{ errors.credentials }}
                    </div>
                    <div class="form-group">
                    <input type="submit" value="Login" @click.prevent="login" class="btn float-right login_btn" />
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center links">
                    Don't have an account?
                    <a href="#">Sign Up</a>
                </div>
                <div class="d-flex justify-content-center">
                    <a href="#">Forgot your password?</a>
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
            this.$http.post('/api/auth/admin/login', this.user).then(res => {
                window.localStorage.setItem('access_token', res.data.access_token)
                this.getUserLogin()
                this.toHome()
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors
                }
            })
        },
        toHome () {
            this.$router.push(this.$route.query.redirect || { name: 'dashboard'});
        },
        getUserLogin() {
            this.$http.post('/api/auth/admin/me').then(res => {
                window.localStorage.setItem('admin', JSON.stringify( res.data))
            })
        }
    }
}
</script>
<style scoped>
.container-fluid {
  background-image: url('https://uploadstatic-sea.mihoyo.com/contentweb/20200602/2020060215050762333.png');
  background-size: cover;
  background-repeat: no-repeat;
  height: 100%;
  font-family: "Numans", sans-serif;
}
.container-fluid {
  height: 100vh;
  align-content: center;
}
.card {
  height: 370px;
  margin-top: auto;
  margin-bottom: auto;
  width: 400px;
  background-color: rgba(0, 0, 0, 0.5) !important;
}
.social_icon span {
  font-size: 60px;
  margin-left: 10px;
  color: #ffc312;
}
.social_icon span:hover {
  color: white;
  cursor: pointer;
}
.card-header h3 {
  color: white;
}
.social_icon {
  position: absolute;
  right: 20px;
  top: -45px;
}
.input-group-prepend span {
  width: 50px;
  background-color: #ffc312;
  color: black;
  border: 0 !important;
}
input:focus {
  outline: 0 0 0 0 !important;
  box-shadow: 0 0 0 0 !important;
}
.remember {
  color: white;
}
.remember input {
  width: 20px;
  height: 20px;
  margin-left: 15px;
  margin-right: 5px;
}
.login_btn {
  color: black;
  background-color: #ffc312;
  width: 100px;
}
.login_btn:hover {
  color: black;
  background-color: white;
}
.links {
  color: white;
}
.links a {
  margin-left: 4px;
}
</style>
