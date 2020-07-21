<template>
    <div class="p-2">
        <h5>Tạo mới đơn hàng</h5>
        <form >
            <div class="form-group">
                <label for="name">Tên khách hàng</label>
                <input class="form-control" id="name" type="text" v-model="user.fullname">
            </div>
            <div class="form-group">
                <label for="tel">Số điện thoại </label>
                <input class="form-control" id="tel" type="text" v-model="user.tel">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" id="email" type="email" v-model="user.email">
            </div>
            <div class="form-group">
                <label for="add">Địa chỉ</label>
                <input class="form-control" id="email" type="add" v-model="user.address">
            </div>
            <div class="form-group">
                <label for="total">Số tiền</label>
                <input class="form-control" id="total" type="
                text" v-model="total">
            </div>
            <div class="form-group">
                <label for="content">Nội dung thanh toán</label>
                <input class="form-control" id="content" type="" v-model="content">
            </div>
            <div class="form-group">
                <label for="bank">Ngân hàng</label>
                <input class="form-control" id="bank" type="">
            </div>
            <div class="form-roup">
                <button class="btn btn-outline-dark" @click.prevent="reDirect">Thanh toán online</button>
                <button class="btn btn-outline-dark" @click.prevent="payOffline">Thanh toán offline</button>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    data () {
        return {
            cart : [],
            user  : [],
            total: 0,
            content : null,

        }
    },
    created() {
        this.fetchData()
    },
    mounted() {
        if (this.cart && this.cart.length > 0) {
            this.cart.forEach(item => {
                this.total = this.total + item.quantity * item.price
            })
        }

    },
    methods : {
        fetchData() {
            this.user = window.localStorage.getItem('user') ? JSON.parse(window.localStorage.getItem('user')) : this.user
            this.cart = window.sessionStorage.getItem('cart') ? Object.values(JSON.parse(window.sessionStorage.getItem('cart'))) : this.cart
        },
        reDirect() {
            this.$http.post('/api/vnpay',{
                total : this.total,
                content : this.content
            })
            .then(res => {
                if(res.data.code === "00") {
                    window.location.href = res.data.data
                }
            })
        },
        payOffline() {
            let cart = window.sessionStorage.getItem('cart') ? Object.values(JSON.parse(window.sessionStorage.getItem('cart'))) : []
            this.$http.post('/api/order/store', {
                cart : cart,
                type : 1,
                amount: this.total,
                message : this.content
            })
            .then(res => {
                this.$eventBus.$emit('resetCart')
                this.$http.get('/api/cart/reset').then().catch(err => console.log(err))
                window.izitoast.success({
                    title : "Success",
                    message : "Đặt hàng thành công !"
                });
            })
            .catch(err => console.log(err))
        }
    }
}
</script>
