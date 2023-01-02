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
                    <span>$ {{ item.total }} </span>
                </template>



                <template v-slot:top>
                    <v-toolbar flat>
                        <v-toolbar-title>Clients</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>

                        <v-dialog v-model="dialog">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn color="success" dark v-bind="attrs" v-on="on">
                                    ADD CLIENT
                                </v-btn>
                            </template>

                            <v-card>
                                <v-card-title class="text-h5 grey lighten-2">
                                    {{ title }}
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <h2 class="text-left">Personal Information</h2>
                                        <template>
                                            <v-form ref="form" v-model="valid" lazy-validation>
                                                <v-container>
                                                    <v-row>
                                                        <v-col cols="12" sm="6" md="4">
                                                            <v-text-field label="First Name" v-model="form.name"
                                                                :rules="nameRules"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="6" md="4">
                                                            <v-text-field label="Last Name" v-model="form.lastname"
                                                                :rules="lastnameRules"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="6" md="4">
                                                            <v-menu ref="menu" v-model="menu"
                                                                :close-on-content-click="false"
                                                                :return-value.sync="form.dob"
                                                                transition="scale-transition" offset-y min-width="auto">
                                                                <template v-slot:activator="{ on, attrs }">
                                                                    <v-text-field v-model="form.dob" label="DOB"
                                                                        :rules="dobRules" prepend-icon="mdi-calendar"
                                                                        readonly v-bind="attrs"
                                                                        v-on="on"></v-text-field>
                                                                </template>
                                                                <v-date-picker v-model="form.dob" no-title scrollable>
                                                                    <v-spacer></v-spacer>
                                                                    <v-btn text color="primary" @click="menu = false">
                                                                        Cancel
                                                                    </v-btn>
                                                                    <v-btn text color="primary"
                                                                        @click="$refs.menu.save(form.dob)">
                                                                        OK
                                                                    </v-btn>
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </v-col>

                                                        <v-col cols="12" sm="6" md="4">
                                                            <v-text-field label="Phone" :rules="phoneRules"
                                                                v-model="form.phone"></v-text-field>
                                                        </v-col>

                                                        <v-col cols="12" sm="6" md="4">
                                                            <v-text-field label="Email" :rules="emailRules"
                                                                v-model="form.email"></v-text-field>
                                                        </v-col>

                                                        <v-col cols="12" sm="6" md="4">
                                                            <v-text-field label="Address" :rules="addressRules"
                                                                v-model="form.address"></v-text-field>
                                                        </v-col>

                                                        <v-btn :disabled="!valid" color="green" class="mr-4"
                                                            v-if="title !== 'Edit Client'" @click="validate">
                                                            Save
                                                        </v-btn>

                                                        <v-btn :disabled="!valid" color="green" class="mr-4"
                                                            v-else @click="updateClient">
                                                            Update
                                                        </v-btn>
                                                    </v-row>
                                                </v-container>
                                            </v-form>
                                        </template>

                                    </v-container>


                                </v-card-text>
                            </v-card>
                        </v-dialog>

                        <v-dialog v-model="dialogPayment">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn color="success" dark v-bind="attrs" v-on="on">
                                    ADD PAYMENT
                                </v-btn>
                            </template>


                            <v-card>
                                <v-card-title class="text-h5 grey lighten-2">
                                    Add Payment
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-container>
                                            <v-row>
                                                <v-col cols="6" sm="6" md="6">
                                                    <h2 class="text-left">Payments</h2>
                                                </v-col>

                                                <v-col cols="6" sm="6" md="6">
                                                    <v-btn icon @click="addAnother">
                                                        <v-icon>mdi-plus</v-icon>
                                                    </v-btn>
                                                </v-col>

                                            </v-row>
                                        </v-container>
                                        <template>
                                            <v-form ref="formPayment" v-model="validPayment" lazy-validation>
                                                <v-container>
                                                    <v-row>
                                                        <v-col cols="12" sm="6" md="3">
                                                            <v-select :items="items" label="Clients" item-value="id"
                                                                item-text="fullname" v-model="formPayment.clientId"
                                                                :rules="clientIdRules"></v-select>
                                                        </v-col>

                                                        <v-col cols="12" sm="6" md="3">
                                                            <v-text-field label="Transaction ID"
                                                                v-model="formPayment.transaction_id"
                                                                :rules="transactionIdRules"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="6" md="3">
                                                            <v-text-field label="Amount" v-model="formPayment.amount"
                                                                prefix="$" :rules="amountRules"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="6" md="3">
                                                            <v-menu ref="menuPayment" v-model="menuPayment"
                                                                :close-on-content-click="false"
                                                                :return-value.sync="formPayment.date"
                                                                transition="scale-transition" offset-y min-width="auto">
                                                                <template v-slot:activator="{ on, attrs }">
                                                                    <v-text-field v-model="formPayment.date"
                                                                        label="Date" :rules="dateRules"
                                                                        prepend-icon="mdi-calendar" readonly
                                                                        v-bind="attrs" v-on="on"></v-text-field>
                                                                </template>
                                                                <v-date-picker v-model="formPayment.date" no-title
                                                                    scrollable>
                                                                    <v-spacer></v-spacer>
                                                                    <v-btn text color="primary"
                                                                        @click="menuPayment = false">
                                                                        Cancel
                                                                    </v-btn>
                                                                    <v-btn text color="primary"
                                                                        @click="$refs.menuPayment.save(formPayment.date)">
                                                                        OK
                                                                    </v-btn>
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </v-col>

                                                        <v-btn :disabled="!validPayment" color="green" class="mr-4"
                                                            @click="validatePayment">
                                                            Save
                                                        </v-btn>
                                                    </v-row>
                                                </v-container>
                                            </v-form>
                                        </template>
                                    </v-container>
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


export default {
    data() {
        return {
            title: 'Add Client',
            vertical: false,
            dialogPayment: false,
            items: [],
            snackbar: false,
            text: 'I love snacks',
            nameRules: [
                v => !!v || 'Name is required',
            ],
            lastnameRules: [
                v => !!v || 'Last Name is required',
            ],
            dobRules: [
                v => !!v || 'DOB is required',
            ],
            dateRules: [
                v => !!v || 'Date is required',
            ],
            phoneRules: [
                v => !!v || 'Phone is required',
            ],
            emailRules: [
                v => !!v || 'Email is required',
            ],
            addressRules: [
                v => !!v || 'Address is required',
            ],
            amountRules: [
                v => !!v || 'Amount is required',
                v => /^\d+$/.test(v) || 'Amount must be a number',
            ],
            transactionIdRules: [
                v => !!v || 'Transaction Id is required',
            ],
            clientIdRules: [
                v => !!v || 'Client is required',
            ],
            valid: true,
            listOfPayments: [],
            validPayment: true,
            form: {},
            formPayment: {},
            payment: {},
            editedItem: {},
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
    mounted() {
        this.listClients();
    },


    methods: {
        editItem(item) {
            this.title = 'Edit Client';
            this.form = Object.assign({}, item);
            this.dialog = true;

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
        addAnother: function () {
            const valid = this.$refs.formPayment.validate()
            if (valid) {
                this.listOfPayments.push(Object.assign({}, this.formPayment));
                console.log(this.listOfPayments);
                this.$refs.formPayment.reset()
            } else {
                alert('Please fill all the fields');
            }
        },
        validate() {
            const valid = this.$refs.form.validate()
            if (valid) {
                this.addClient();
                this.dialog = false;
                this.text = 'Client Added Successfully';
                this.snackbar = true;
                this.$refs.form.reset()
                setTimeout(() => {
                    this.snackbar = false
                }, 2000)
            }
        },
        validatePayment: async function () {
            const { status } = await this.$axios.post('/addPayment', this.listOfPayments);
            if (status === 200) {
                this.listClients();
                this.dialogPayment = false;
                this.text = 'Payments Added Successfully';
                this.snackbar = true;
                this.$refs.formPayment.reset()
                setTimeout(() => {
                    this.snackbar = false
                }, 2000)
            }
        },
        listClients: async function () {
            const response = await this.$axios.get('/listClients');
            this.items = response.data;
        },
        addClient: async function () {
            const { status } = await this.$axios.post('/addClient', this.form);
            if (status === 200) {
                this.listClients();
            }
        },

        updateClient: async function () {
            // verify if id is present
            console.log(this.form);
            const { status } = await this.$axios.put('/updateClient', this.form);
            if (status === 200) {
                this.listClients();
                this.dialog = false;
                this.text = 'Client Updated Successfully';
                this.snackbar = true;
                this.$refs.form.reset()
                setTimeout(() => {
                    this.snackbar = false
                }, 2000)
            }
        },
        addPayment: async function () {
            const { status } = await this.$axios.post('/addPayment', this.formPayment);
            if (status === 200) {
                this.listClients();
                this.dialogPayment = false;
                this.text = 'Payment Added Successfully';
                this.snackbar = true;
                this.$refs.formPayment.reset()
                setTimeout(() => {
                    this.snackbar = false
                }, 2000)
            }
        },
    },
}
</script>
