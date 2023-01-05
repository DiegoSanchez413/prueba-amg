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
                                <v-text-field v-model="client.dob" label="Date" type="date" :rules="dobRules"
                                    prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="client.dob" no-title scrollable :max="maxDate" label="date">
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
                        <v-btn class="mx-2" @click="addInput()" fab dark color="success" x-small>
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
                                            :max="maxDate" prepend-icon="mdi-calendar" readonly v-bind="attrs"
                                            v-on="on"></v-text-field>
                                    </template>
                                    <v-date-picker v-model="payment.date" no-title scrollable :max="today">

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
                            <v-col cols="12" sm="6" md="4">
                                <v-btn class="mx-2" @click="removeInput(index)" fab dark color="info" x-small>
                                    <v-icon dark>
                                        mdi-minus
                                    </v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-col>

                    <v-col cols="12" sm="12" md="12">
                        <v-btn :disabled="!valid" block color="green" type="primary" class="mr-4"
                            v-if="title !== 'Edit Client'" @click.prevent="validate">
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
        },
    },
    data() {
        return {
            deletePayments: [],
            maxDate: '',
            today: new Date().toISOString().substr(0, 10),
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
                v => !!v || 'DOB is required',

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
            ],
        }
    },
    // mounted() {
    //     console.log(this.$store.state.client.client)
    //     console.log(this.$store.state.client.payments)
    // },
    methods: {
        validate() {
            const clientValid = this.$refs.form.validate()
            if (clientValid) {
                this.addClient();
            }
        },

        addInput: function () {
            if (this.payments.length === 5) {
                this.$emit('client-event', 'You can only add 5 payments');
            }

            if (this.payments.length < 5) {
                this.payments.push({
                    transaction_id: Math.floor(Math.random() * 1000000000),
                    amount: Math.floor(Math.random() * 1000),
                    date: "2023-01-01",
                    menu: false,
                });
            }
        },
        removeInput: async function (index) {
            this.deletePayments.push(this.payments[index]);
            this.payments.splice(index, 1);
        },
        deletePayment: async function (transactionId) {
            const { status } = await this.$axios.delete(`/api/deletePayment/${transactionId}`);
            if (status === 200) {
                this.$emit('client-event', 'Payment deleted successfully');
                return true;
            }
            return false;
        },
        addClient: function () {
            let form = {
                client: this.client,
                payments: this.payments
            }
            this.$store.commit('setClient', form)
            console.log(this.$store.state.client.client)
            console.log(this.$store.state.client.payments)
            // const { data, status } = await this.$axios.post('/api/addClient', form);
            // if (status === 200 && data.success) {
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
            // }
        },

        updateClient: async function () {
            let form = {
                client: this.client,
                payments: this.payments,
                deletePayments: this.deletePayments
            }
            const { status } = await this.$axios.put('/api/updateClient', form);
            if (status === 200) {
                listClients().then((response) => {
                    this.items = response;
                });
                this.$emit('client-event', 'Client updated successfully');
            }
            this.deletePayments = [];
        },
    },
}
</script>