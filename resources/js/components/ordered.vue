<template>
    <div>
        <div :class="showModel ? 'd-none' : 'd-block'">
            <h5 class="text-center text-danger ">Các đơn hàng đặt</h5>
            <table class="table table-bordered mb-2">
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
        <Model ref="modelOrder"  @showSuccess="showSuccess" @unshowModel="unshowModel" />
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
        }
    },
    created () {
        this.fetchOrders()
    },
    methods : {
        fetchOrders(page_url) {
            let url = page_url || '/api/order/ordered';
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
        }
    }
}
</script>
