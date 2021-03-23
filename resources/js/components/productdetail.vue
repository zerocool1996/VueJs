<template>
    <div class="media p-1">
        <img v-bind:src="product.img" class="align-self-center mr-3" alt="..." width="250px">
        <div class="media-body">
            <h5 class="mt-0" v-html="product.name"></h5>
            <p class="text-dangder">Giá: {{ product.price }} Vnd</p>
            <strong>Giới thiệu:</strong><br>
            <p v-html="product.content"></p>
            <p><strong>Tác giả:</strong>
                <router-link :to="{name: 'author.detail', params: { id : product.author.id}}" v-html="product.author.name" ></router-link>
            </p>
            <p class="mb-0">Thể loại :
                <router-link class="mr-1" v-for="category in product.categories" v-bind:key="category.id" :to="{name: 'category.detail', params: { id : category.id}}">{{ category.name }}, </router-link>
            </p>
            <button @click="addToCart(product.id)" class="btn btn-outline-success m-1"><i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng</button>
        </div>
    </div>
</template>
<script>
import { mapMutations } from 'vuex'
export default {
    data () {
        return {
            product: {
                id      : null,
                name    : null,
                img     : null,
                price   : null,
                content : null,
                status  : null,
                author  : {},
                categories: [],
            },
            cart: [],
        }
    },
    created () {
        this.fetchData()
        this.$eventBus.$on('productDetail', this.fetchData)
        this.$eventBus.$on('login', this.login)
        this.$eventBus.$on('logout', this.logout)
    },
    methods : {
        ...mapMutations([
            'cart/ADD_TO_CART',
        ]),
        ...mapMutations({
            add_to_cart: 'cart/ADD_TO_CART', // map `this.add()` to `this.$store.commit('increment')`
        }),

        fetchData() {
            let id = this.$route.params.id
            this.$http.get(`/api/product/detail/${id}`)
            .then(res => {
                this.product = {
                    id      : id,
                    name    : res.data.product.name,
                    price   : res.data.product.price,
                    content : res.data.product.content,
                    img     : res.data.product.image,
                    status  : res.data.product.status || 0,
                    author  : res.data.product.product_author,
                    categories: res.data.product.product_category,
                }
            })
            .catch(res => console.log(res))
        },
        addToCart(id) {
            if(this.$store.state.user.access_token){
                const cart = {
                    id: id, // id sản phẩmpm run wtach
                    quantity: 1, // số lượng mặc định 1
                    name : this.product.name,
                    img  : this.product.img,
                    price: this.product.price,
                }
                this.$store.dispatch('cart/addToCart', cart).then(res => {
                    window.izitoast.success({
                        title : "Success",
                        message : res.data.message
                    });
                }).catch(err => {
                    window.izitoast.error({
                        title: 'Error',
                        message: 'Có gì đó lỗi !',
                    });
                })
            }else{

                let data = {
                    id: id,
                    quantity: 1,
                    name : this.product.name,
                    img  : this.product.img,
                    price: this.product.price,
                }
                this.add_to_cart(data)
                window.izitoast.success({
                    title : "Success",
                    message : 'Thêm sản phẩm thành công'
                });
            }
        },
    },
}
</script>
