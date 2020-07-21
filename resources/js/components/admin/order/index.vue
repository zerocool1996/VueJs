<template>
    <div>
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Trang Quản Trị Hệ Thống</h2>
            </div>
	    </div>
        <div :class="[showModel ? 'd-none' : 'd-block']">
            <!-- Breadcrumb-->
            <div class="container-fluid">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Đơn hàng</a></li>
                    <li class="breadcrumb-item active">Đơn hàng</li>
                </ul>
            </div>
            <section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <select class="custom-select col-md-3 mb-1" title="Số bản ghi trên 1 trang" @change="search" v-model="type">
                                <option value="3">Tất cả</option>
                                <option value="2">Thanh toán online</option>
                                <option value="1">Thanh toán khi nhận hàng</option>
                            </select>

                        </div>
                        <div class="col-lg-12">
                            <div class="block margin-bottom-sm">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <th>Mã đơn hàng</th>
                                            <th>Lời nhắn</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </thead>
                                        <tbody>
                                            <tr v-for="order in orders" :key="order.id">
                                                <td v-html="order.id"></td>
                                                <td v-html="order.message"></td>
                                                <td v-html="order.amount"></td>
                                                <td v-html="order.status"></td>
                                                <td><span title="xem chi tiết" class="btn btn-info" @click="orderDetail(order.id)"><i class='far fa-edit'></i></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchOrders(pagination.prev_page_url)" >Previous</a></li>
                                            <li class="page-item disabled"><a class="page-link text-light" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
                                            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a @click="fetchOrders(pagination.next_page_url)" class="page-link" href="#">Next</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <Model ref="modelOrder"  @showSuccess="showSuccess" @unshowModel="unshowModel" />
        <footer class="footer">
          <div class="footer__block block no-margin-bottom">
            <div class="container-fluid text-center">
            </div>
          </div>
        </footer>
    </div>
</template>
<script>
import Model from './ordermodel'
export default {
    components : {
        Model
    },
    data () {
        return {
            orders : [],
            pagination: {},
            showModel : false,
            type : "3",
        }
    },
    created () {
        this.fetchOrders()
    },
    methods : {
        fetchOrders(page_url) {
            let url = page_url || '/api/orders/'
            let vm = this;
            this.$http.get(url)
            .then(res => {
                this.orders = res.data.orders.data
                vm.makePagination(res.data.orders);
            })
            .catch(err => console.log(err))
        },
        makePagination(data) {
            let pagination = {
                current_page    : data.current_page,
                last_page       : data.last_page,
                next_page_url   : data.next_page_url,
                prev_page_url   : data.prev_page_url
            };
            this.pagination = pagination;
        },
        showSuccess() {
            this.showModel = true
        },
        unshowModel() {
            this.showModel = false
        },
        orderDetail(id) {
            this.$refs.modelOrder.fetchData(id)
        },
        search() {
            if(this.type == 3){
                this.fetchOrders()
            }else{
                let vm = this;
                this.$http.get(`/api/orders/type/${this.type}`)
                .then(res => {
                    this.orders = res.data.orders.data
                    vm.makePagination(res.data.orders);
                })
                .catch(err => console.log(err))
            }
        }
    }
}
</script>
