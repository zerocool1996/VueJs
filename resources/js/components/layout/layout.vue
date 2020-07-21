<template>
    <div class="container-fluid">
        <menucustomer></menucustomer>
        <slide></slide>
        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <router-view></router-view>
            </div>
            <div class="col-lg-4 col-sm-12">
                <rightbar></rightbar>
            </div>
        </div>
    </div>
</template>
<script>
import Menu from "./menu";
import RightBar from "./rightBar";
import Slide from "./slide";
export default {
    components: {
        menucustomer: Menu,
        rightbar    : RightBar,
        slide       : Slide,
    }
}
</script>
<style scoped>
    * {
        padding: 0;
    }
    .row {
        padding: 20px;
        margin: 20px 0px;
    }
</style>
