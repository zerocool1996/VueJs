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
                    <li class="breadcrumb-item"><a href="#">Danh mục</a></li>
                    <li class="breadcrumb-item active">Danh sách Danh mục</li>
                </ul>
                <ul class="breadcrumb">
                    <li>
                        <span class="btn btn-sm btn-primary btn-dark" style="border: 1px solid gray" @click="showModelCategory(null)">Thêm mới Danh mục</span>
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
                                <div class="table_products">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tên Danh mục</th>
                                                <th>Mô tả</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="category in categories" v-bind:key="category.id">
                                                <th scope="row">{{ category.id }}</th>
                                                <td>{{ category.name }}</td>
                                                <td>{{ category.des }}</td>
                                                <td>
                                                   <span title="edit" class="btn btn-info"><i class='far fa-edit' @click="showModelCategory(category.id)"></i></span>
                                                    <a :data-target="'#modal' + category.id"
                                                        data-toggle="modal" style="cursor:pointer" title="delete" class="btn btn-danger"><i class='fa fa-trash'></i></a>
                                                    <div class="modal fade" v-bind:id="'modal' + category.id " tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">                                                        <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Xóa danh mục này: {{ category.name }} ?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Bạn chắc chứ ?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng lại</button>
                                                                <button @click="deleteCategory(category.id)" type="button" data-dismiss="modal" class="btn btn-primary">Xóa</button>
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
                                            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchCategories(pagination.prev_page_url)" >Previous</a></li>
                                            <li class="page-item disabled"><a class="page-link text-light" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
                                            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a @click="fetchCategories(pagination.next_page_url)" class="page-link" href="#">Next</a></li>
                                        </ul>
                                    </nav>
                                    <span @click="showModelGarbage" title="thùng rác" class="btn btn-outline-danger float-right mt-2 mb-1"><i class="fas fa-recycle fa-spin" ></i></span>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <Model ref="modelCategory" @showSuccess="showSuccess" @showError="showError" @createSuccess="createSuccess" @updateSuccess="updateSuccess" />
        <Gabage ref="modelGarbage" @showGarbage="showGarbage" @unshowGarbage="unshowGarbage" />
        <footer class="footer">
          <div class="footer__block block no-margin-bottom">
            <div class="container-fluid text-center">
            </div>
          </div>
        </footer>
    </div>
</template>
<script>
import Model    from './model'
import Gabage   from './garbage'
export default {
    components: {
        Model,
        Gabage,
    },
    data () {
        return {
            categories  : [],
            pagination  : {},
            keyword     : '',
            per_page    : '',
            showModel   : false
        }
    },

    created () {
        this.fetchCategories();
    },

    methods: {
        fetchCategories(page_url) {
            let vm = this;
            page_url = page_url || 'http://localhost/day2006/public/api/categories/';
            this.$http.get(page_url).then(res => {
                this.categories = res.data.categories.data
                this.per_page   = res.data.categories.per_page
                vm.makePagination(res.data.categories);
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
        deleteCategory (id) {
            let url ='http://localhost/day2006/public/api/categories/delete/'+id;
            this.$http.get(url)
            .then(res =>{
                window.izitoast.success({
                    title : "Success",
                    message : res.data.message
                });
                this.fetchCategories();
            })
            .catch(err => console.log(err));
        },
        search () {
            if(this.keyword != ""){
                let vm = this;
                let url =`http://localhost/day2006/public/api/categories/search/${this.keyword}`;
                this.$http.get(url)
                .then(res => {
                    this.categories = res.data.categories.data
                    vm.makePagination(res.data.categories);
                })
            }else{
                this.fetchCategories()
            }
        },
        showModelCategory (id) {
            this.$refs.modelCategory.fetchData(id)
        },
        showSuccess () {
            this.showModel = true
        },
        showError () {
            this.showModel = false
        },
        createSuccess () {
            this.fetchCategories()
            window.izitoast.success({
                title: 'Success',
                message: 'Tạo mới thành công',
            });
        },
        updateSuccess () {
            this.fetchCategories()
            window.izitoast.success({
                title: 'Success',
                message: 'Chỉnh sửa thành công',
            });
        },
        showModelGarbage () {
            this.$refs.modelGarbage.fetchCategories()
        },
        showGarbage () {
            this.showModel = true;
        },
        unshowGarbage () {
            this.showModel = false;
        }
    },

    computed : {

    }

};
</script>
