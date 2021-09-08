<template>
    <div>
        <!-- Modal -->
        <transition name="bounce">
            <div class="modal" v-if="modal">
                <div class="content relative">
                    <LoaderComponent v-if="modalLoading"/>

                    <i class="fas fa-times close" @click="modal = false"></i>
                    <div class="header">{{ (adminID >= 1) ? 'Edit' : 'Add' }} Administrator</div>

                    <div class="flex flex-col gap-4">
                        <FieldInput :captalize="false" class="flex-1" v-model="admin.username.value" :fErrors="admin.username.errors" :fLabel="admin.username.label" @onInput="validateData('username')"/>
                        <FieldInput :captalize="false" class="flex-1" fType="password" v-model="admin.password.value" :fErrors="admin.password.errors" :fLabel="admin.password.label" @onInput="validateData('password')"/>
                    </div>

                    <div class="text-right mt-5">
                        <button class="button bg-black text-white mr-2" @click="modal = false">Cancel</button>
                        <button class="button bg-green-500 text-white" @click="submitAdmin()">Submit</button>
                    </div>
                </div>
            </div>
        </transition>

        <div class="flex gap-4 items-center">
            <div class="flex-1"><div class="header mb-0">Administrators</div></div>
            <div class="flex-initial">
                <button class="button bg-green-500 text-white" @click="modal = true">Add Administrator</button>
            </div>
        </div>

        <div class="card relative mt-4">
            <LoaderComponent v-if="adminsLoading"/>
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left border-collapse">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Last Modified</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(a, i) in admins" :key="i">
                            <td> {{a.username}} </td>
                            <td class="font-semibold">
                                <span v-if="a.active >= 1" class="text-blue-500">Active</span>
                                <span v-else class="text-red-500">Inactive</span>
                            </td>
                            <td> {{moment(a.updated_at).fromNow()}} </td>
                            <td>
                                <div class="flex gap-1 justify-end text-sm">
                                    <button title="Edit" @click="editAdmin(a)" class="button bg-green-500 text-white"><i class="fas fa-edit"></i></button>
                                    
                                    <button v-if="a.active >= 1" class="button bg-red-500 text-white" @click="setStatus(a)">Deactivate</button>
                                    <button v-else class="button bg-blue-500 text-white" @click="setStatus(a)">Activate</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="admins.length <= 0">
                            <td colspan="5">No data found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <AlertComponent v-model="alertError" :message="alertErrorMsg" status="warning" />
        <AlertComponent v-model="alertSuccess" message="Administrator data submitted successfully." status="success" />
    </div>
</template>
<script>
import { reactive } from 'vue'
import moment from 'moment'
import axios from 'axios'

import FormValidator from './Utils/FormValidator'

import LoaderComponent from './Utils/LoaderComponent.vue'
import FieldInput from './Utils/FieldInput.vue'
import AlertComponent from './Utils/AlertComponent.vue'

export default {
    inject: ['errorResponseMsg'],
    props: ['apiServerName', 'adminLevel'],
    components: {LoaderComponent, FieldInput, AlertComponent},
    setup () {
        const fv = FormValidator()

        const admin = reactive({
            username: { value:'', rule:'username', label:'Username' },
            password: { value:'', rule:'username', label:'Password' },
        })

        const validateData = (objKey='') => {
            if (admin[objKey]) {
                let d = admin[objKey]
                admin[objKey].errors = fv.validate(d.value, d.label)[d.rule](d.opts)

            } else {
                for (let k in admin) {
                    let d = admin[k]
                    admin[k].errors = fv.validate(d.value, d.label)[d.rule](d.opts)
                }
            }
        }

        return {admin, validateData}
    },
    data () {
        return {
            moment,
            modal: false,
            modalLoading: false,

            alertError: false,
            alertErrorMsg: '',
            alertSuccess: false,

            admins: [],
            adminsLoading: false,
            adminID: 0,
        }
    },
    created () {
        if (this.adminLevel >= 2) {
            this.$router.push('/admin/home')
        } else {
            this.getAdmins()
        }
    },
    methods: {
        getAdmins () {
            this.adminsLoading = true
            axios.get(`${this.apiServerName}admins?`)
                .then((res) => {
                    this.adminsLoading = false
                    this.admins = res.data
                })
                .catch((err) => {
                    this.adminsLoading = false
                    this.alertError = true
                    this.alertErrorMsg = this.errorResponseMsg(err)
                })
        },
        editAdmin (data) {
            this.adminID = data.id
            for (let k in this.admin) {
                if (k != 'password') this.admin[k].value = data[k]
            }
            this.modal = true
        },

        setStatus (data) {
            let stat = (data.active >= 1) ? 0 : 1
            if (stat <= 0) {
                if (!confirm("Are you sure you want to deactivate this admin?")) return false
            }

            this.adminsLoading = true
            axios.post(`${this.apiServerName}modify_admin`,{data:{active:stat}, id:data.id})
                .then((res) => {
                    this.alertSuccess = true
                    this.getAdmins()
                })
                .catch((err) => {
                    this.adminsLoading = false
                    this.alertError = true
                    this.alertErrorMsg = this.errorResponseMsg(err)
                })

        },
        submitAdmin () {
            this.validateData()

            let data = {}
            let error = 0
            for (let k in this.admin) {
                data[k] = this.admin[k].value
                if (this.admin[k].errors && this.admin[k].errors.length >= 1) error += Number(1)
            }

            if (error >= 1) {
                this.alertError = true
                this.alertErrorMsg = "Some fields have errors, please fill in the form correctly."
                return false
            }
            data.active = 1
            data.level = 2
            this.modalLoading = true
            axios.post(`${this.apiServerName}modify_admin`,{data:data, id:this.adminID})
                .then((res) => {
                    this.modalLoading = false
                    this.modalDocs = false
                    this.alertSuccess = true
                    this.getAdmins()
                })
                .catch((err) => {
                    this.modalLoading = false
                    this.alertError = true
                    this.alertErrorMsg = this.errorResponseMsg(err)
                })
        },
    },
    watch: {
        modal(val) {
            if (!val) {
                this.adminID = 0
                for (let k in this.admin) {
                    this.admin[k].errors = []
                    this.admin[k].value = ''
                }
            }
        },
    }
}
</script>