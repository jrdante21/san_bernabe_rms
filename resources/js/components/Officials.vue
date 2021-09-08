<template>
    <div>
        <!-- Modal -->
        <transition name="bounce">
            <div class="modal" v-if="modal">
                <div class="content relative">
                    <LoaderComponent v-if="modalLoading"/>

                    <i class="fas fa-times close" @click="modal = false"></i>
                    <div class="header">{{ (officialID >= 1) ? 'Edit' : 'Add' }} Barangay Official</div>

                    <div class="flex flex-col gap-4">
                        <div class="flex-1">
                            <div class="flex gap-4 items-center">
                                <FieldInput class="flex-1" fType="select" :fOpts="officialsTitles" v-model="official.title.value" :fErrors="official.title.errors" :fLabel="official.title.label" @onInput="validateData('title')"/>
                                <FieldInput class="flex-1" fType="select" :fOpts="officialsPositions" v-model="official.position.value" :fErrors="official.position.errors" :fLabel="official.position.label" @onInput="validateData('position')"/>
                            </div>
                        </div>
                        <FieldInput class="flex-1" v-model="official.fname.value" :fErrors="official.fname.errors" :fLabel="official.fname.label" @onInput="validateData('fname')"/>
                        <FieldInput class="flex-1" v-model="official.mname.value" :fErrors="official.mname.errors" :fLabel="official.mname.label" @onInput="validateData('mname')"/>
                        <FieldInput class="flex-1" v-model="official.lname.value" :fErrors="official.lname.errors" :fLabel="official.lname.label" @onInput="validateData('lname')"/>
                    </div>

                    <div class="text-right mt-5">
                        <button class="button bg-black text-white mr-2" @click="modal = false">Cancel</button>
                        <button class="button bg-green-500 text-white" @click="submitOfficial()">Submit</button>
                    </div>
                </div>
            </div>
        </transition>

        <transition name="bounce">
            <div class="modal" v-if="modalSetLogin">
                <div class="content relative">
                    <LoaderComponent v-if="modalSetLoginLoading"/>

                    <i class="fas fa-times close" @click="modalSetLogin = false"></i>
                    <div class="header">Set Login Barangay Official Information</div>

                    <div class="flex flex-col gap-4">
                        <FieldInput :captalize="false" class="flex-1" v-model="login.username.value" :fErrors="login.username.errors" :fLabel="login.username.label" @onInput="validateLogin('username')"/>
                        <FieldInput :captalize="false" class="flex-1" fType="password" v-model="login.password.value" :fErrors="login.password.errors" :fLabel="login.password.label" @onInput="validateLogin('password')"/>
                    </div>

                    <div class="text-right mt-5">
                        <button class="button bg-black text-white mr-2" @click="modalSetLogin = false">Cancel</button>
                        <button class="button bg-green-500 text-white" @click="submitLogin()">Submit</button>
                    </div>
                </div>
            </div>
        </transition>


        <div class="flex gap-4 items-center">
            <div class="flex-1"><div class="header mb-0">Barangay Officials</div></div>
            <div class="flex-initial">
                <button class="button bg-green-500 text-white" @click="modal = true">Add Barangay Officials</button>
            </div>
        </div>
        <div class="card mt-4 relative">
            <LoaderComponent v-if="officialsLoading"/>
            <FieldInput class="w-1/3 mb-4" v-model="searchOfficials" fLabel="Search Barangay Official"/>
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left border-collapse">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Username</th>
                            <th>Last Modified</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(o, i) in officials.data" :key="i">
                            <td> {{o.name}} </td>
                            <td> {{o.position}} </td>
                            <td> 
                                <span v-if="o.username === null" class="italic text-yellow-600">Log-in information not set</span>
                                <span v-else>{{ o.username }}</span>
                            </td>
                            <td> {{moment(o.updated_at).fromNow()}} </td>
                            <td>
                                <div class="flex gap-1 justify-end text-sm">
                                    <button class="button bg-gray-300" @click="setLogin(o)" >Set Log-in</button>
                                    <button title="Edit" @click="editOfficial(o)" class="button bg-green-500 text-white"><i class="fas fa-edit"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="officials.data && officials.data.length <= 0">
                            <td colspan="5">No data found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationComponent class="inline-block mt-4" :links="officials.links" :totalData="officials.total" :perPage="officials.per_page"/>
        </div>

        <AlertComponent v-model="alertError" :message="alertErrorMsg" status="warning" />
        <AlertComponent v-model="alertSuccess" message="Barangay Official data submitted successfully." status="success" />
    </div>
</template>
<script>
import { reactive } from 'vue'
import moment from 'moment'
import axios from 'axios'

import FormValidator from './Utils/FormValidator'

import LoaderComponent from './Utils/LoaderComponent.vue'
import PaginationComponent from './Utils/PaginationComponent.vue'
import FieldInput from './Utils/FieldInput.vue'
import AlertComponent from './Utils/AlertComponent.vue'

export default {
    inject: ['errorResponseMsg'],
    props: ['apiServerName', 'adminLevel'],
    components: {LoaderComponent, PaginationComponent, AlertComponent, FieldInput},
    setup () {
        const fv = FormValidator()

        const official = reactive({
            fname: { value:'', rule:'alpha', label:'First Name' },
            mname: { value:'', rule:'alpha', label:'Middle Name' },
            lname: { value:'', rule:'alpha', label:'Last Name' },
            title: { value:'', rule:'alpha', label:'Title' },
            position: { value:'', rule:'alpha', label:'Position' },
        })

        const validateData = (objKey='') => {
            if (official[objKey]) {
                let d = official[objKey]
                official[objKey].errors = fv.validate(d.value, d.label)[d.rule](d.opts)

            } else {
                for (let k in official) {
                    let d = official[k]
                    official[k].errors = fv.validate(d.value, d.label)[d.rule](d.opts)
                }
            }
        }

        const login = reactive({
            username: { value:'', rule:'username', label:'Username' },
            password: { value:'', rule:'username', label:'Password' },
        })

        const validateLogin = (objKey='') => {
            if (login[objKey]) {
                let d = login[objKey]
                login[objKey].errors = fv.validate(d.value, d.label)[d.rule](d.opts)

            } else {
                for (let k in login) {
                    let d = login[k]
                    login[k].errors = fv.validate(d.value, d.label)[d.rule](d.opts)
                }
            }
        }

        return { official, validateData, login, validateLogin }
    },
    data () {
        return {
            moment,

            searchOfficials: '',

            modal: false,
            modalLoading: false,
            modalSetLogin: false,
            modalSetLoginLoading: false,

            alertError: false,
            alertErrorMsg: '',
            alertSuccess: false,

            officials: Object,
            officialsLoading: false,
            officialID: 0,
            officialsTitles: [
                {value:'Hon.', text:'Hon.'},
                {value:'Mr.', text:'Mr.'},
                {value:'Ms.', text:'Ms.'},
                {value:'Mrs.', text:'Mrs.'},
                {value:'Kgwd.', text:'Kgwd.'},
            ],
            officialsPositions: [
                {value:'Barangay Captain', text:'Barangay Captain'},
                {value:'Barangay Secretary', text:'Barangay Secretary'},
                {value:'Barangay Treasurer', text:'Barangay Treasurer'},
                {value:'Barangay Kagawad', text:'Barangay Kagawad'},
                {value:'R.I.C Purok President', text:'R.I.C Purok President'},
            ]
        }
    },
    created () {
        if (this.adminLevel >= 3) {
            this.$router.push('/admin/home')

        } else {
            this.getOfficials()
            this.searchOfficials = this.$route.query.search
            this.$watch(() => this.$route.query, () => {
                this.getOfficials()
            })
        }

    },
    methods: {
        getOfficials () {
            let page = this.$route.query.page || 1
            let search = this.$route.query.search || ''

            this.officialsLoading = true
            axios.get(`${this.apiServerName}officials?page=${page}&search=${search}`)
                .then((res) => {
                    this.officialsLoading = false
                    this.officials = res.data
                })
                .catch((err) => {
                    this.officialsLoading = false
                    this.alertError = true
                    this.alertErrorMsg = this.errorResponseMsg(err)
                })
        },
        editOfficial (data) {
            this.officialID = data.id
            for (let k in this.official) {
                this.official[k].value = data[k]
            }
            this.modal = true
        },
        submitOfficial () {
            this.validateData()

            let data = {}
            let error = 0
            for (let k in this.official) {
                data[k] = this.official[k].value
                if (this.official[k].errors && this.official[k].errors.length >= 1) error += Number(1)
            }

            if (error >= 1) {
                this.alertError = true
                this.alertErrorMsg = "Some fields have errors, please fill in the form correctly."
                return false
            }

            this.modalLoading = true
            axios.post(`${this.apiServerName}modify_official`,{data:data, id:this.officialID})
                .then((res) => {
                    this.modalLoading = false
                    this.modal = false
                    this.alertSuccess = true
                    this.getOfficials()
                })
                .catch((err) => {
                    this.modalLoading = false
                    this.alertError = true
                    this.alertErrorMsg = this.errorResponseMsg(err)
                })
        },

        setLogin (data) {
            this.officialID = data.id
            this.login.username.value = data.username
            this.modalSetLogin = true
        },
        submitLogin () {
            this.validateLogin()

            let data = {}
            let error = 0
            for (let k in this.login) {
                data[k] = this.login[k].value
                if (this.login[k].errors && this.login[k].errors.length >= 1) error += Number(1)
            }

            if (error >= 1) {
                this.alertError = true
                this.alertErrorMsg = "Some fields have errors, please fill in the form correctly."
                return false
            }

            this.modalSetLoginLoading = true
            axios.post(`${this.apiServerName}modify_official`,{data:data, id:this.officialID})
                .then((res) => {
                    this.modalSetLoginLoading = false
                    this.modalSetLogin = false
                    this.alertSuccess = true
                    this.getOfficials()
                })
                .catch((err) => {
                    this.modalSetLoginLoading = false
                    this.alertError = true
                    this.alertErrorMsg = this.errorResponseMsg(err)
                })
        }
    },
    watch: {
        searchOfficials (val) {
            this.$router.push({ query:Object.assign({}, this.$route.query, { search: val }) })
        },
        modal(val) {
            if (!val) {
                this.officialID = 0
                for (let k in this.official) {
                    this.official[k].errors = []
                    this.official[k].value = ''
                }
            }
        },
        modalSetLogin(val) {
            if (!val) {
                this.officialID = 0
                for (let k in this.login) {
                    this.login[k].errors = []
                    this.login[k].value = ''
                }
            }
        }
    }
}
</script>