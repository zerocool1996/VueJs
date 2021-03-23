
const cart = {
    namespaced: true,
    state: {
        cart : window.sessionStorage.getItem('cart') ? Object.values(JSON.parse(window.sessionStorage.getItem('cart'))) : []
    },
    mutations: {
        ADD_TO_CART (state, data) {
            let index = -1 // biến để xem thêm số lượng sản phẩm hay thêm sản phẩm mới
            let cart = state.cart
            for (let i=0; i<cart.length; i++) { // vòng lặp kiểm tra giỏ hàng
                if (cart[i].id === data.id ) { // nếu sản phẩm thêm đã tồn tại trong giỏ hàng
                    index = i // đặt index = vị trí của sản phẩm trong giỏ hàng
                    break // thoát khỏi vòng lặp
                }
            }
            if (index === -1) { // nếu biến index giữ nguyên
                cart.push(data) // thêm mới sản phẩm vào giỏ hàng
            } else { // nếu biến index đã bị gán thành vị trí sản phẩm trogn giỏ hàng
                cart[index].quantity += 1 // tăng số lượng sản phẩm trong giỏ hàng lên 1
            }
            state.cart = cart
            window.sessionStorage.setItem('cart', JSON.stringify(cart))
        },
        RESET_CART (state) {
            state.cart = []
            window.sessionStorage.setItem('cart', JSON.stringify(state.cart))
        },
        REMOVE_FROM_CARD (state, id) {
            let cart = state.cart
            cart = cart.filter(item => {
                return item.id !== id
            })
            state.cart = cart
            window.sessionStorage.setItem('cart', JSON.stringify(cart))
        },
        MINUS (state, id) {
            let cart = state.cart
            let index = -1
            for (let i = 0; i < cart.length; i++) {
                if(cart[i].id == id){
                    index = i
                    break;
                }
            }
            if (index != -1) {
                cart[index].quantity -= 1
            }
            state.cart = cart
            window.sessionStorage.setItem('cart', JSON.stringify(cart))
        },
        PLUS (state, id) {
            let cart = state.cart
            let index = -1
            for(let i= 0; i < cart.length; i++) {
                if(cart[i].id == id) {
                    index = i
                    break;
                }
            }
            if(index != -1) {
                cart[index].quantity +=1
            }
            state.cart = cart
            window.sessionStorage.setItem('cart', JSON.stringify(cart))
        },
        INIT(state, cart) {
            console.log(cart)
            let cart_init = []
            if (cart.length > 0) {
                for (let i = 0; i < cart.length; i++) {
                    let item = {
                        id : cart[i].product.id,
                        img : cart[i].product.image,
                        name : cart[i].product.name,
                        price : cart[i].product.price,
                        quantity: cart[i].quantity
                    }
                    cart_init.push(item)
                }
            }
            state.cart = cart_init
            window.sessionStorage.setItem('cart', JSON.stringify(cart_init))
        }
    },
    actions: {
        addToCart ({commit, dispatch}, data) {
            return new Promise((resolve, reject) => {
                window.axios.get(`/api/cart/add-product/${data.id}`).then(response => {
                    commit('ADD_TO_CART', data)
                    resolve(response)
                }).catch(err => {
                    reject(err)
                })
            })
        },
        removeFromCart({commit, dispatch}, id) {
            return new Promise((resolve, reject) => {
                window.axios.get(`/api/cart/delete-product/${id}`).then(response => {
                    commit ('REMOVE_FROM_CARD', id)
                    resolve(response)
                }).catch(err => {
                    reject(err)
                })
            })
        },
        resetCart ({commit}) {
            commit ('RESET_CART')
        },
        minus ({commit, dispatch}, {id, quantity}) {
            return new Promise((resolve, reject) => {
                window.axios.get(`/api/cart/minus/${id}/${quantity}`).then(response => {
                    commit ('MINUS', id)
                    resolve(response)
                }).catch(err => {
                    reject(err)
                })
            })
        },
        plus ({commit, dispatch}, {id, quantity}) {
            return new Promise((resolve, reject) => {
                window.axios.get(`/api/cart/plus/${id}/${quantity}`).then(response => {
                    commit ('PLUS', id)
                    resolve(response)
                }).catch(err => {
                    reject(err)
                })
            })
        },
        init ({commit, dispatch}) {
            return new Promise((resolve, reject) => {
                window.axios.post(`/api/cart/init`, this.state.cart).then(response => {
                    commit ('INIT', response.data.cart)
                }).catch(err => {
                    reject(err)
                })
            })
        }
    }
}

export default cart
