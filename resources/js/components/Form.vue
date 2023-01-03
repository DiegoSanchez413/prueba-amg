<template>
    <v-container data-app>
        <h2 class="text-left">Personal Information</h2>
        <v-form ref="form" v-model="valid" lazy-validation>
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field label="First Name" v-model="client.name" :rules="nameRules"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field label="Last Name" v-model="client.lastname" :rules="lastnameRules"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-menu ref="menu" v-model="menu" :close-on-content-click="false"
                            :return-value.sync="client.dob" transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field v-model="client.dob" label="DOB" type="date" :rules="dobRules"
                                    prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="client.dob" no-title scrollable>
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" @click="menu = false">
                                    Cancel
                                </v-btn>
                                <v-btn text color="primary" @click="$refs.menu.save(client.dob)">
                                    OK
                                </v-btn>
                            </v-date-picker>
                        </v-menu>
                    </v-col>

                    <v-col cols="12" sm="6" md="4">
                        <v-text-field label="Phone" :rules="phoneRules" v-model="client.phone"></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="6" md="4">
                        <v-text-field label="Email" :rules="emailRules" v-model="client.email"></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="6" md="4">
                        <v-text-field label="Address" :rules="addressRules" v-model="client.address"></v-text-field>
                    </v-col>

                    <v-col cols="6" sm="6" md="6">
                        <h2 class="text-left">PAYMENTS</h2>

                    </v-col>


                    <v-col cols="6" sm="6" md="6">
                        <v-btn class="mx-2" @click="addMore()" fab dark color="primary" v-if="title !== 'Edit Client'">
                            <v-icon dark>
                                mdi-plus
                            </v-icon>
                        </v-btn>
                    </v-col>

                    <v-col cols="12" sm="12" md="12" v-for="(payment, index) in payments" :key="index">
                        <v-row>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field label="Transaction ID" v-model="payments[index].transaction_id"
                                    type="number" :rules="transactionIdRules"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="2">
                                <v-text-field label="Amount" v-model="payment.amount" type="number" prefix="$"
                                    :rules="amountRules"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="2">
                                <v-menu ref="payment" v-model="payment.menu" :close-on-content-click="false"
                                    :return-value.sync="payment.date" transition="scale-transition" offset-y
                                    min-width="auto">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field v-model="payment.date" type="date" label="Date" :rules="dateRules"
                                            prepend-icon="mdi-calendar" readonly v-bind="attrs"
                                            v-on="on"></v-text-field>
                                    </template>
                                    <v-date-picker v-model="payment.date" no-title scrollable>

                                        <v-spacer></v-spacer>
                                        <v-btn text color="primary" @click="payment.menu = false">
                                            Cancel
                                        </v-btn>
                                        <v-btn text color="primary" @click="$refs.payment[index].save(payment.date)">
                                            OK
                                        </v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="12" sm="6" md="4" v-if="index > 0">
                                <v-btn class="mx-2" @click="removePayment(index)" fab dark color="red"
                                    v-if="title !== 'Edit Client'">
                                    <v-icon dark>
                                        mdi-minus
                                    </v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-col>

                    <v-col cols="12" sm="12" md="12">
                        <v-btn :disabled="!valid" block color="green" type="primary" class="mr-4" v-if="title !== 'Edit Client'"
                            @click="validate">
                            Save
                        </v-btn>

                        <v-btn :disabled="!valid" color="green" class="mr-4" v-else @click="updateClient">
                            Update
                        </v-btn>
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
    </v-container>
</template>
<script>
import { listClients } from '../api/client.js';

export default {
    props: {
        title: {
            type: String,
            required: true
        },
        client: {
            type: Object,
            required: true
        },
        payments: {
            type: Array,
            required: true,
            default: () => [
                {
                    transaction_id: '',
                    amount: '',
                    date: '',
                    menu: false
                }],

        }

    },
    data() {
        return {
            valid: true,
            menu: false,
            nameRules: [
                v => !!v || 'Name is required',
                v => (v && v.length <= 20) || 'Name must be less than 20 characters'
            ],
            lastnameRules: [
                v => !!v || 'Last Name is required',
                v => (v && v.length <= 20) || 'Last Name must be less than 20 characters'
            ],
            dobRules: [
                v => !!v || 'DOB is required'
            ],
            phoneRules: [
                v => !!v || 'Phone is required',
            ],
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+\..+/.test(v) || 'E-mail must be valid'
            ],
            addressRules: [
                v => !!v || 'Address is required',
                v => (v && v.length <= 50) || 'Address must be less than 50 characters'
            ],
            transactionIdRules: [
                v => !!v || 'Transaction ID is required',
            ],
            amountRules: [
                v => !!v || 'Amount is required',
            ],
            dateRules: [
                v => !!v || 'Date is required'
            ]
        }
    },
    methods: {
        validate() {
            const clientValid = this.$refs.form.validate()
            if (clientValid) {
                this.addClient();
            }
        },
        editClient() {
            alert('edit')
        },

        addClient: async function () {
            let form = {
                client: this.client,
                payments: this.payments
            }
            const { data, status } = await this.$axios.post('/addClient', form);
            if (status === 200 && data[0].success) {
                listClients().then((response) => {
                    this.items = response;
                });
                this.showForm = false;
                this.text = 'Client and Payments registered successfully';
                this.snackbar = true;
                this.$refs.form.reset()
                setTimeout(() => {
                    this.snackbar = false
                }, 2000)
                this.$emit('client-event', 'Client registered successfully');
            }
        },

        addMore: function () {
            if (this.payments.length < 5) {
                this.payments.push({
                    transaction_id: "",
                    amount: "",
                    date: "",
                    menu: false,
                });
            }
        },

        removePayment: function (index) {
            this.payments.splice(index, 1);
        },


        editItem(item) {
            this.title = 'Edit Client';
            this.form = Object.assign({}, item);
            this.showForm = true;
            this.listClientPayments(item.id);

        },

        updateClient: async function () {
            let form = {
                client: this.client,
                payments: this.payments
            }
            const { status } = await this.$axios.put('/updateClient', form);
            if (status === 200) {
                listClients().then((response) => {
                    this.items = response;
                });
                this.$emit('client-event', 'Client updated successfully');
            }
        },
    },
}
</script>