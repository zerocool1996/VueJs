<template>
    <div>
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Trang Quản Trị Hệ Thống</h2>
            </div>
	    </div>

        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Sản phẩm</a></li>
                <li class="breadcrumb-item active">Thêm mới sản phẩm</li>
            </ul>
        </div>
        <section class="no-padding-top">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12">
                    <div class="block col-sm-11">
                        <div class="title"><strong>Thêm mới</strong></div>
                        <div class="block-body">
                            <form class="form-horizontal" enctype="multipart/form-data" @submit.prevent="create">
                                <div class="form-group row">
                                    <strong class="col-sm-3 form-control-label">Tên sản phẩm:*</strong>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" v-model="name" placeholder="Nhập tên sản phẩm"  autocomplete="off" required >
                                    </div>
                                    <div v-if="errors.name" class=" alert alert-danger">
                                        {{ errors.name[0] }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <strong class="col-sm-3 form-control-label">Giá:*</strong>
                                    <div class="col-sm-9">
                                        <input placeholder="Nhập giá (vnd)" v-model="price" type="number" class="form-control" required>
                                    </div>
                                    <div v-if="errors.price" class=" alert alert-danger">
                                        {{ errors.price[0] }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <strong class="col-sm-3 form-control-label">Giới thiệu:*</strong>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" v-model="content" rows="4" required></textarea>
                                    </div>
                                    <div v-if="errors.content" class=" alert alert-danger">
                                        {{ errors.content[0] }}
                                    </div>
                                </div>
                                <div class="line"></div>
                                <div class="form-group row">
                                    <strong class="col-sm-3 form-control-label">Tác giả:*</strong>
                                    <div class="col-sm-9">
                                        <select class="authors form-control" v-model="author">
                                            <option v-for="author in authors" v-bind:key="author.id" :value="author.id"> {{ author.name }}                </option>
                                        </select>
                                        <div v-if="errors.author" class=" alert alert-danger">
                                            {{ errors.author[0] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <strong class="col-sm-3 form-control-label">Thể loại:*</strong>
                                    <div class="col-sm-9">
                                        <select class="custom-select categories" multiple v-model="p_categories">
                                            <option v-for="category in categories" v-bind:key="category.id" :value="category.id"> {{ category.name }}                </option>
                                        </select>
                                        <div v-if="errors.categories" class=" alert alert-danger">
                                            {{ errors.categories[0] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <strong class="col-sm-3 form-control-label">Hình ảnh:*</strong>
                                    <div class="col-sm-9">
                                        <input name="img" type="file" v-on:change="onImageChange" class="form-control">
                                        <div v-if="errors.img" class=" alert alert-danger">
                                            {{ errors.img[0] }}
                                        </div>
                                        <img v-if="image" :src="image" class="img-responsive"   width="350" >
                                    </div>
                                </div>
                                <div class="line"></div>
                                <div class="form-group row">
                                    <div class="col-sm-9 ml-auto">
                                        <button type="reset" class="btn btn-danger"
                                        @click="backToProduct">Hủy</button>
                                        <button type="submit" class="btn btn-success">Hoàn Thành</button>
                                    </div>
                                </div>
                            </form>
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
    data(){
        return {
            image       : '',
            name        : '',
            price       : '',
            content     : '',
            categories  : [],
            p_categories: [],
            authors     : [],
            author      : '',
            errors      : {},
        }
    },
    created () {
        this.fetchData()
    },
    methods: {
        fetchData () {
            // alert(this.$route.params.id);
            let id = this.$route.params.id;
            let url = "http://localhost/day2006/public/api/product/create";
            fetch(url)
            .then(res => res.json())
            .then(res => {
                // console.log(res);
                this.authors        = res.authors;
                this.categories     = res.categories;
            })
            .catch(err => console.log(err));
        },
        onImageChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.image = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        create () {
            axios.post("http://localhost/day2006/public/api/product/store", {
                name            : this.name,
                price           : this.price,
                content         : this.content,
                img             : this.image,
                author_id       : this.author,
                categories      : this.p_categories,
            })
            .then(res => res.json())
            .then(res => {
                this.$toast.success(res, "Success", {timeout:2000} );
            })
            .catch(err => {
                console.log('Something went wrong !');
                this.errors = err.response.data.errors;
            });
        },
        backToProduct() {
            this.$router.push({
                name: 'product.index'
            })
        }
    },


}
</script>
