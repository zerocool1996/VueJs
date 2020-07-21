<template>
    <form style="border:1px solid #ccc">
        <div class="container">
            <h1>Thông tin khách hàng</h1>
            <hr>

            <label for="email"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" v-model="user.email" >
            <div v-if="errors.email" class="alert alert-danger">
                {{ errors.email[0] }}
            </div>

            <label><b>Tên </b></label>
            <input type="text" placeholder="Nhập tên..." v-model="user.first_name" >
            <div v-if="errors.first_name" class="alert alert-danger">
                {{ errors.first_name[0] }}
            </div>

            <label><b>Họ </b></label>
            <input type="text" placeholder="Nhập họ..." v-model="user.last_name" >
            <div v-if="errors.last_name" class="alert alert-danger">
                {{ errors.last_name[0] }}
            </div>
            <label for="add"><b>Địa chỉ</b></label>
            <input type="text" placeholder="Nhập Địa chỉ" id="add" v-model="user.address">
            <div v-if="errors.address" class="alert alert-danger" >
                {{ errors.address[0] }}
            </div>

            <label for="tel"><b>Số điện thoại</b></label>
            <input type="text" placeholder="Nhập số điênh thoại..." id="tel" v-model="user.tel" >
            <div v-if="errors.tel" class="alert alert-danger" >
                {{ errors.tel[0] }}
            </div>
            <label ><b>Giới tính</b></label><br>
            Nam <input type="radio" value="1" name="sex" v-model="user.gender_id">
            Nữ <input type="radio" value="2" name="sex" v-model="user.gender_id">
            Khác <input type="radio" value="3" name="sex" v-model="user.gender_id"><br>

            <label for="img"><b>Hình ảnh</b></label><br>
            <input ref="imageUser" name="img" type="file" class="form-control" @change="changeImage">
            <div v-if="errors.img" class=" alert alert-danger">
                {{ errors.img[0] }}
            </div>
            <img v-if="img" :src="img" class="img-responsive"   width="350" >


            <div v-if="successMessage" class=" alert alert-success">
                {{ successMessage }}
            </div>
            <div class="clearfix mt-3">
                <span type="submit" class="btn btn-success" @click.prevent="update">Cập nhật</span>
                <span type="button" class="btn button-blue" @click.prevent="back">Quay lại</span>
            </div>
        </div>
    </form>
</template>
<script>
export default {
    data() {
        return {
            user: {
                email       : null,
                first_name  : null,
                last_name   : null,
                address     : null,
                tel         : null,
                gender_id   : null,
                img         : null
            },
            successMessage  : null,
            errors          : {},
            id              : null,
            img :null
        }
    },
    created () {
        this.$eventBus.$on('logout', this.logout)
        let data = JSON.parse(window.localStorage.getItem('user'))
        this.id  = data.id
        this.user = {
            email       : data.email,
            first_name  : data.first_name,
            last_name   : data.last_name,
            address     : data.last_name,
            tel         : data.tel,
            gender_id   : data.gender_id,
            //img         : data.img
        }
        this.img = data.img
    },
    methods : {
        back () {
            window.history.go(-1)
        },
        changeImage() {
            const file = this.$refs.imageUser.files[0]
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                this.user.img = e.target.result
                this.img = e.target.result
            }
        },
        update() {
            this.$http.post(`/api/auth/update/${this.id}`, this.user)
            .then(res =>{
                this.successMessage = res.data.message
                this.$http.post('/api/auth/me').then(res => {
                    window.localStorage.setItem('user', JSON.stringify(res.data))
                })
                this.$eventBus.$emit('userUpdated')
            })
            .catch(err => {
                this.errors = err.response.data.errors;
            })
        },
        logout() {
            this.user = {
                email       : null,
                first_name  : null,
                last_name   : null,
                address     : null,
                tel         : null,
                gender_id   : null,
                img         : null
            }
            this.id = null
            this.errors = {}
            this.successMessage = null
            this.$router.push({name:'index'})
        }
    }
}
</script>
<style scoped>
* {box-sizing: border-box}

/* Full-width input fields */
  input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for all buttons */
button {
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

button:hover {
  opacity:1;
}

/* Extra styles for the cancel button */
.cancelbtn {
  padding: 14px 20px;
  background-color: #f44336;
}

/* Float cancel and signup buttons and add an equal width */
.cancelbtn, .signupbtn {
  float: left;
  width: 50%;
}

/* Add padding to container elements */
.container {
  padding: 16px;
}

/* Clear floats */
.clearfix::after {
  content: "";
  clear: both;
  display: table;
}

/* Change styles for cancel button and signup button on extra small screens */
@media screen and (max-width: 300px) {
  .cancelbtn, .signupbtn {
    width: 100%;
  }
}
</style>
