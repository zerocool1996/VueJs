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
                    <li class="breadcrumb-item"><a href="#">khách hàng</a></li>
                    <li class="breadcrumb-item active">Danh sách khách hàng</li>
                </ul>
                <ul class="breadcrumb">
                    <li>
                        <!-- <span class="btn btn-sm btn-primary btn-dark" style="border: 1px solid gray" @click="showModelCustomer(null)">Thêm mới khách hàng</span> -->
                    </li>
                </ul>
            </div>
            <section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <input class="form-control col-md-3 mb-1 float-right" type="text" placeholder="Search.." v-model="keyword" @keydown.enter="search">
                            <!-- <select class="custom-select col-md-3 mb-1" title="Số bản ghi trên 1 trang" v-model="per_page" @change="search">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="15">15</option>
                            </select> -->

                        </div>
                        <div class="col-lg-12">
                            <div class="block margin-bottom-sm">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tên khách hàng</th>
                                                <th>Email</th>
                                                <th>Hình ảnh</th>
                                                <th>Giới tính</th>
                                                <th>Xem chi tiết</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="customer in customers" v-bind:key="customer.id">
                                                <th scope="row">{{ customer.id }}</th>
                                                <td>{{ customer.fullname }}</td>
                                                <td>{{ customer.email }}</td>
                                                <td><img v-bind:src=" customer.image " width="40px" alt="hình ảnh"></td>
                                                <td>{{ customer.sex }}</td>
                                                <td>
                                                   <span title="edit" class="btn btn-info"><i class='far fa-edit' @click="showModelCustomer(customer.id)"></i></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchCustomers(pagination.prev_page_url)" >Previous</a></li>
                                            <li class="page-item disabled"><a class="page-link text-light" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
                                            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a @click="fetchCustomers(pagination.next_page_url)" class="page-link" href="#">Next</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <Model ref="modelCustomer" @showModelSuccess="showModelSuccess" @unshowModel="unshowModel" />
        <footer class="footer">
          <div class="footer__block block no-margin-bottom">
            <div class="container-fluid text-center">
            </div>
          </div>
        </footer>
    </div>
</template>
<script>
import Model from './model'
export default {
    components: { Model },
    data () {
        return {
            customers    : [],
            pagination  : {},
            keyword     : '',
            per_page    : '',
            showModel   : false
        }
    },

    created () {
        this.fetchCustomers();
    },

    methods: {
        fetchCustomers(page_url) {
            let vm = this;
            page_url = page_url || 'http://localhost/day2006/public/api/customers';
            this.$http.get(page_url).then(res => {
                this.customers = res.data.customers.data
                this.per_page = res.data.customers.per_page
                vm.makePagination(res.data.customers);
            })
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
        search () {
            if(this.keyword != ""){
                let vm = this;
                let url =`http://localhost/day2006/public/api/customers/search/${this.keyword}`;
                this.$http.get(url)
                .then(res => {
                    this.customers = res.data.customers.data
                    vm.makePagination(res.data.customers);
                })
            }else{
                this.fetchCustomers()
            }
        },
        showModelCustomer(id) {
            this.$refs.modelCustomer.fetchData(id)
        },
        showModelSuccess() {
            this.showModel = true
        },
        unshowModel() {
            this.showModel = false
        },
    },

    computed : {

    }

};
</script>
