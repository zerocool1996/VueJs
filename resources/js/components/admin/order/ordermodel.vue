<template>
    <div :class="isShow ? 'd-block' : 'd-none p-2' ">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" @click.prevent="back">Đơn hàng</a></li>
                <li class="breadcrumb-item active">Chi tiết đơn hàng</li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block col-sm-11">
                            <div class="block-body">
                                <div class="table-responsive">
                                    <div class="form-group">
                                        <label >Tên khách hàng: </label>
                                        <label v-html="user.fullname"></label>
                                    </div>
                                    <div class="form-group">
                                        <label >Địa chỉ :</label>
                                        <label v-html="user.address"></label>
                                    </div>
                                    <div class="form-group">
                                        <label >Số điện thoại :</label>
                                        <label v-html="user.tel"></label>
                                    </div>
                                    <div class="form-group">
                                        <label >Trạng thái :</label>
                                        <label v-html="status"></label>
                                    </div>
                                    <div v-if="data.type == 'Online'">
                                        <div class="form-group">
                                            <label >Mã đơn hàng:</label>
                                            <label v-html="data.vnp_TxnRef"></label>
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
                                            <label >Mã GD Tại VNPAY:</label>
                                            <label v-html="data.vnp_TransactionNo"></label>
                                        </div>
                                        <div class="form-group">
                                            <label >Mã Ngân hàng:</label>
                                            <label v-html="data.vnp_BankCode"></label>
                                        </div>
                                        <div class="form-group">
                                            <label >Thời gian thanh toán:</label>
                                            <label v-html="data.vnp_PayDate"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <h5 class="text text-center">Các sản phẩm trong đơn hàng</h5>
                                    <table class="table table-striped ">

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
                                    </table>

                                </div>
                                <button v-if="trangthai==0" class="btn btn-success" @click="confirm">Xác nhận đơn hàng</button>
                            </div>
                            <button class="btn btn-outline-primary" @click="back">Back</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
export default {
    data () {
        return {
            isShow      : false,
            data        : [],
            products    : [],
            user        : {},
            status      : null,
            trangthai   : null,
            id          : null,
        }
    },
    methods : {
        fetchData(id) {
            this.$http.get(`/api/orders/detail/${id}`)
            .then(res => {
                this.id = res.data.data.id
                this.data = res.data.data
                this.products = res.data.data.order_detail
                this.user  = res.data.data.order_user
                this.status = res.data.data.status,
                this.trangthai = res.data.data.trangthai
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
            this.id = null
            this.status = null
            this.trangthai = null
            this.user = {}
        },
        confirm() {
            this.$http.get(`/api/orders/confirm/${this.id}`)
            .then(res =>{
                window.izitoast.success({
                    title: 'Success',
                    message: res.data.message,
                });
                this.status = "đã xác nhận"
                this.trangthai = 1
            })
            .catch(err => {
                console.log(err)
                window.izitoast.error({
                    title: 'Error',
                    message: 'Xác nhận không thành công',
                });
            })
        }
    }
}
</script>
