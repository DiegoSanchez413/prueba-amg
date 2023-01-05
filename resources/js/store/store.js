import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)


const client = new Vuex.Store({
    state: {
        client: {
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
        }
    },
    mutations: {
        setClient(state, data) {
            state.client = data
            state.client.payments = data.payments
        }
    }
})

export default client 