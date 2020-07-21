<template>
    <table class="table table-bordered">
        <thead>
            <th>Tên </th>
            <th>hình ảnh </th>
            <th>Số lượng </th>
            <th>Giá </th>
            <th>Thao tác</th>
        </thead>
        <tbody>
            <template v-if="cart && cart.length > 0">
                <tr v-for="item in cart" :key="item.id">
                    <td>{{ item.name }}</td>
                    <td><img :src="item.img" width="40px"></td>
                    <td>
                        <button @click="plus(item.id)" class="btn btn-sm btn-outline-info"><i class="far fa-plus-square"></i></button>
                        <!-- {{ item.quantity }} -->
                        <input :id="item.id" :value="item.quantity">
                        <button @click="minus(item.id)" class="btn btn-sm btn-outline-info"><i class="far fa-minus-square"></i></button>
                    </td>
                    <td>{{ item.price }}</td>
                    <td><span class="btn btn-danger" @click="deleteProduct(item.id)">Delete</span></td>
                </tr>
                <tr>
                    <td>Tổng giá</td>
                    <td colspan="4">{{ total }}</td>
                </tr>
            </template>
        </tbody>
        <button class="btn btn-success" @click="order">Check out</button>
        <!-- <button class="btn btn-danger" @click="reset">Reset cart</button> -->
    </table>

</template>
<script>
export default {
    data () {
        return {
            cart : [],
        }
    },
    created () {
        this.fetchCart()
        this.$eventBus.$on('logout', this.reset)
        this.$eventBus.$on('login', this.refresh)
        this.$eventBus.$on('resetCart', this.reset)
    },
    computed: {
        total() {
            let total = 0
            if (this.cart && this.cart.length > 0) {
                this.cart.forEach(item => {
                    total = total + item.quantity * item.price
                })
            }
            return total + 'VND'
        }
    },
    methods : {
        fetchCart() {
            this.cart = window.sessionStorage.getItem('cart') ? Object.values(JSON.parse(window.sessionStorage.getItem('cart'))) : this.cart
        },

        deleteProduct(id) {
            // let index = -1
            // for(let i = 0; i < this.cart.length; i++){
            //     if (this.cart[i].id === id) {
            //         index = i
            //         break
            //     }
            // }
            // this.cart.splice(index, 1)
            let token = window.localStorage.getItem('access_token') ?? null
                if(token){
                this.$http.get(`/api/cart/delete-product/${id}`)
                .then(res => {
                    this.cart = this.cart.filter(item => { // hàm filter để lọc các sản phẩm không có id = id bị xóa - tức là chỉ lấy các sản phẩm không bị xóa
                        return item.id !== id
                    })
                    this.$eventBus.$emit('addToCart', this.cart)
                    window.sessionStorage.setItem('cart', JSON.stringify(this.cart))
                    window.izitoast.success({
                        title : "Success",
                        message : res.data.message
                    });
                })
                .catch(err => {
                    window.izitoast.error({
                        title: 'Error',
                        message: 'Có gì đó lỗi !!',
                    });
                })
            }else{
                this.cart = this.cart.filter(item => {
                    return item.id !== id
                })
                this.$eventBus.$emit('addToCart', this.cart)
                window.sessionStorage.setItem('cart', JSON.stringify(this.cart))
                window.izitoast.success({
                    title : "Success",
                    message : 'Xóa sản phẩm thành công !'
                });
        }
        },
        order() {
            if(window.localStorage.getItem('access_token') != null){
                this.$router.push({name : 'order'})
            }else{
                document.getElementById('login').click()
            }
        },
        reset() {
            this.cart = []
            window.sessionStorage.setItem('cart', JSON.stringify(this.cart))
            //this.$eventBus.$emit('resetCart')
        },
        refresh(){
            setTimeout(this.fetchCart, 2000);
        },
        plus(id){
            let vm  = id
            let quantity = parseInt($('#'+ id).val()) + 1
            let token = window.localStorage.getItem('access_token') ?? null
            if(token != null){
                this.$http.get(`/api/cart/plus/${id}/${quantity}`)
                .then(res => {
                    $('#'+ vm).val(quantity)
                    let index = -1
                    for(let i= 0; i< this.cart.length; i++){
                        if(this.cart[i].id == vm){
                            index = i
                            break;
                        }
                    }
                    if(index != -1){
                        this.cart[index].quantity +=1
                    }
                    window.sessionStorage.setItem('cart', JSON.stringify(this.cart))
                })
                .catch(err => console.log(err))
            }else{
                $('#'+ vm).val(quantity)
                let index = -1
                for(let i= 0; i< this.cart.length; i++){
                    if(this.cart[i].id == vm){
                        index = i
                        break;
                    }
                }
                if(index !== -1){
                    this.cart[index].quantity +=1
                }
                window.sessionStorage.setItem('cart', JSON.stringify(this.cart))
            }
        },
        minus(id){
            //alert(1)
            let vm  = id
            let quantity = parseInt($('#'+ id).val())
            if(quantity == 1){
                alert('Số lượng không hợp lệ !!')
                return 0
            }
            //alert(2)
            quantity = quantity - 1
            let token = window.localStorage.getItem('access_token') ?? null
            if(token){
               // alert(3)
                this.$http.get(`/api/cart/minus/${id}/${quantity}`)
                .then(res => {
                    $('#'+ vm).val(quantity)
                    let index = -1
                    for(let i= 0; i< this.cart.length; i++){
                        if(this.cart[i].id == vm){
                            index = i
                            break;
                        }
                    }
                    if(index != -1){
                        this.cart[index].quantity -= 1
                    }
                    window.sessionStorage.setItem('cart', JSON.stringify(this.cart))
                })
                .catch(err => console.log(err))
            }else{
                //alert(4)
                $('#'+ vm).val(quantity)
                let index = -1
                for(let i= 0; i< this.cart.length; i++){
                    if(this.cart[i].id == vm){
                        index = i
                        break;
                    }
                }
                alert(index)
                if(index != -1){
                    this.cart[index].quantity -= 1
                }
                window.sessionStorage.setItem('cart', JSON.stringify(this.cart))
                //alert(5)
            }
        }
    },

}
</script>
<style scoped>
    tr td input{
        width: 50px;
    }
</style>
