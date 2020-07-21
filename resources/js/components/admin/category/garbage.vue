<template>
    <div :class="[isShow ? 'd-block' : 'd-none']">
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" @click="backToCategories">Danh mục</a></li>
                <li class="breadcrumb-item active">Danh sách Danh mục bị xóa</li>
            </ul>
        </div>
        <section class="no-padding-top">
		<div class="container-fluid">
			<div class="row">
                <div class="col-lg-12">
                    <input class="form-control col-md-3 mb-1 float-left" type="text" placeholder="Search.." v-model="keyword" @keydown.enter="search">
                    <!-- <select class="selectpage form-control col-md-3 mb-1">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="15">15</option>
                    </select> -->
                    <button class="btn btn-outline-success float-right " @click="backToCategories">Back</button>
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
                                            <span @click="restore(category.id)" title="khôi phục" class="btn btn-info"><i class='fas fa-trash-restore'></i></span>
                                            <a :data-target="'#modal' + category.id"
                                                data-toggle="modal" style="cursor:pointer" title="delete" class="btn btn-danger"><i class='fa fa-trash'></i></a>
                                            <div class="modal fade" v-bind:id="'modal' + category.id " tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Xóa vĩnh viễn danh mục này: {{ category.name }} ?</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Bạn chắc chứ ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng lại</button>
                                                        <button @click="forcedelete(category.id)" type="button" data-dismiss="modal" class="btn btn-primary">Xóa</button>
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
                        </div>
					    </div>
					</div>
				</div>
			</div>
		</div>
	    </section>
        <footer class="footer">
          <div class="footer__block block no-margin-bottom">
            <div class="container-fluid text-center">
            </div>
          </div>
        </footer>
    </div>
</template>
<script>

export default {
    data () {
        return {
            categories  : [],
            pagination  : {},
            keyword     : '',
            isShow      : false
        }
    },

    methods: {
        fetchCategories(page_url) {
            let vm = this;
            page_url = page_url || 'http://localhost/day2006/public/api/categories/garbage';
            this.$http.get(page_url).then(res => {
                this.categories = res.data.categories.data
                vm.makePagination(res.data.categories);
                this.isShow     = true
                this.$emit('showGarbage')
            })
            .catch(err => console.log(err));
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
        forcedelete(id){
            let url ='http://localhost/day2006/public/api/categories/forcedelete/'+id;
            this.$http.get(url)
            .then(res =>{
                window.izitoast.success({
                    title: 'Success',
                    message: res.data.message,
                });
                this.fetchCategories();
            })
            .catch(err => console.log(err));
        },
        restore(id) {
            let url ='http://localhost/day2006/public/api/categories/restore/'+id;
            this.$http.get(url)
            .then(res =>{
                window.izitoast.success({
                    title: 'Success',
                    message: res.data.message,
                });
                this.fetchCategories();
            })
            .catch(err => console.log(err));
        },
        search () {
            if(this.keyword != ""){
                let vm = this;
                let url =`http://localhost/day2006/public/api/categories/search-deleted/${this.keyword}`;
                this.$http.get(url)
                .then(res => {
                    this.categories = res.data.categories.data
                    vm.makePagination(res.data.categories);
                })
            }else{
                this.fetchCategories()
            }
        },
        backToCategories () {
            this.isShow = false;
            this.$emit('unshowGarbage')
        }
    },

    computed : {

    }

};
</script>
