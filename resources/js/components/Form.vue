<template>
    <v-container>
        <h2 class="text-left">Personal Information</h2>
        <v-form ref="form" v-model="valid" lazy-validation>
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field label="First Name" v-model="form.name" :rules="nameRules"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field label="Last Name" v-model="form.lastname" :rules="lastnameRules"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="form.dob"
                            transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field v-model="form.dob" label="DOB" type="date" :rules="dobRules"
                                    prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="form.dob" no-title scrollable>
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" @click="menu = false">
                                    Cancel
                                </v-btn>
                                <v-btn text color="primary" @click="$refs.menu.save(form.dob)">
                                    OK
                                </v-btn>
                            </v-date-picker>
                        </v-menu>
                    </v-col>

                    <v-col cols="12" sm="6" md="4">
                        <v-text-field label="Phone" :rules="phoneRules" v-model="form.phone"></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="6" md="4">
                        <v-text-field label="Email" :rules="emailRules" v-model="form.email"></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="6" md="4">
                        <v-text-field label="Address" :rules="addressRules" v-model="form.address"></v-text-field>
                    </v-col>

                    <v-col cols="6" sm="6" md="6">
                        <h2 class="text-left">PAYMENTS</h2>

                    </v-col>


                    <v-col cols="6" sm="6" md="6">
                        <v-btn class="mx-2" @click="addMore()" fab dark color="primary">
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
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field label="Amount" v-model="payment.amount" type="number" prefix="$"
                                    :rules="amountRules"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
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
                        </v-row>
                    </v-col>

                    <v-col cols="12" sm="12" md="12">
                        <v-btn :disabled="!valid" color="green" class="mr-4" v-if="title !== 'Edit Client'"
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
export default {
    props: {
        title: {
            type: String,
            required: true
        },
        client: {
            type: Object,
            required: false
        }
    },
    data() {
        return {
            valid: true,
            menu: false,
            payments: [{
                transaction_id: '',
                amount: '',
                date: '',
                menu: false
            }],
            form: {
                name: '',
                lastname: '',
                dob: '',
                phone: '',
                email: '',
                address: ''
            },
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
                v => (v && v.length <= 10) || 'Phone must be less than 10 characters'
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
                v => (v && v.length <= 10) || 'Transaction ID must be less than 10 characters'
            ],
            amountRules: [
                v => !!v || 'Amount is required',
                v => (v && v.length <= 10) || 'Amount must be less than 10 characters'
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

        addClient: async function () {
            let form = {
                client: this.form,
                payments: this.payments
            }
            const { data, status } = await this.$axios.post('/addClient', form);
            if (status === 200 && data[0].success) {
                this.listClients();
                this.dialog = false;
                this.text = 'Client and Payments registered successfully';
                this.snackbar = true;
                this.$refs.form.reset()
                setTimeout(() => {
                    this.snackbar = false
                }, 2000)
                this.$emit('client-registered', true);
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

        listClients: async function () {
            const response = await this.$axios.get('/listClients');
            this.items = response.data;
        },
        editItem(item) {
            this.title = 'Edit Client';
            this.form = Object.assign({}, item);
            this.dialog = true;
            this.listClientPayments(item.id);

        },
        listClientPayments: async function (id) {
            const response = await this.$axios.get(`/listClientPayments/${id}`);
            this.payments = response.data;
        },
    },
}
</script>