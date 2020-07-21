<template>
    <div class="media p-1">
        <img v-bind:src="author.img" class="align-self-center mr-3" alt="..." width="250px">
        <div class="media-body">
            <h5 class="mt-0">Tác giả : {{ author.name }}</h5>
            <strong>Giới thiệu:</strong><br>
            <p v-html="author.des"></p>
            <strong>Tiểu sử:</strong><br>
            <p v-html="author.story"></p>
            <p class="mb-0">Các tác phẩm :
                <span class="mr-1" v-for="product in author.products" :key="product.id"><a href="#" @click.prevent="ProductDetail(product.id)">{{ product.name }}</a>, </span>
            </p>
        </div>
    </div>
</template>
<script>
export default {
    data () {
        return {
            author : {
                name    : null,
                img     : null,
                story   : null,
                des     : null,
                products: [],
            },
        }
    },
    created () {
        this.fetchAuthor()
        this.$eventBus.$on('authorDetail', this.fetchAuthor)
    },
    methods : {
        fetchAuthor() {
            let id = this.$route.params.id
            this.$http.get(`/api/author/detail/${id}`)
            .then(res => {
                this.author = {
                    name    : res.data.author.name,
                    img     : res.data.author.img,
                    story   : res.data.author.story,
                    des     : res.data.author.des,
                    products: res.data.author.author_product,
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
