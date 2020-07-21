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
                    <li class="breadcrumb-item"><a href="#">Sản phẩm</a></li>
                    <li class="breadcrumb-item active">Danh sách sản phẩm</li>
                </ul>
                <ul class="breadcrumb">
                    <li>
                        <!-- <router-link :to="{name:'product.create'}"> -->
                            <!-- <span class="btn btn-sm btn-primary btn-dark" style="border: 1px solid gray">Thêm mới sản phẩm</span> -->
                        <!-- </router-link> -->
                        <span class="btn btn-sm btn-primary btn-dark" style="border: 1px solid gray" @click="showModelProduct(null)">Thêm mới sản phẩm</span>
                    </li>
                </ul>
            </div>
            <section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <input class="form-control col-md-3 mb-1 float-right" id="search" type="text" placeholder="Search.." v-model="keyword" @keydown.enter="search">
                            <!-- <select class="custom-select col-md-3 mb-1" title="Số bản ghi trên 1 trang" v-model="per_page" @change="search">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="15">15</option>
                            </select> -->

                        </div>
                        <div class="col-lg-12">
                            <div class="block margin-bottom-sm">
                                <div class="table_products">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Hình ảnh</th>
                                                <th>Giá</th>
                                                <th>Giới thiệu</th>
                                                <th>Tác giả</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="product in products" v-bind:key="product.id">
                                                <th scope="row">{{ product.id }}</th>
                                                <td>{{ product.name }}</td>
                                                <td><img v-bind:src=" product.image " width="40px" alt="hình ảnh"></td>
                                                <td>{{ product.price }}</td>
                                                <td>{{ product.content }}</td>
                                                <td>{{ product.product_author.name }}</td>
                                                <td>
                                                    <!-- <router-link :to="{name:'product.edit', params:{ id : product.id }}"><span title="edit" class="btn btn-info"><i class='far fa-edit'></i></span></router-link> -->
                                                   <span title="edit" class="btn btn-info"><i class='far fa-edit' @click="showModelProduct(product.id)"></i></span>
                                                    <a :data-target="'#modal' + product.id"
                                                        data-toggle="modal" style="cursor:pointer" title="delete" class="btn btn-danger"><i class='fa fa-trash'></i></a>
                                                    <div class="modal fade" v-bind:id="'modal' + product.id " tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">                                                        <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Xóa sản phẩm này !</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Bạn chắc chứ ?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng lại</button>
                                                                <button @click="deleteProduct(product.id)" type="button" data-dismiss="modal" class="btn btn-primary">Xóa</button>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchProducts(pagination.prev_page_url)" >Previous</a></li>
                                            <li class="page-item disabled"><a class="page-link text-light" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
                                            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a @click="fetchProducts(pagination.next_page_url)" class="page-link" href="#">Next</a></li>
                                        </ul>
                                    </nav>
                                    <router-link :to="{name:'product.garbage'}">
                                        <p title="thùng rác" class="btn btn-outline-danger float-right mt-2"><i class="fas fa-recycle fa-spin"></i></p>
                                    </router-link>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <Model ref="modelProduct" @showSuccess="showSuccess" @showError="showError" @createSuccess="createSuccess" @updateSuccess="updateSuccess" />
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
            products    : [],
            pagination  : {},
            keyword     : '',
            per_page    : '',
            showModel   : false
        }
    },

    created () {
        this.fetchProducts();
    },

    methods: {
        fetchProducts(page_url) {
            let vm = this;
            page_url = page_url || 'http://localhost/day2006/public/api/product';
            // fetch(page_url)
            // .then(res => res.json())
            // .then(res => {
            //     this.products = res.data;
            //     vm.makePagination(res.current_page, res.last_page, res.next_page_url, res.prev_page_url);
            // })
            // .catch(err => console.log(err));
            this.$http.get(page_url).then(res => {
                this.products = res.data.products.data
                this.per_page = res.data.products.per_page
                vm.makePagination(res.data.products);
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
        deleteProduct(id){
            let url ='http://localhost/day2006/public/api/product/delete/'+id;
            this.$http.get(url)
            .then(res =>{
                window.izitoast.success({
                    title : "Success",
                    message : res.data.message
                });

                this.fetchProducts();
            })
            .catch(err => console.log(err));
        },
        search () {
            if(this.keyword != ""){
                let vm = this;
                let url =`http://localhost/day2006/public/api/product/search/${this.keyword}`;
                this.$http.get(url)
                .then(res => {
                    this.products = res.data.products.data
                    vm.makePagination(res.data.products);
                })
            }else{
                this.fetchProducts()
            }
        },
        showModelProduct(id) {
            this.$refs.modelProduct.fetchData(id)
        },
        showSuccess() {
            this.showModel = true
        },
        showError() {
            this.showModel = false
        },
        createSuccess() {
            this.fetchProducts()
            window.izitoast.success({
                title: 'Success',
                message: 'Tạo mới thành công',
            });
        },
        updateSuccess() {
            this.fetchProducts()
            window.izitoast.success({
                title: 'Success',
                message: 'Chỉnh sửa thành công',
            });
        }
    },

    computed : {

    }

};
</script>
