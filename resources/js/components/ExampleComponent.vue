<template>
    <div data-app>
        <v-app>
            <div class="text-center">
                <v-dialog v-model="dialog">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn color="success" dark v-bind="attrs" v-on="on">
                            ADD CLIENT
                        </v-btn>
                    </template>

                    <v-card>
                        <v-card-title class="text-h5 grey lighten-2">
                            Add Client
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
                                                    <v-menu ref="menu" v-model="menu" :close-on-content-click="false"
                                                        :return-value.sync="form.dob" transition="scale-transition"
                                                        offset-y min-width="auto">
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-text-field v-model="form.dob" label="DOB"
                                                                :rules="dobRules" prepend-icon="mdi-calendar" readonly
                                                                v-bind="attrs" v-on="on"></v-text-field>
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

                                                <v-btn :disabled="!valid" color="green" class="mr-4" @click="validate">
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
            </div>
            <v-snackbar v-model="snackbar" :vertical="vertical">
                {{ text }}

                <template v-slot:action="{ attrs }">
                    <v-btn color="success" text v-bind="attrs" @click="snackbar = false">
                        Close
                    </v-btn>
                </template>
            </v-snackbar>
            <v-data-table :headers="headers" :items="items" :items-per-page="5" class="elevation-1"></v-data-table>
        </v-app>

    </div>

</template>

<script>

export default {
    data() {
        return {
            vertical: false,
            items : [],
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
            phoneRules: [
                v => !!v || 'Phone is required',
            ],
            emailRules: [
                v => !!v || 'Email is required',
            ],
            addressRules: [
                v => !!v || 'Address is required',
            ],
            valid: true,
            form: {

            },
            menu: false,

            dialog: false,
            headers: [
                {
                    text: 'Client Name',
                    align: 'start',
                    sortable: false,
                    value: 'fullname',
                },
                { text: 'DOB', value: 'dob' },
                { text: 'Phone', value: 'phone' },
                { text: 'Email', value: 'email' },
                { text: 'Address', value: 'address' },
                {text: 'Actions', value: 'actions', sortable: false}
            ],
       
        }
    },
    mounted() {
        this.listClients();
    },

    methods: {
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

        listClients: async function () {
            const response = await this.$axios.get('/listClients');
            this.items = response.data;
        },

        addClient: async function () {
            const {data, status} = await this.$axios.post('/addClient', this.form);
            if (status === 200) {
                this.listClients();
            }
        }
    },
}
</script>
