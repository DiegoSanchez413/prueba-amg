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

            <v-data-table :headers="headers" :items="items" :items-per-page="10" class="elevation-1">
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

                        <v-dialog v-model="showForm">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn color="success" dark v-bind="attrs" v-on="on" @click="showAddDialog">
                                    ADD CLIENT
                                </v-btn>
                            </template>

                            <v-card>
                                <v-card-title class="text-h5 grey lighten-2">
                                    {{ title }}
                                </v-card-title>

                                <v-card-text>
                                    <form-component :title="title" :client="client" :payments="payments"
                                        @client-event="showAlert">
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
                                                        <v-icon class="mr-2" color="red">mdi-alert-circle</v-icon>
                                                        <span class="headline">Are you sure you want to delete this
                                                            client?</span>
                                                    </v-card-title>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn color="blue darken-1" text
                                                            @click="showDeleteForm = false">
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
    </div>
</template>
<script>
import Form from './Form.vue';
import { formatCurrency, formatDate } from '../helpers/helpers.js';
import { listClients } from '../api/client.js';

export default {
    data() {
        return {
            title: 'Add Client',
            formatCurrency: formatCurrency,
            formatDate: formatDate,
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
            menu: false,
            menuPayment: false,
            showDeleteForm: false,
            showForm: false,
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
        listClients().then((response) => {
            this.items = response;
        });
    },
    methods: {
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
            const response = await this.$axios.get(`/listClientPayments/${id}`);
            this.payments = response.data;
        },
        deleteClient: async function () {
            this.showDeleteForm = false;
            const { status } = await this.$axios.delete(`/deleteClient/${this.client.id}`);
            if (status === 200) {
                this.showAlert('Client deleted successfully');
            }
        },
    },
}
</script>
