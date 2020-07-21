<template>
    <div class="container p-2">
        <div class="header clearfix">
            <h3 class="text-muted">Phản hồi VNPAY</h3>
            <h5 class="text text-warning">Tự động chuyển về trang chủ sau 7s</h5>
        </div>
        <div class="table-responsive text-black">
            <div class="form-group">
                <label >Mã đơn hàng:</label>
                <label v-html="order.vnp_TxnRef"></label>
            </div>
            <div class="form-group">

                <label >Số tiền:</label>
                <label v-html="order.vnp_Amount"></label>
            </div>
            <div class="form-group">
                <label >Nội dung thanh toán:</label>
                <label v-html="order.vnp_OrderInfo"></label>
            </div>
            <div class="form-group">
                <label >Mã GD Tại VNPAY:</label>
                <label v-html="order.vnp_TransactionNo"></label>
            </div>
            <div class="form-group">
                <label >Mã Ngân hàng:</label>
                <label v-html="order.vnp_BankCode"></label>
            </div>
            <div class="form-group">
                <label >Thời gian thanh toán:</label>
                <label v-html="order.vnp_PayDate"></label>
            </div>
            <div class="form-group">
                <label >Kết quả:</label>
                <label ><strong>{{ Noti }}</strong></label>
            </div>
        </div>
        <p>
            &nbsp;
        </p>
        <footer class="footer">
            <p>&copy; VNPAY 2015</p>
        </footer>
    </div>
</template>
<script>
import moment from 'moment';
export default {
    data () {
        return {
            order : {
                vnp_Amount      : null,
                vnp_BankCode    : null,
                vnp_BankTranNo  : null,
                vnp_CardType    : null,
                vnp_OrderInfo   : null,
                vnp_PayDate     : null,
                vnp_ResponseCode: null,
                vnp_TransactionNo: null,
                vnp_TxnRef      : null,
            }
        }
    },
    created() {
        this.fetchData()
        this.saveOrder()
        this.backToHome()
    },
    methods : {
        fetchData() {
            this.order.vnp_TxnRef     = this.$route.query.vnp_TxnRef
            this.order.vnp_BankCode   = this.$route.query.vnp_BankCode
            this.order.vnp_BankTranNo = this.$route.query.vnp_BankTranNo
            this.order.vnp_CardType   = this.$route.query.vnp_CardType
            this.order.vnp_Amount     = this.$route.query.vnp_Amount
            this.order.vnp_OrderInfo  = this.$route.query.vnp_OrderInfo
            this.order.vnp_PayDate    = this.$route.query.vnp_PayDate
            this.order.vnp_ResponseCode = this.$route.query.vnp_ResponseCode
            this.order.vnp_TransactionNo= this.$route.query.vnp_TransactionNo
        },
        saveOrder() {
            if(this.order.vnp_ResponseCode == "00"){
                let cart = window.sessionStorage.getItem('cart') ? Object.values(JSON.parse(window.sessionStorage.getItem('cart'))) : []
                this.$http.post('/api/order/store', {
                    order : this.order,
                    cart : cart,
                    type : 2
                })
                .then(res => {
                    let cart = []
                    this.$http.get('/api/cart/reset').then().catch(err => console.log(err))
                    window.sessionStorage.setItem('cart', this.cart)
                    this.$eventBus.$emit('resetCart')
                })
                .catch(err => console.log(err))
            }else{
                console.log('luu order khong thanh cong :((')
            }
        },
        backToHome(){
            setTimeout(()=> {
                this.$router.push({name: 'index'})
            }, 7000)
        }
    },
    computed :{
        Noti() {
            if(this.order.vnp_ResponseCode == "00"){
                return 'Thanh toán thành công'
            }
            return 'Thanh toán không thành công'
        }
    }
}
</script>
