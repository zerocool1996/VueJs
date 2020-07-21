<template>
    <div :class="[isShow ? 'd-block' : 'd-none']">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" @click.prevent="backToCustomer">Khách hàng</a></li>
                <li class="breadcrumb-item active">Thông tin khách hàng</li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="block col-sm-11">
                            <div class="title"><strong>Thông tin</strong></div>
                            <div class="block-body">
                                <form class="form-horizontal" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Tên: </strong>
                                        <div class="col-sm-9">
                                            <p v-html="customer.first_name"></p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Họ:</strong>
                                        <div class="col-sm-9">
                                            <p v-html="customer.last_name"></p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Địa chỉ:</strong>
                                        <div class="col-sm-9">
                                            <p v-html="customer.address"></p>
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Giới tính:</strong>
                                        <div class="col-sm-9">
                                            <p v-html="customer.sex"></p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">SĐT:</strong>
                                        <div class="col-sm-9">
                                            <p v-html="customer.tel"></p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <strong class="col-sm-3 form-control-label">Hình ảnh:</strong>
                                        <div class="col-sm-9">
                                            <img v-if="customer.img" :src="customer.img" class="img-responsive" alt="hình ảnh" width="350" >
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <div class="col-sm-9 ml-auto">
                                            <button type="reset" class="btn btn-success"
                                            @click="backToCustomer">Back</button>
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
            customer: {
                first_name  : null,
                last_name   : null,
                email       : null,
                address     : null,
                tel         : null,
                img         : null,
                sex         : null,
            },
            isShow  : false,
        }
    },
    created() {
       
    },
    methods: {
        fetchData (id) {
            this.$http.get(`/api/customers/edit/${id}`).then(res => {
                this.customer = {
                    first_name  : res.data.customer.first_name,
                    last_name   : res.data.customer.last_name,
                    email       : res.data.customer.email,
                    address     : res.data.customer.address,
                    img         : res.data.customer.img,
                    tel         : res.data.customer.tel,
                    sex         : res.data.customer.sex,
                }
                this.$emit('showModelSuccess')
                this.isShow = true
            }).catch(err => {
                this.isShow = false
                this.$emit('unshowModel')
            })
        },
        backToCustomer() {
            this.customer = {
                first_name  : null,
                last_name   : null,
                email       : null,
                address     : null,
                tel         : null,
                img         : null,
                sex         : null,
            }
            this.isShow = false
            this.$emit('unshowModel')
        },
        changeImage() {
            const file = this.$refs.imageCustomer.files[0]
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                this.customer.img = e.target.result
            }
        },
    },


}
</script>
