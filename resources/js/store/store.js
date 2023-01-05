import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)


const client = {
    state: {
        client: {
            name: "",
            lastname: "",
            dob: "",
            phone: "",
            email: "",
            address: "",
        },
        payments: [
            {
                transaction_id: '',
                amount: '',
                date: '',
                menu: false
            }
        ]
    },
    mutations: {
        setClient(state, data) {
            state.client = data.client
            state.payments = data.payments

            axios.post('/api/addClient', data).then(response => {
                console.log(response)
                // if (response.success) {
                //     state.client = data.client
                //     state.client.payments = data.payments
                // }
            })
        }
    }
}

const store = new Vuex.Store({
    modules: {
        client
    }
})

export default store 