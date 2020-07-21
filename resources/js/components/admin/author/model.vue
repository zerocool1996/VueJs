<template>
    <div :class="[isShow ? 'd-block' : 'd-none']">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" @click.prevent="backToAuthors">Tác giả</a></li>
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
                                        <strong class="col-sm-3 form-control-label">Tên tác giả:*</strong>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" v-model="author.name" placeholder="Nhập tên sản phẩm"  autocomplete="off" required >
                                            <div v-if="errors.name" class=" alert alert-danger">
                                                {{ errors.name[0] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Hình ảnh:*</strong>
                                        <div class="col-sm-9">
                                            <input ref="imageAuthor" name="img" type="file" class="form-control" @change="changeImage">
                                            <div v-if="errors.img" class=" alert alert-danger">
                                                {{ errors.img[0] }}
                                            </div>
                                            <img v-if="author.img" :src="author.img" class="img-responsive"   width="350" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Tiểu sử:</strong>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" v-model="author.story" rows="4"></textarea>
                                            <div v-if="errors.story" class=" alert alert-danger">
                                                {{ errors.story[0] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Giới thiệu:</strong>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" v-model="author.des" rows="4"></textarea>
                                            <div v-if="errors.des" class=" alert alert-danger">
                                                {{ errors.des[0] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <div class="col-sm-9 ml-auto">
                                            <button type="reset" class="btn btn-danger"
                                            @click="backToAuthors">Hủy</button>
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
            author: {
                name    : '',
                des     : '',
                story   : '',
                img     : '',
            },
            id          : null,
            errors      : {},
            isShow: false,
            isEdit: false,
        }
    },
    created() {

    },
    methods: {
        fetchData (id) {
            if (id !== null) {
                this.$http.get(`/api/authors/edit/${id}`).then(res => {
                    this.isEdit = true
                    this.id = id
                    this.author = {
                        name    : res.data.author.name,
                        des     : res.data.author.des,
                        story   : res.data.author.story,
                        img     : res.data.author.img
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
        changeImage() {
            const file = this.$refs.imageAuthor.files[0]
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                this.author.img = e.target.result
            }
        },
        backToAuthors() {
            this.author = {
                name    : '',
                story   : '',
                des     : '',
                img     : '',
            }
            this.id = null
            this.errors = {}
            this.isShow = false
            this.isEdit = false
            this.$emit('showError')
        },
        storeAndUpdate() {
            if (this.id === null) {
                this.$http.post('/api/authors/store', this.author).then(res => {
                    this.backToAuthors()
                    this.$emit('createSuccess')
                }).catch(err => {
                    this.errors = err.response.data.errors;
                    window.izitoast.error({
                        title: 'Error',
                        message: 'Tạo mới không thành công',
                    });
                })
            } else {
                this.$http.post(`/api/authors/update/${this.id}`, this.author).then(res => {
                    this.backToAuthors()
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
    },


}
</script>
