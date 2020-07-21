<template>
    <div class="pl-2 .bg-light">
        <h4>Sản phẩm bán chạy</h4>
        <ul class="list-unstyled p-4">
            <li class="media product-most-sale" v-for="product in products" :key="product.id" style="cursor: pointer" @click="ProductDetail(product.id)">
                <img :src="product.image" class="mr-3 " alt="Image" width="50px" height="60px">
                <div class="media-body">
                <h5 class="mt-0 mb-1" v-html="product.name"></h5>
                    {{ product.content }}
                    <br><p><small class="texi-danger">Đã bán: </small>{{ product.saled }}</p>
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    data () {
        return {
            products : [],
        }
    },
    created () {
        this.fetchProducts()
    },
    methods : {
        fetchProducts() {
            this.$http.get('/api/product/most-sale')
            .then(res => {
                this.products = res.data.products
            })
            .catch(err => console.log(err))
        },
        ProductDetail (id) {
            this.$router.push({ name: 'product.detail', params: { id: id }})
            this.$eventBus.$emit('productDetail')
        }
    }
}
</script>
<style scoped>
li .media .product-most-sale {
    border-bottom: 1px solid grey;
}
</style>
