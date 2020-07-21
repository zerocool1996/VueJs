<template>
    <div :class="[isShow ? 'd-block' : 'd-none']">
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" @click="backToAuthors">Tác giả</a></li>
                <li class="breadcrumb-item active">Danh sách tác giả bị xóa</li>
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
                    <button class="btn btn-outline-success float-right " @click="backToAuthors">Back</button>
                </div>
				<div class="col-lg-12">
					<div class="block margin-bottom-sm">
						<div class="table-responsive">
							<table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên Tác giả</th>
                                        <th>Hình ảnh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="author in authors" v-bind:key="author.id">
                                        <th scope="row">{{ author.id }}</th>
                                        <td>{{ author.name }}</td>
                                        <td><img v-bind:src=" author.img " width="40px" alt="hình ảnh"></td>
                                        <td>
                                            <span @click="restore(author.id)" title="khôi phục" class="btn btn-info"><i class='fas fa-trash-restore'></i></span>
                                            <a :data-target="'#modal' + author.id"
                                                data-toggle="modal" style="cursor:pointer" title="delete" class="btn btn-danger"><i class='fa fa-trash'></i></a>
                                            <div class="modal fade" v-bind:id="'modal' + author.id " tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Xóa vĩnh viễn tác giả: {{ author.name }} ?</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Bạn chắc chứ ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng lại</button>
                                                        <button @click="forcedelete(author.id)" type="button" data-dismiss="modal" class="btn btn-primary">Xóa</button>
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
                                    <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchAuthors(pagination.prev_page_url)" >Previous</a></li>
                                    <li class="page-item disabled"><a class="page-link text-light" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
                                    <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a @click="fetchAuthors(pagination.next_page_url)" class="page-link" href="#">Next</a></li>
                                </ul>
                            </nav>
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
            authors  : [],
            pagination  : {},
            keyword     : '',
            isShow      : false
        }
    },

    methods: {
        fetchAuthors(page_url) {
            let vm = this;
            page_url = page_url || 'http://localhost/day2006/public/api/authors/garbage';
            this.$http.get(page_url).then(res => {
                this.authors = res.data.authors.data
                vm.makePagination(res.data.authors);
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
            let url ='http://localhost/day2006/public/api/authors/forcedelete/'+id;
            this.$http.get(url)
            .then(res =>{
                window.izitoast.success({
                    title: 'Success',
                    message: res.data.message,
                });
                this.fetchAuthors();
            })
            .catch(err => console.log(err));
        },
        restore(id) {
            let url ='http://localhost/day2006/public/api/authors/restore/'+id;
            this.$http.get(url)
            .then(res =>{
                window.izitoast.success({
                    title: 'Success',
                    message: res.data.message,
                });
                this.fetchAuthors();
            })
            .catch(err => console.log(err));
        },
        search () {
            if(this.keyword != ""){
                let vm = this;
                let url =`http://localhost/day2006/public/api/authors/search-deleted/${this.keyword}`;
                this.$http.get(url)
                .then(res => {
                    this.authors = res.data.authors.data
                    vm.makePagination(res.data.authors);
                })
            }else{
                this.fetchAuthors()
            }
        },
        backToAuthors () {
            this.isShow = false;
            this.$emit('unshowGarbage')
        }
    },

    computed : {

    }

};
</script>
