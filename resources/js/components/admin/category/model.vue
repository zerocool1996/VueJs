<template>
    <div :class="[isShow ? 'd-block' : 'd-none']">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" @click.prevent="backToCategories">Danh mục</a></li>
                <li class="breadcrumb-item active">{{ isEdit ? 'Chỉnh sửa danh mục' : 'Thêm mới danh mục'}}</li>
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
                                        <strong class="col-sm-3 form-control-label">Tên danh mục:*</strong>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" v-model="category.name" placeholder="Nhập tên sản phẩm"  autocomplete="off" required >
                                            <div v-if="errors.name" class=" alert alert-danger">
                                                {{ errors.name[0] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Mô tả:*</strong>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" v-model="category.des" rows="4" required></textarea>
                                            <div v-if="errors.des" class=" alert alert-danger">
                                                {{ errors.des[0] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Sản phẩm danh mục:*</strong>
                                        <div class="col-sm-9">
                                            <select class="authors form-control" v-model="category.products" multiple>
                                                <option v-for="product in products" v-bind:key="product.id" :value="product.id"> {{ product.name }}                </option>
                                            </select>
                                            <div v-if="errors.products" class=" alert alert-danger">
                                                {{ errors.products[0] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-9 ml-auto">
                                            <button type="reset" class="btn btn-danger"
                                            @click="backToCategories">Hủy</button>
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
            category: {
                name    : '',
                des     : '',
                products: [],
            },
            id          : null,
            errors      : {},
            products    : [],
            isShow: false,
            isEdit: false,
        }
    },
    created() {
        this.getAllProducts()
    },
    methods: {
        fetchData (id) {
            if (id !== null) {
                this.$http.get(`/api/categories/edit/${id}`).then(res => {
                    this.isEdit = true
                    this.id = id
                    this.category = {
                        name    : res.data.category.name,
                        des     : res.data.category.des,
                        products: res.data.category.category_products.map(item => {
                            return item.id
                        }),
                    }
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
        backToCategories() {
            this.category = {
                name: '',
                products: [],
                des: '',
            }
            this.id = null
            this.errors = {}
            this.isShow = false
            this.isEdit = false
            this.$emit('showError')
        },
        storeAndUpdate() {
            if (this.id === null) {
                this.$http.post('/api/categories/store', this.category).then(res => {
                    this.backToCategories()
                    this.$emit('createSuccess')
                }).catch(err => {
                    this.errors = err.response.data.errors;
                    window.izitoast.error({
                        title: 'Error',
                        message: 'Tạo mới không thành công',
                    });
                })
            } else {
                this.$http.post(`/api/categories/update/${this.id}`, this.category).then(res => {
                    this.backToCategories()
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
        getAllProducts () {
            this.$http.get('api/product/all')
            .then(res => {
                this.products = res.data.products
            })
        }
    },


}
</script>
