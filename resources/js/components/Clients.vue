<template>
    <v-app data-app>
        <v-snackbar v-model="snackbar" :vertical="vertical">
            {{ text }}
            <template v-slot:action="{ attrs }">
                <v-btn color="success" text v-bind="attrs" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>

        <v-card-title>
            Search Clients
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details readonly>
            </v-text-field>
        </v-card-title>


        <v-data-table :headers="headers" :search="search" :items="items" :items-per-page="10" class="elevation-1"
            :loading="loading" loading-text="Loading clients... Please wait">
            <template v-slot:item.name="{ item }">
                <span>{{ item.name }} {{ item.lastname }}</span>
            </template>

            <template v-slot:item.dob="{ item }">
                <span>{{ formatDate(item.dob) }}</span>
            </template>

            <template v-slot:item.quantity="{ item }">
                <v-chip :color="getColor(item.quantity)" dark>
                    {{ item.quantity ?? 0 }}
                </v-chip>
            </template>

            <template v-slot:item.total="{ item }">
                <span>$ {{ item.total ?? 0 }} </span>
            </template>

            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>Clients</v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-spacer></v-spacer>

                    <v-dialog v-model="showForm" persistent>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn color="success" dark v-bind="attrs" v-on="on" @click="showAddDialog">
                                ADD CLIENT
                            </v-btn>
                        </template>

                        <v-card>
                            <v-card-title class="text-h5 grey lighten-2">
                                <v-row>
                                    <v-col cols="11">
                                        <span>{{ title }}</span>
                                    </v-col>
                                    <v-col cols="1">
                                        <v-icon @click="closeDialog">mdi-close</v-icon>
                                    </v-col>
                                </v-row>
                            </v-card-title>

                            <v-card-text>
                                <form-component :title="title" :client="client" :payments="payments"
                                    :delete-payments="deletePayments" @client-event="showAlert">
                                </form-component>
                            </v-card-text>
                        </v-card>
                    </v-dialog>

                    <v-dialog v-model="showDeleteForm">
                        <v-card>
                            <v-card-title class="text-h5 grey lighten-2">
                                DELETE CLIENT
                            </v-card-title>

                            <v-card-text>
                                <v-container fluid>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-card>
                                                <v-card-title>
                                                    <v-icon color="red">mdi-alert-circle</v-icon>
                                                    <span class="headline"> Are you sure you want to delete this
                                                        client? This will be delete all payments</span>
                                                </v-card-title>
                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn color="blue darken-1" text @click="showDeleteForm = false">
                                                        Cancel
                                                    </v-btn>
                                                    <v-btn color="blue darken-1" text @click="deleteClient">
                                                        OK
                                                    </v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-dialog>

                </v-toolbar>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="showEditDialog(item)">
                    mdi-pencil
                </v-icon>
                <v-icon small @click="showDeleteDialog(item)">
                    mdi-delete
                </v-icon>
            </template>
        </v-data-table>
    </v-app>
</template>
<script>
import Form from './Form.vue';
import { formatCurrency, formatDate } from '../helpers/helpers.js';
import { listClients } from '../api/client.js';

export default {
    data() {
        return {
            formatDate: formatDate,
            loading: true,
            search: '',
            title: 'Add Client',
            vertical: false,
            items: [],
            snackbar: false,
            text: '',
            client: {},
            payments: [{
                transaction_id: '',
                amount: '',
                date: '',
                menu: false
            }],
            deletePayments: [],
            menu: false,
            menuPayment: false,
            showDeleteForm: false,
            showForm: false,
            headers: [
                { text: 'Client Name', value: 'name' },
                { text: 'DOB', value: 'dob' },
                { text: 'Phone', value: 'phone' },
                { text: 'Email', value: 'email' },
                { text: 'Address', value: 'address' },
                { text: 'Payments', value: 'quantity' },
                { text: 'Total', value: 'total' },
                { text: 'Actions', value: 'actions', sortable: false }
            ],
        }
    },
    components: {
        'form-component': Form
    },
    computed: {
        // formattedDate(date) {
        //     return formatDate(date);
        // },
    },
    mounted() {
        listClients().then((response) => {
            this.items = response;
            this.loading = false;
        });
    },
    methods: {
        getColor(quantity) {
            if (quantity > 3) {
                return 'green';
            } else if (quantity > 1) {
                return 'orange';
            } else {
                return 'red';
            }
        },
        closeDialog() {
            this.showForm = false;
            this.deletePayments = [];
        },
        showAddDialog() {
            this.showForm = true;
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
        showEditDialog(item) {
            this.showForm = true;
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
        showDeleteDialog(item) {
            this.showDeleteForm = true;
            this.client = {
                id: item.id,
                name: item.name,
                lastname: item.lastname,
                dob: item.dob,
                phone: item.phone,
                email: item.email,
                address: item.address,
            };
        },
        showAlert(text) {
            this.text = text;
            this.snackbar = true;
            if (text !== 'You can only add 5 payments') {
                listClients().then((response) => {
                    this.items = response;
                });
                this.showForm = false;
            }
            setTimeout(() => {
                this.snackbar = false
            }, 3000)
        },
        listClientPayments: async function (id) {
            const response = await this.$axios.get(`/api/listClientPayments/${id}`);
            this.payments = response.data;
        },
        deleteClient: async function () {
            this.showDeleteForm = false;
            const { status } = await this.$axios.delete(`/api/deleteClient/${this.client.id}`);
            if (status === 200) {
                this.showAlert('Client deleted successfully');
            }
        },
    },
}
</script>
