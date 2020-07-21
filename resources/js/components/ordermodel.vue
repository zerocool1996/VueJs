<template>
    <div :class="isShow ? 'd-block' : 'd-none p-2' ">
        <h5 class="text text-center text-success">Chi tiết đơn hàng</h5>
        <div class="table-responsive text-black">
            <div class="form-group">
                <label >Mã đơn hàng:</label>
                <label v-html="data.id"></label>
            </div>
            <div class="form-group">
                <label >Số tiền:</label>
                <label v-html="data.amount"></label>
            </div>
            <div class="form-group">
                <label >Nội dung thanh toán:</label>
                <label v-html="data.message"></label>
            </div>

            <div class="form-group">
                <label >Loại hình thanh toán:</label>
                <label v-html="data.type"></label>
            </div>

            <div class="form-group">
                <label >Trạng thái đơn hàng:</label>
                <label v-html="data.status"></label>
            </div>

            <div v-if="data.type == 'Online' " class="form-group">
                <label >Mã GD Tại VNPAY:</label>
                <label v-html="data.vnp_TransactionNo"></label>
            </div>
            <div v-if="data.type == 'Online' " class="form-group">
                <label >Mã Ngân hàng:</label>
                <label v-html="data.vnp_BankCode"></label>
            </div>
            <div v-if="data.type == 'Online' " class="form-group">
                <label >Thời gian thanh toán:</label>
                <label v-html="data.vnp_PayDate"></label>
            </div>
        </div>
        <h5>Các sản phẩm trong đơn hàng</h5>
        <table class="table table-hover table-info p-3">

            <thead>
                <th>Tên </th>
                <th>hình ảnh </th>
                <th>Số lượng </th>
                <th>Giá </th>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td>{{ product.orderdetail_product.name }}</td>
                    <td><img :src="product.orderdetail_product.image" width="40px"></td>
                    <td>{{ product.quantity }}</td>
                    <td>{{ product.price }}</td>
                </tr>
            </tbody>
            <button class="btn btn-success" @click="back">Back</button>
        </table>
    </div>
</template>
<script>
export default {
    data () {
        return {
            isShow      : false,
            data        : [],
            products    : [],
        }
    },
    methods : {
        fetchData(id) {
            this.$http.get(`/api/order/detail/${id}`)
            .then(res => {
                this.data = res.data.data
                this.products = res.data.data.order_detail
            })
            .catch(err => console.log(err))
            this.$emit('showSuccess')
            this.isShow = true

        },
        back() {
            this.isShow = false
            this.$emit('unshowModel')
            this.data = []
            this.products = []
        }
    }
}
</script>
