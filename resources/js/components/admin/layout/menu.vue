<template>
      <!-- Sidebar Navigation-->
      <nav id="sidebar">
        <!-- Sidebar Header-->
        <div class="sidebar-header d-flex align-items-center">
          <div class="avatar"><img src="https://sohojhisab.com/avatar.png" alt="..." class="img-fluid rounded-circle"></div>
          <div class="title">
            <h1 class="h5">{{ admin.fullname }}</h1>
            <p>Admin</p>
          </div>
        </div>
        <!-- Sidebar Navidation Menus--><span class="heading">Main</span>
        <ul class="list-unstyled">
                <li class="">
                    <router-link :to="{name: 'dashboard'}">
                        <i class="icon-home"></i>Home
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'product.index'}">
                        <i class="fab fa-product-hunt"></i>Sản phẩm
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'categories'}">
                        <i class="fas fa-align-justify"></i>Danh mục
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'authors'}">
                        <i class="fas fa-user"></i>Tác giả
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'customers'}">
                        <i class="fas fa-users"></i>khách hàng
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'orders'}">
                        <i class="fas fa-receipt"></i>Đơn hàng
                    </router-link>
                </li>
                <!-- <li><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Example dropdown </a>
                  <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                    <li><a href="#">Page</a></li>
                    <li><a href="#">Page</a></li>
                    <li><a href="#">Page</a></li>
                  </ul>
                </li>
                <li><a href="login.html"> <i class="icon-logout"></i>Login page </a></li> -->
        </ul><span class="heading">Extras</span>
        <!-- <ul class="list-unstyled">
          <li> <a href="#"> <i class="icon-settings"></i>Demo </a></li>
          <li> <a href="#"> <i class="icon-writing-whiteboard"></i>Demo </a></li>
          <li> <a href="#"> <i class="icon-chart"></i>Demo </a></li>
        </ul> -->
      </nav>
      <!-- Sidebar Navigation end-->
</template>
<script>
export default {
    data() {
        return {
            admin: JSON.parse(window.localStorage.getItem('admin'))
        }
    },
    created () {
        this.getAdmin()
    },
    methods: {
        getAdmin(){
            setTimeout(()=>{
                this.admin = JSON.parse(window.localStorage.getItem('admin')) || null
            }, 1500)
        }
    }
}
</script>
