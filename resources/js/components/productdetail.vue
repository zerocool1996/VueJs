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
            token : null,
        }
    },
    created () {
        this.fetchData()
        this.$eventBus.$on('productDetail', this.fetchData)
        this.$eventBus.$on('login', this.login)
        this.$eventBus.$on('logout', this.logout)
    },
    methods : {
        fetchData() {
            this.token = window.localStorage.getItem('access_token') ?? null
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
            if(this.token){
                this.$http.get(`/api/cart/add-product/${id}`)
                .then(res => {
                    // lấy sản phẩm thêm vào giỏ hàng
                    const cart = {
                        id: id, // id sản phẩm
                        quantity: 1, // số lượng mặc định 1
                        name : this.product.name,
                        img  : this.product.img,
                        price: this.product.price,
                    }
                    let index = -1 // biến để xem thêm số lượng sản phẩm hay thêm sản phẩm mới
                    this.cart = window.sessionStorage.getItem('cart') ? Object.values(JSON.parse(window.sessionStorage.getItem('cart'))) : this.cart // lấy giỏ hàng từ local session, nếu có giỏ hàng rồi thì chuyển nó về mảng array - JSON.parse chuyển về dạng object JSON - Object.values chuyển về dạng mảng, nếu không có giỏ hàng thì set bằng rỗng
                    for (let i=0; i<this.cart.length; i++) { // vòng lặp kiểm tra giỏ hàng
                        if (this.cart[i].id === id ) { // nếu sản phẩm thêm đã tồn tại trong giỏ hàng
                            index = i // đặt index = vị trí của sản phẩm trong giỏ hàng
                            break // thoát khỏi vòng lặp
                        }
                    }
                    if (index === -1) { // nếu biến index giữ nguyên
                        this.cart.push(cart) // thêm mới sản phẩm vào giỏ hàng
                    } else { // nếu biến index đã bị gán thành vị trí sản phẩm trogn giỏ hàng
                        this.cart[index].quantity += 1 // tăng số lượng sản phẩm trong giỏ hàng lên 1
                    }
                    this.$eventBus.$emit('addToCart', this.cart) // truyền event để thông báo thêm sản phẩm vào giỏ hàn
                    window.sessionStorage.setItem('cart', JSON.stringify(this.cart)) // lưu giỏ hàng vào local session, JSON.stringify - chuyển dữ liệu về dạng tring JSON
                    window.izitoast.success({
                        title : "Success",
                        message : res.data.message
                    });
                })
                .catch(err =>{
                    window.izitoast.error({
                        title: 'Error',
                        message: 'Có gì đó lỗi !',
                    });
                })
            }else{
                const cart = {
                    id: id,
                    quantity: 1,
                    name : this.product.name,
                    img  : this.product.img,
                    price: this.product.price,
                }
                let index = -1
                this.cart = window.sessionStorage.getItem('cart') ? Object.values(JSON.parse(window.sessionStorage.getItem('cart'))) : this.cart
                for (let i=0; i<this.cart.length; i++) {
                    if (this.cart[i].id === id ) {
                        index = i
                        break
                    }
                }
                if (index === -1) {
                    this.cart.push(cart)
                } else {
                    this.cart[index].quantity += 1
                }
                this.$eventBus.$emit('addToCart', this.cart)
                window.sessionStorage.setItem('cart', JSON.stringify(this.cart))
            }
        },
        login(){
            setTimeout(()=>{
                this.token = window.localStorage.getItem('access_token') ?? null
            }, 1000)
        },
        logout(){
            this.token = null
        }
    },
}
</script>
