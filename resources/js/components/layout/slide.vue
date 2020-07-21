<template>
<div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
  <!-- <ol class="carousel-indicators">
    <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
    <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
  </ol> -->
  <h5 class="text-center">Các sản phảm mới cập nhật</h5>
  <div class="carousel-inner" >
    <div class="carousel-item " v-for="product in products" :key="product.id" @click="ProductDetail(product.id)">
      <img :src="product.image" class="rounded mx-auto d-block" alt="..."  height="250px">
      <div class="carousel-caption d-none d-md-block">
        <h5 v-html="product.name"></h5>
        <p></p>
      </div>
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
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
            this.$http.get('/api/product/newest-update')
            .then(res => {
                this.products = res.data.products
                $(document).ready(() => {
                    $('.carousel-item:first-child').addClass('active');
                })
            })
            .catch(err => console.log(err))

        },
        ProductDetail (id) {
            this.$router.push({ name: 'product.detail', params: { id: id }})
            this.$eventBus.$emit('productDetail', id)
        }
    }
}
</script>
