<template>
    <div>
        <!-- Modal -->
        <transition name="bounce">
            <div class="modal" v-if="modalUsername">
                <div class="content relative">
                    <LoaderComponent v-if="modalUsernameLoading"/>

                    <i class="fas fa-times close" @click="modalUsername = false"></i>
                    <div class="header">Edit Username</div>

                    <div class="flex flex-col gap-4">
                        <FieldInput :captalize="false" class="flex-1" v-model="username.value" :fErrors="username.errors" :fLabel="username.label" @onInput="validateUsername()"/>
                    </div>

                    <div class="text-right mt-5">
                        <button class="button bg-black text-white mr-2" @click="modalUsername = false">Cancel</button>
                        <button class="button bg-green-500 text-white" @click="submitUsername()">Submit</button>
                    </div>
                </div>
            </div>
        </transition>

        <transition name="bounce">
            <div class="modal" v-if="modalPassword">
                <div class="content relative">
                    <LoaderComponent v-if="modalPasswordLoading"/>

                    <i class="fas fa-times close" @click="modalPassword = false"></i>
                    <div class="header">Change Password</div>

                    <div class="flex flex-col gap-4">
                        <FieldInput :captalize="false" fType="password" class="flex-1" v-model="password.old_password.value" :fErrors="password.old_password.errors" :fLabel="password.old_password.label" @onInput="validatePassword('old_password')"/>
                        <FieldInput :captalize="false" fType="password" class="flex-1" v-model="password.password.value" :fErrors="password.password.errors" :fLabel="password.password.label" @onInput="validatePassword('password')"/>
                        <FieldInput :captalize="false" fType="password" class="flex-1" v-model="password.conf_password.value" :fErrors="password.conf_password.errors" :fLabel="password.conf_password.label" @onInput="validatePassword('conf_password')"/>
                    </div>

                    <div class="text-right mt-5">
                        <button class="button bg-black text-white mr-2" @click="modalPassword = false">Cancel</button>
                        <button class="button bg-green-500 text-white" @click="submitPassword()">Submit</button>
                    </div>
                </div>
            </div>
        </transition>


        <div class="dropdown">
            <button class="button bg-transparent text-white border border-white border-solid">
                <span class="mr-4">{{ adminUsername }}</span> <i class="fas fa-angle-down"></i>
            </button>
            <div class="dropdown-content p-2 w-48 bg-white right-0">
                <div class="menu-col">
                    <a @click="modalUsername = true" class="item">Edit Username</a>
                    <a @click="modalPassword = true" class="item">Change Password</a>
                    <a href="/logout" class="item">Logout</a>
                </div>
            </div>
        </div>

        <AlertComponent v-model="alertError" :message="alertErrorMsg" status="warning" />
        <AlertComponent v-model="alertSuccess" :message="alertSuccessMsg" status="success" />
    </div>
</template>
<script>
import { reactive } from 'vue'
import axios from 'axios'
import FormValidator from './Utils/FormValidator'

import LoaderComponent from './Utils/LoaderComponent.vue'
import FieldInput from './Utils/FieldInput.vue'
import AlertComponent from './Utils/AlertComponent.vue'

export default {
    props: ['admin'],
    inject: ['errorResponseMsg'],
    components: {LoaderComponent, FieldInput, AlertComponent},
    setup (props) {
        const fv = FormValidator()

        const username = reactive({value:props.admin.username, rule:'username', label:'Username' })

        const validateUsername = () => {
            username.errors = fv.validate(username.value, username.label)[username.rule](username.opts)
        }

        const password = reactive({
            old_password: {value:'', rule:'username', label:'Old Password'},
            password: {value:'', rule:'username', label:'New Password'},
            conf_password: {value:'', rule:'username', label:'Confirm New Password'},
        })

        const validatePassword = (objKey='') => {
            if (password[objKey]) {
                let d = password[objKey]
                password[objKey].errors = fv.validate(d.value, d.label)[d.rule](d.opts)

            } else {
                for (let k in password) {
                    let d = password[k]
                    password[k].errors = fv.validate(d.value, d.label)[d.rule](d.opts)
                }
            }
        }

        return { username, validateUsername, password, validatePassword }
    },
    data () {
        return {
            adminUsername: '',

            modalUsername: false,
            modalUsernameLoading: false,
            modalPassword: false,
            modalPasswordLoading: false,

            alertError: false,
            alertErrorMsg: '',
            alertSuccess: false,
            alertSuccessMsg: '',
        }
    },
    created () {
        this.adminUsername = this.admin.username
    },
    methods: {
        submitUsername () {
            this.validateUsername()
            if (this.username.errors !== undefined && this.username.errors.length >= 1) return false

            this.modalUsernameLoading = true
            axios.post('/api/edit_account', { username:this.username.value })
                .then((res) => {
                    this.modalUsernameLoading = false
                    this.modalUsername = false
                    this.alertSuccess = true
                    this.alertSuccessMsg = "Username updated successfully!"
                    this.adminUsername = this.username.value
                })
                .catch((err) => {
                    this.modalUsernameLoading = false
                    this.alertError = true
                    this.alertErrorMsg = this.errorResponseMsg(err)
                })
        },
        submitPassword () {
            this.validatePassword()
            let data = {}
            let error = 0

            for (let k in this.password) {
                if (this.password[k].errors && this.password[k].errors.length >= 1) error += Number(1)
            }

            if (error >= 1) {
                this.alertError = true
                this.alertErrorMsg = "Some fields have errors, please fill in the form correctly."
                return false
            }

            if (this.password.password.value !== this.password.conf_password.value) {
                this.password.conf_password.errors = ["New password did not match."]
                return false
            }

            data.password = this.password.password.value
            data.old_password = this.password.old_password.value

            this.modalPasswordLoading = true
            axios.post('/api/edit_account', data)
                .then((res) => {
                    this.modalPasswordLoading = false
                    this.modalPassword = false
                    this.alertSuccess = true
                    this.alertSuccessMsg = "Password updated successfully!"
                    this.adminUsername = this.username.value
                })
                .catch((err) => {
                    this.modalPasswordLoading = false
                    this.alertError = true
                    this.alertErrorMsg = this.errorResponseMsg(err)
                })
        }
    },
    watch: {
        modalUsername (val) {
            if (!val) {
                this.username.value = this.adminUsername
                this.username.errors = []
            }
        },
        modalPassword (val) {
            if (!val) {
                for (let k in this.password) {
                    this.password[k].errors = []
                    this.password[k].value = ''
                }
            }
        }
    }
}
</script>