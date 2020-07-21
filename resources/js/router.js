import login from './components/admin/layout/login.vue';
import dashboard from './components/admin/layout/dashboard.vue';
import ProductIndex from './components/admin/product/index.vue';
import ProductGarbage from './components/admin/product/garbage.vue';
import AdminHome from './components/admin/layout/master.vue';
import Categories from './components/admin/category/index.vue';
import Authors from './components/admin/author/index.vue';
import Orders from './components/admin/order/index.vue';
import Customers from './components/admin/customer/index.vue';
import Home from './components/layout/layout.vue';
import Index from './components/index.vue';
import ProductDetail from './components/productdetail.vue';
import Signup from './components/layout/signup.vue';
import Cart from './components/cart.vue';
import Order from './components/order.vue';
import Return from './components/return.vue';
import Author from './components/authordetail.vue';
import Category from './components/categorydetail.vue';
import Ordered from './components/ordered.vue';
import CustomerInfor from './components/customerinfo.vue'
import Search from './components/search.vue'
export const routes = [
    {
        path: '/admin/login',
        name: 'login',
        component: login
    },
    {
        path: '/admin',
        component: AdminHome,
        meta: {
            requiresAuth: true
        },
        children: [
            { path: '/admin/dashboard', name: 'dashboard', component: dashboard },
            { path: '/admin/product', name: 'product.index', component: ProductIndex },
            { path: '/admin/product/garbage', name: 'product.garbage', component: ProductGarbage },
            { path: '/admin/categories', name: 'categories', component: Categories },
            { path: '/admin/authors', name: 'authors', component: Authors },
            { path: '/admin/customers', name: 'customers', component: Customers },
            { path: '/admin/orders', name: 'orders', component: Orders },
        ]
    },
    {
        path: '/signup',
        name: 'signup',
        component: Signup
    },
    {
        path: '/',
        //name: 'customer',
        component: Home,
        children: [
            { path: "", name: 'index', component: Index },
            { path: "/seach/:keyword", name: 'search', component: Search },
            { path: "/product/detail/:id", name: 'product.detail', component: ProductDetail },
            { path: "/cart", name: 'cart', component: Cart },
            { path: "/order", name: 'order', component: Order },

            { path: "/author/detail/:id", name: 'author.detail', component: Author } ,
            { path: "/category/detail/:id", name: 'category.detail', component: Category } ,
            { path: "/ordered", name: 'ordered', component: Ordered, meta: { requiresAuthCustomer: true }},
            { path: "/customer-info", name: 'customer.info', component: CustomerInfor, meta: { requiresAuthCustomer: true }},
        ]
    },
    {
        path: "/return",
        name: 'return',
        component: Return,
        meta: {
            requiresAuthCustomer: true
        }
    },
];
