<template>
    <div class="container-fluid">
        <div class="row p-0 m-0">
            <div class="card col-md-3 bg-light product" v-for="product in products" v-bind:key="product.id" @click="ProductDetail(product.id)" style="cursor: pointer">
                <img class="card-img-top" v-bind:src="product.image" alt="Card image cap" height="200px">
                <div class="card-body">
                    <h6 class="card-title"><strong v-html="product.name"></strong></h6>
                    <p class="card-text" v-html="product.content"></p>
                    <p class="card-text" >{{ product.price }} <strong class="text-red">vnd</strong></p>
                </div>
            </div>
            <nav aria-label="Page navigation example" class="col-md-12 p-2">
                <ul class="pagination">
                    <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchProducts(pagination.prev_page_url)" >Previous</a></li>
                    <li class="page-item disabled"><a class="page-link text-light" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
                    <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a @click="fetchProducts(pagination.next_page_url)" class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</template>
<script>
export default {
    data () {
        return {
            products    : [],
            pagination  : {},
            keyword     : '',
            per_page    : '',
            showModel   : false
        }
    },

    created () {
        this.fetchProducts();
        this.$eventBus.$on('search', this.fetchProducts)
    },
    methods: {
        fetchProducts(page_url) {
            let vm = this;
            this.keyword = this.$route.params.keyword
            page_url = page_url || `/api/search/${this.keyword}`;
            this.$http.get(page_url).then(res => {
                this.products = res.data.products.data
                this.per_page = res.data.products.per_page
                vm.makePagination(res.data.products);
            })
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
        ProductDetail (id) {
            this.$router.push({ name: 'product.detail', params: { id: id }})
        }
    },
}
</script>
<style scoped>

</style>
