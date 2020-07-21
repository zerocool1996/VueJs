<template>
    <div :class="[isShow ? 'd-block' : 'd-none']">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" @click.prevent="backToProduct">Sản phẩm</a></li>
                <li class="breadcrumb-item active">{{ isEdit ? 'Chỉnh sửa sản phẩm' : 'Thêm mới sản phẩm'}}</li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="block col-sm-11">
                            <div class="title"><strong>{{ isEdit ? 'Chỉnh sửa' : 'Thêm mới' }}</strong></div>
                            <div class="block-body">
                                <form class="form-horizontal" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Tên sản phẩm:*</strong>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" v-model="product.name" placeholder="Nhập tên sản phẩm"  autocomplete="off" required >
                                            <div v-if="errors.name" class=" alert alert-danger">
                                                {{ errors.name[0] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Giá:*</strong>
                                        <div class="col-sm-9">
                                            <input placeholder="Nhập giá (vnd)" v-model="product.price" type="number" class="form-control" required>
                                            <div v-if="errors.price" class=" alert alert-danger">
                                                {{ errors.price[0] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Giới thiệu:*</strong>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" v-model="product.content" rows="4" required></textarea>
                                            <div v-if="errors.content" class=" alert alert-danger">
                                                {{ errors.content[0] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Tác giả:*</strong>
                                        <div class="col-sm-9">
                                            <select class="authors form-control" v-model="product.author_id">
                                                <option v-for="author in authors" v-bind:key="author.id" :value="author.id"> {{ author.name }}                </option>
                                            </select>
                                            <div v-if="errors.author_id" class=" alert alert-danger">
                                                {{ errors.author_id[0] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Thể loại:*</strong>
                                        <div class="col-sm-9">
                                            <select class="custom-select categories" multiple v-model="product.categories">
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
                                            <input ref="imageProduct" name="img" type="file" class="form-control" @change="changeImage">
                                            <div v-if="errors.img" class=" alert alert-danger">
                                                {{ errors.img[0] }}
                                            </div>
                                            <img v-if="img" :src="img" class="img-responsive"   width="350" >
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <div class="col-sm-9 ml-auto">
                                            <button type="reset" class="btn btn-danger"
                                            @click="backToProduct">Hủy</button>
                                            <button type="submit" class="btn btn-success" @click.prevent="storeAndUpdate">Hoàn Thành</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    data(){
        return {
            product: {
                name: null,
                price: null,
                content: null,
                categories: [],
                author_id: null,
                img: null
            },
            img : null,
            id: null,
            errors: {},
            categories  : [],
            authors     : [],
            isShow: false,
            isEdit: false,
            errors: {},
        }
    },
    created() {
        this.getAllCategories()
        this.getAllAuthors()
    },
    methods: {
        fetchData (id) {
            if (id !== null) {
                this.$http.get(`/api/product/edit/${id}`).then(res => {
                    this.isEdit = true
                    this.id = id
                    this.product = {
                        name: res.data.product.name,
                        price: res.data.product.price,
                        content: res.data.product.content,
                        categories: res.data.product.product_category.map(item => {
                            return item.id
                        }),
                        author_id: res.data.product.author_id,
                        //img: res.data.product.image,
                    }
                    this.img = res.data.product.image
                    this.$emit('showSuccess')
                    this.isShow = true
                }).catch(err => {
                    this.isShow = false
                    this.$emit('showError')
                })
            } else {
                this.isEdit = false
                this.isShow = true
                this.$emit('showSuccess')
            }
        },
        backToProduct() {
            this.product = {
                name: null,
                price: null,
                content: null,
                categories: [],
                author_id: null,
                img: null
            }
            this.id = null
            this.errors = {}
            this.isShow = false
            this.isEdit = false
            this.$emit('showError')
        },
        storeAndUpdate() {
            if (this.id === null) {
                this.$http.post('/api/product/store', this.product).then(res => {
                    this.backToProduct()
                    this.$emit('createSuccess')
                }).catch(err => {
                    this.errors = err.response.data.errors;
                    window.izitoast.error({
                        title: 'Error',
                        message: 'Tạo mới không thành công',
                    });
                })
            } else {
                this.$http.post(`/api/product/update/${this.id}`, this.product).then(res => {
                    this.backToProduct()
                    this.$emit('updateSuccess')
                }).catch(err => {
                    this.errors = err.response.data.errors;
                    window.izitoast.error({
                        title: 'Error',
                        message: 'Chỉnh sửa không thành công',
                    });
                })
            }
        },
        changeImage() {
            const file = this.$refs.imageProduct.files[0]
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                this.product.img = e.target.result
                this.img = e.target.result
            }
        },
        getAllCategories() {
            this.$http.get('/api/categories/all').then(res => {
                this.categories = res.data.categories
            })
        },
        getAllAuthors() {
            this.$http.get('/api/authors/all').then(res => {
                this.authors = res.data.authors
            })
        }
    },


}
</script>
