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
import { mapMutations } from 'vuex'
export default {
    created () {
        // this.fetchCart()
        this.$eventBus.$on('logout', this.reset)
        this.$eventBus.$on('login', this.refresh)
        // this.$eventBus.$on('resetCart', this.reset)
    },
    computed: {
        total() {
            let total = 0
            if (this.$store.state.cart.cart && this.$store.state.cart.cart.length > 0) {
                this.$store.state.cart.cart.forEach(item => {
                    total = total + item.quantity * item.price
                })
            }
            return total + 'VND'
        },
        cart() {
            return this.$store.state.cart.cart
        }
    },
    methods : {

        ...mapMutations([
            'cart/REMOVE_FROM_CARD',
            'cart/RESET_CART',
            'cart/MINUS',
            'cart/PLUS',
        ]),
        ...mapMutations({
            removeFromCart: 'cart/REMOVE_FROM_CARD', // map `this.add()` to `this.$store.commit('increment')`
            resetCart: 'cart/RESET_CART',
            MINUS : 'cart/MINUS',
            PLUS : 'cart/PLUS',
        }),

        deleteProduct(id) {

            if(this.$store.state.user.access_token) {
                this.$store.dispatch('cart/removeFromCart', id).then(res => {
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
                this.removeFromCart(id);
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
            this.resetCart()
        },
        plus(id){
            let vm  = id
            let quantity = parseInt($('#'+ id).val()) + 1
            let token = window.localStorage.getItem('access_token') ?? null
            if(token != null){
                this.$store.dispatch('cart/plus', {id: vm, quantity}).then(res => {
                    $('#'+ vm).val(quantity)
                }).catch(err => {
                    window.izitoast.error({
                        title: 'Error',
                        message: 'Có gì đó lỗi !',
                    })
                    console.log(err)
                })
            }else{
               this.PLUS(vm)
            }
        },
        minus(id){

            let vm  = id
            let quantity = parseInt($('#'+ id).val())
            if(quantity == 1){
                alert('Số lượng không hợp lệ !!')
                return 0
            }

            quantity = quantity - 1
            let token = this.$store.state.user.access_token ?? null

            if(token){

                this.$store.dispatch('cart/minus', {id: vm, quantity}).then(res => {
                    $('#'+ vm).val(quantity)
                }).catch(err => {
                    window.izitoast.error({
                        title: 'Error',
                        message: 'Có gì đó lỗi !',
                    })
                    console.log(err)
                })

            }else{
               this.MINUS(vm)
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
