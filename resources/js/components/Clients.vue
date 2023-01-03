<template>
    <div data-app>
        <v-app>
            <v-snackbar v-model="snackbar" :vertical="vertical">
                {{ text }}
                <template v-slot:action="{ attrs }">
                    <v-btn color="success" text v-bind="attrs" @click="snackbar = false">
                        Close
                    </v-btn>
                </template>
            </v-snackbar>

            <v-data-table :headers="headers" :items="items" :items-per-page="5" class="elevation-1">
                <template v-slot:item.name="{ item }">
                    <span>{{ item.name }} {{ item.lastname }}</span>
                </template>

                <template v-slot:item.total="{ item }">
                    <span>$ {{ item.total ?? 0 }} </span>
                </template>


                <template v-slot:top>
                    <v-toolbar flat>
                        <v-toolbar-title>Clients</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>

                        <v-dialog v-model="dialog">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn color="success" dark v-bind="attrs" v-on="on" @click="showDialog">
                                    ADD CLIENT
                                </v-btn>
                            </template>

                            <v-card>
                                <v-card-title class="text-h5 grey lighten-2">
                                    {{ title }}
                                </v-card-title>

                                <v-card-text>
                                    <form-component :title="title" :client="client" :payments="payments"
                                        @client-registered="successAddClient">
                                    </form-component>
                                </v-card-text>
                            </v-card>
                        </v-dialog>

                    </v-toolbar>
                </template>

                <template v-slot:item.actions="{ item }">
                    <v-icon small class="mr-2" @click="editItem(item)">
                        mdi-pencil
                    </v-icon>
                    <v-icon small @click="deleteItem(item)">
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </v-app>
    </div>
</template>
<script>
import Form from './Form.vue';

export default {
    data() {
        return {
            title: 'Add Client',
            vertical: false,
            items: [],
            snackbar: false,
            text: 'I love snacks',
            valid: true,
            form: {},
            client: {},
            payments: [{
                transaction_id: '',
                amount: '',
                date: '',
                menu: false
            }],
            menu: false,
            menuPayment: false,

            dialog: false,
            headers: [
                {
                    text: 'Client Name',
                    align: 'start',
                    sortable: false,
                    value: 'name',
                },
                { text: 'DOB', value: 'dob', formatter: this.formatDate },
                { text: 'Phone', value: 'phone' },
                { text: 'Email', value: 'email' },
                { text: 'Address', value: 'address' },
                { text: 'Payments', value: 'quantity' },
                { text: 'Total', value: 'total', formatter: this.formatCurrency },
                { text: 'Actions', value: 'actions', sortable: false }
            ],

        }
    },
    components: {
        'form-component': Form
    },
    mounted() {
        this.listClients();
    },
    methods: {
        showDialog() {
            this.dialog = true;
            this.title = 'Add Client';
            this.client = {
                name: "",
                lastname: "",
                dob: "",
                phone: "",
                email: "",
                address: "",
            };
            this.payments = [{
                transaction_id: '',
                amount: '',
                date: '',
                menu: false
            }];
        },

        editItem(item) {
            this.dialog = true;
            this.title = 'Edit Client';
            this.client = {
                id: item.id,
                name: item.name,
                lastname: item.lastname,
                dob: item.dob,
                phone: item.phone,
                email: item.email,
                address: item.address,
            };
            this.listClientPayments(item.id);
        },

        listClientPayments: async function (id) {
            const response = await this.$axios.get(`/listClientPayments/${id}`);
            this.payments = response.data;
        },


        successAddClient(client) {
            this.listClients();
            this.dialog = false;
            this.text = 'Client registered successfully';
            this.snackbar = true;

            setTimeout(() => {
                this.snackbar = false
            }, 2000)
        },
        deleteItem(item) {

        },
        formatCurrency(value) {
            return "$ " + (value / 100).toFixed(2);
        },
        formatDate(date) {
            const d = new Date(date)
            const day = d.getDate()
            const monthIndex = d.getMonth()
            const year = d.getFullYear()
            return `${day}/${monthIndex}/${year}`
        },


        listClients: async function () {
            const response = await this.$axios.get('/listClients');
            this.items = response.data;
        },


    },
}
</script>
