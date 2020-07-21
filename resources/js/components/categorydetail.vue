<template>
    <div class="media p-1">
        <div class="media-body">
            <h5 class="mt-0" >Thể loại : {{ category.name }}</h5>
            <strong>Mô tả:</strong><br>
            <p v-html="category.des"></p>
            <p class="mb-0">Sản phẩm :
                <span class="mr-1" v-for="product in category.products" :key="product.id"><a href="#" @click.prevent="ProductDetail(product.id)">{{ product.name }}</a>, </span>
            </p>
        </div>
    </div>
</template>
<script>
export default {
    data () {
        return {
            category : {
                name    : null,
                des     : null,
                products: [],
            },
        }
    },
    created () {
        this.fetchCategory()
        this.$eventBus.$on('categoryDetail', this.fetchCategory)
    },
    methods : {
        fetchCategory() {
            let id = this.$route.params.id
            this.$http.get(`/api/category/detail/${id}`)
            .then(res => {
                this.category = {
                    name    : res.data.category.name,
                    des     : res.data.category.des,
                    products: res.data.category.category_products,
                }
            })
            .catch(res => console.log(res))
        },
        ProductDetail (id) {
            this.$router.push({ name: 'product.detail', params: { id: id }})
        }
    }
}
</script>
