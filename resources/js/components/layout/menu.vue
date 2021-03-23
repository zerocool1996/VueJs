<template>
<div>
    <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #e3f2fd;">
        <router-link class="navbar-brand" :to="{name: 'index'}"><i class="fas fa-home"></i></router-link>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <router-link class="nav-link" :to="{name: 'index'}">Home</router-link>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Thê loại <i class="fas fa-sort-down"></i>
                    </a>
                    <div class="dropdown-menu categories" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#" v-for="category in categories" v-bind:key="category.id" :title="category.des" @click.prevent="categoryDetail(category.id)">{{ category.name }}</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Tác giả <i class="fas fa-sort-down"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#" v-for="author in authors" :key="author.id" @click.prevent="authorDetail(author.id)">{{ author.name }}</a>
                    </div>
                </li>
            </ul>
            <a class="btn btn-primary" @click.passive="toCart">
                <i class="fas fa-cart-plus"></i> <span class="badge badge-light">{{ quantityProduct }}</span>
            </a>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Enter để tìm kiếm..." aria-label="Search" v-model="keyword" @keydown.enter="search()">
                <!-- <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button> -->
            </form>
            <a v-if="!token" href="javascript:void(0);" id="login" data-toggle="modal" data-target="#loginModal">Đăng nhập</a>
            <div  v-if="token" class="dropdown user-info">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Hi <span v-if="user">{{ user.first_name }}</span>  !
                </button>
                <div class="dropdown-menu bg-light" aria-labelledby="dropdownMenu2">
                    <button class="dropdown-item" type="button" @click="customerInfo">Sửa thông tin cá nhân</button>
                    <button class="dropdown-item" type="button" @click="ordered">Đơn hàng</button>
                    <button class="dropdown-item" type="button" @click="logout">Đăng xuất</button>
                </div>
            </div>
        </div>
    </nav>
<!--    <ModalLogin @loginSuccess="loginSuccess" />-->
    <ModalLogin/>
</div>
</template>
<script>
import ModalLogin from './modalLogin'
export default {
    components: { ModalLogin },
    data () {
        return {
            authors : [],
            categories : [],
            keyword : null

        }
    },
    computed: {
        quantityProduct() {
            // return this.$store.state.cart.cart.length ?? 0 khi log out cart bị null sẽ gây lỗi con chưa login ko co cart nên ko lỗi
            return this.$store.state.cart.cart ? this.$store.state.cart.cart.length : 0
        },
        user() {
            return this.$store.state.user.user ?? null
        },
        token() {
            return this.$store.state.user.access_token ?? null
        }
    },
    created () {
        this.fetchCategories()
        this.fetchAuthors()
        // this.initCart()
        // this.$eventBus.$on('addToCart', this.addToCart) // lắng nghe event bus
        // this.$eventBus.$on('resetCart', this.resetCart)
        // this.$eventBus.$on('userUpdated', this.getUser) user đã chuyển sang get từ vuex nên ko cần nữa
    },

    methods : {
        fetchCategories() {
            this.$http.get('/api/all/category')
            .then(res => {
                this.categories = res.data.categories
            })
            .catch(err => console.log(err))
        },

        fetchAuthors () {
            this.$http.get('/api/all/author')
            .then(res => {
                this.authors = res.data.authors
            })
            .catch(err => console.log(err))
        },
        // ko cần khi chuyển lưu cart bằng vuex
        // loginSuccess(data) {
        //     this.token = data
        //
        //     this.$http.post('/api/cart/init', {cart : this.cart})
        //     .then(res => {
        //         this.cart = []
        //         let user_cart = res.data.cart
        //         for(let i=0; i< user_cart.length; i++){
        //             let cart = {
        //                 id: user_cart[i].product.id,
        //                 quantity: user_cart[i].quantity,
        //                 name : user_cart[i].product.name,
        //                 img  : user_cart[i].product.image,
        //                 price: user_cart[i].product.price,
        //             }
        //             this.cart.push(cart)
        //         }
        //         window.sessionStorage.setItem('cart', JSON.stringify(this.cart))
        //     })
        //     .catch(err => console.log(err))
        //     this.$eventBus.$emit('login')
        //
        // },
        logout() {
            this.$store.dispatch('user/logout')
            this.$store.dispatch('cart/resetCart')
            this.$eventBus.$emit('logout', {})

        },
        // initCart() {
        //     this.cart = window.sessionStorage.getItem('cart') ? Object.values(JSON.parse(window.sessionStorage.getItem('cart'))) : this.cart
        // },
        // addToCart(data) {
        //     this.cart = data
        // },
        // resetCart(){
        //     this.cart = []
        //     window.sessionStorage.setItem('cart', JSON.stringify(this.cart))
        // },
        authorDetail(id) {
            this.$router.push({name: 'author.detail', params: { id : id}})
            this.$eventBus.$emit('authorDetail')
        },
        categoryDetail(id) {
            this.$router.push({name: 'category.detail', params: { id : id}})
            this.$eventBus.$emit('categoryDetail')
        },
        ordered() {
            this.$router.push({name: 'ordered'})
        },
        customerInfo() {
            this.$router.push({name: 'customer.info'})
        },
        toCart(){
            this.$router.replace({name: 'cart'})
            //this.$eventBus.$on('refresh')
        },
        search(){
            if(this.keyword != null && this.keyword.length > 0){
                this.$router.push({name:'search', params: { keyword: this.keyword }})
                this.$eventBus.$emit('search')
            }else{
                this.$router.push({name:'index'})
            }

        }
    }
}
</script>
<style scoped>
    .navbar {
        padding: 7px;
    }
    .dropdown-toggle::after {
        display: none;
    }
    .navbar-expand-lg .navbar-nav .dropdown-menu {
        top : 20px;
        left: 10px;
        background-color: rgb(221, 231, 204);
        opacity: 0.8;
        width: 950px;
    }
    .navbar-expand-lg .navbar-nav .dropdown-menu a.dropdown-item {
        border: none !important;
        display: inline-block;
        width: 120px;
    }
    button.dropdown-item{
        color:#1f8494;
    }
</style>
