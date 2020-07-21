<template>
    <div>
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Trang Quản Trị Hệ Thống</h2>
            </div>
	    </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Sản phẩm</a></li>
                <li class="breadcrumb-item active">Danh sách sản phẩm bị xóa</li>
            </ul>
        </div>
        <section class="no-padding-top">
		<div class="container-fluid">
			<div class="row">
                <div class="col-lg-12">
                    <input class="form-control col-md-3 mb-1 float-right" type="text" placeholder="Search.." v-model="keyword" @keydown.enter="search">
                    <!-- <select class="selectpage form-control col-md-3 mb-1">
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
									</tr>
								</thead>
								<tbody>
                                    <tr v-for="product in products" v-bind:key="product.id">
                                        <th scope="row">{{ product.id }}</th>
                                        <td>{{ product.name }}</td>
                                        <td><img v-bind:src=" product.img " width="40px" alt="hình ảnh"></td>
                                        <td>{{ product.price }}</td>
                                        <td>{{ product.content }}</td>
                                        <td>
                                            <button @click="restore(product.id)" title="Khôi phục" class="btn btn-info"><i class='fas fa-trash-restore'></i></button>
                                            <a :data-target="'#modal' + product.id"
                                                data-toggle="modal" style="cursor:pointer" title="delete" class="btn btn-danger"><i class='fa fa-trash'></i></a>
                                            <div class="modal fade" v-bind:id="'modal' + product.id " tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Xóa vĩnh viễn sản phẩm này !</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Bạn chắc chứ ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng lại</button>
                                                        <button @click="forcedeleteProduct(product.id)" type="button" data-dismiss="modal" class="btn btn-primary">Xóa</button>
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
            products   : [],
            pagination : {},
            keyword    : '',
        }
    },

    created () {
        this.fetchProducts();
    },

    methods: {
        fetchProducts(page_url) {
            let vm = this;
            page_url = page_url || 'http://localhost/day2006/public/api/product/garbage';
            this.$http.get(page_url)
            //.then(res => {res.json()})
            .then(res => {
                this.products = res.data.products.data;
                vm.makePagination(res.data.products);
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
        forcedeleteProduct(id){
            let url ='http://localhost/day2006/public/api/product/forcedelete/'+id;
            this.$http.get(url)
            .then(res =>{
                window.izitoast.success({
                    title: 'Success',
                    message: res.data.message,
                });
                this.fetchProducts();
            })
            .catch(err => console.log(err));
        },
        restore (id) {
            let url ='http://localhost/day2006/public/api/product/restore/'+id;
            this.$http.get(url)
            .then(res =>{
                window.izitoast.success({
                    title: 'Success',
                    message: res.data.message,
                });
                this.fetchProducts();
            })
            .catch(err => console.log(err));
        },
        search () {
            if(this.keyword != ""){
                let vm = this;
                let url =`http://localhost/day2006/public/api/product/search-deleted/${this.keyword}`;
                this.$http.get(url)
                .then(res => {
                    this.products = res.data.products.data
                    vm.makePagination(res.data.products);
                })
            }else{
                this.fetchProducts()
            }
        },
    },

    computed : {

    }

};
</script>
