<template>
    <div>
        <!-- Assignatory -->
        <transition name="bounce">
            <div class="modal" v-if="modalAssignatory">
                <div class="content relative">
                    <LoaderComponent v-if="modalAssignatoryLoading"/>

                    <i class="fas fa-times close" @click="modalAssignatory = false"></i>
                    <div class="header">Set Assignatory</div>
                    <div class="flex  gap-4 flex-col">
                        <div v-for="(a, i) in assignatoryData" :key="i" class="flex-1">
                            <input class="input mr-4" type="checkbox" :id="`assignatory_chk_${i}`" :value="a.id" v-model="assignatoryCheckbox">
                            <label :for="`assignatory_chk_${i}`">{{ a.name }}</label>
                        </div>
                    </div>
                    
                    <div class="text-right mt-5">
                        <button class="button bg-black text-white mr-2" @click="modalAssignatory = false">Cancel</button>
                        <button class="button bg-green-500 text-white" @click="setAssignatory()">Submit</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Modal -->
        <transition name="bounce">
            <div class="modal" v-if="modalDocs">
                <div class="content relative">
                    <LoaderComponent v-if="modalLoading"/>

                    <i class="fas fa-times close" @click="modalDocs = false"></i>
                    <div class="header">{{ (documentID >= 1) ? 'Edit' : 'Add' }} {{ trans.title }}</div>
                    
                    <div class="flex flex-col gap-4">
                        <div class="flex-1" :class="{ 'field-error': modalResidentError }">
                            <label>Resident</label>
                            <SearchResident v-model="modalResidentData"/>
                            <div class="text-red-500" v-if="modalResidentError">{{ modalResidentError }}</div>
                        </div>
                        <div class="flex-1" :class="{ 'field-error': modalRespondentError }">
                            <label>Respondent</label>
                            <SearchResident :nameOnly="true" :showError="false" v-model="modalRespondent"/>
                            <div class="text-red-500" v-if="modalRespondentError">{{ modalRespondentError }}</div>
                        </div>
                        <div class="flex-1">
                            <FieldInput fType="textarea" v-model="docs.reason.value" :fErrors="docs.reason.errors" :fLabel="docs.reason.label" @onInput="validateData('reason')"/>
                        </div>
                        <div class="flex-1">
                            <FieldInput dateMinView="time" fType="date" v-model="docs.schedule.value" :fErrors="docs.schedule.errors" :fLabel="docs.schedule.label" />
                        </div>
                        <div class="flex-1">
                            <div class="flex gap-4 items-center">
                                <FieldInput class="flex-1" v-model="docs.doc_num.value" :fErrors="docs.doc_num.errors" :fLabel="docs.doc_num.label" @onInput="validateData('doc_num')"/>
                                <FieldInput class="flex-1" v-model="docs.or_num.value" :fErrors="docs.or_num.errors" :fLabel="docs.or_num.label" @onInput="validateData('or_num')"/>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex gap-4 items-center">
                                <FieldInput class="flex-1" v-model="docs.amount.value" :fErrors="docs.amount.errors" :fLabel="docs.amount.label" @onInput="validateData('amount')"/>
                                <FieldInput fType="date" class="flex-1" v-model="docs.issued_at.value" :fErrors="docs.issued_at.errors" :fLabel="docs.issued_at.label" @onInput="validateData('issued_at')"/>
                            </div>
                        </div>
                    </div>

                    <div class="text-right mt-5">
                        <button class="button bg-black text-white mr-2" @click="modalDocs = false">Cancel</button>
                        <button class="button bg-green-500 text-white" @click="submitDocs()">Submit</button>
                    </div>
                </div>
            </div>
        </transition>

        <div class="flex gap-4 items-center">
            <div class="flex-1"><div class="header mb-0">{{ trans.title }}</div></div>
            <div class="flex-initial">
                <button v-if="trans.id >= 2" class="button bg-blue-500 text-white" @click="modalAssignatory = true">Set Assignatory</button>
            </div>
            <div class="flex-initial">
                <button class="button bg-green-500 text-white" @click="modalDocs = true">Add {{ trans.title }}</button>
            </div>
        </div>
        <div class="card mt-4 relative">
            <LoaderComponent v-if="documentsLoading"/>
            <FieldInput class="w-1/3 mb-4" v-model="searchResident" fLabel="Search Resident"/>
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left border-collapse">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Case No.</th>
                            <th>OR No.</th>
                            <th>Amount</th>
                            <th>Date Issued</th>
                            <th>Last Modified</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(d, i) in documents.data" :key="i">
                            <td>{{ d.resident.name }}</td>
                            <td>{{ d.doc_num }}</td>
                            <td>{{ d.or_num }}</td>
                            <td>{{ d.amount_format }}</td>
                            <td>{{ moment(d.issued_at).format('ll') }}</td>
                            <td>{{ moment(d.updated_at).fromNow() }}</td>
                            <td>
                                <div class="flex gap-1 justify-end text-sm">
                                    <button class="button bg-blue-500 text-white" @click="printDocument(d.id)">Print</button>
                                    <button title="Edit" @click="editDocuments(d)" class="button bg-green-500 text-white"><i class="fas fa-edit"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="documents.data && documents.data.length <= 0">
                            <td colspan="6">No data found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationComponent class="inline-block mt-4" :links="documents.links" :totalData="documents.total" :perPage="documents.per_page"/>
        </div>

        <AlertComponent v-model="alertError" :message="alertErrorMsg" status="warning" />
        <AlertComponent v-model="alertSuccess" :message="`${trans.title} submitted successfully.`" status="success" />
    </div>
</template>
<script>
import { reactive } from 'vue'
import moment from 'moment'
import axios from 'axios'

import FormValidator from './Utils/FormValidator'

import LoaderComponent from './Utils/LoaderComponent.vue'
import PaginationComponent from './Utils/PaginationComponent.vue'
import SearchResident from './Utils/SearchResident.vue'
import FieldInput from './Utils/FieldInput.vue'
import AlertComponent from './Utils/AlertComponent.vue'

export default {
    inject: ['errorResponseMsg'],
    props: { trans: Object, apiServerName: String, },
    components: { LoaderComponent, PaginationComponent, SearchResident, FieldInput, AlertComponent },
    setup () {
        const fv = FormValidator()

        const docs = reactive({
            schedule: { value:new Date(), rule:'date', opts:{hasNum:true}, label:'Schedule' },
            reason: { value:'', rule:'alpha', opts:{hasNum:true}, label:'Reason' },

            doc_num: { value:'', rule:'alpha', opts:{hasNum:true}, label:'Barangay Case Number' },
            or_num: { value:'', rule:'alpha', opts:{hasNum:true}, label:'Receipt Number' },
            amount: { value:0, rule:'numeric', label:'Amount' },
            issued_at: { value: new Date(), rule:'date', label:'Date Issued' },
        })

        const validateData = (objKey='') => {
            if (docs[objKey]) {
                let d = docs[objKey]
                docs[objKey].errors = fv.validate(d.value, d.label)[d.rule](d.opts)

            } else {
                for (let k in docs) {
                    let d = docs[k]
                    docs[k].errors = fv.validate(d.value, d.label)[d.rule](d.opts)
                }
            }
        }

        return { docs, validateData }
    },
    data () {
        return {
            moment,

            alertError: false,
            alertErrorMsg: null,
            alertSuccess: false,

            modalDocs: false,
            modalLoading: false,
            modalResidentData: {},
            modalResidentError: '',
            modalRespondent: '',
            modalRespondentError: '',

            documents: Object,
            documentsLoading: false,
            documentID: 0,
            
            searchResident: '',

            modalAssignatory: false,
            modalAssignatoryLoading: false,
            assignatoryData: [],
            assignatoryCheckbox: [],
            assignatoryAlertMsg: false,
        }
    },
    created () {
        this.getDocuments()
        
        this.searchResident = this.$route.query.search
        this.$watch(() => this.$route.query, () => {
            this.getDocuments()
        })
    },
    methods: {
        getDocuments () {
            let page = this.$route.query.page || 1
            let search = this.$route.query.search || ''

            this.documentsLoading = true
            axios.get(`${this.apiServerName}documents?page=${page}&type=${this.trans.id}&search=${search}`)
                .then((res) => {
                    this.documentsLoading = false
                    this.documents = res.data
                })
                .catch((err) => {
                    this.documentsLoading = false
                    this.alertError = true
                    this.alertErrorMsg = this.errorResponseMsg(err)
                })
        },
        editDocuments (data) {
            this.modalResidentData = data.resident
            this.documentID = data.id
            for (let k in this.docs) {
                if (!['schedule', 'reason'].includes(k)) this.docs[k].value = data[k]
            }

            this.modalRespondent = data.summons.respondent
            this.docs.schedule.value = moment(data.summons.schedule)._d
            this.docs.reason.value = data.summons.reason

            this.modalDocs = true
        },
        submitDocs () {
            this.validateData()

            let data = {}
            let error = 0
            for (let k in this.docs) {
                data[k] = this.docs[k].value
                if (this.docs[k].errors && this.docs[k].errors.length >= 1) error += Number(1)
            }

            if (error >= 1 || this.modalResidentData.id === undefined || this.modalRespondent == '') {
                this.alertError = true
                this.alertErrorMsg = "Some fields have errors, please fill in the form correctly."
                if (this.modalResidentData.id === undefined) this.modalResidentError = "Resident is required."
                if (this.modalRespondent == '') this.modalRespondentError = "Respondent is required."
                return false
            }

            data.issued_at = moment(data.issued_at).format('YYYY-MM-DD')
            data.resident_id = this.modalResidentData.id
            data.type = this.trans.id
            if (data.type <= 0) {
                this.alertError = true
                this.alertErrorMsg = "Transaction type unknown."
                return false
            }

            let summons = {}
            summons.respondent = this.modalRespondent
            summons.schedule = moment(data.schedule).format('YYYY-MM-DD HH:mm:ss')
            summons.reason = data.reason

            delete data.schedule
            delete data.reason

            this.modalLoading = true
            axios.post(`${this.apiServerName}modify_docs`,{
                    id: this.documentID,
                    data: data,
                    summons: summons
                })
                .then((res) => {
                    this.modalLoading = false
                    this.modalDocs = false
                    this.alertSuccess = true
                    this.getDocuments()
                })
                .catch((err) => {
                    this.modalLoading = false
                    this.alertError = true
                    this.alertErrorMsg = this.errorResponseMsg(err)
                })
        },
        printDocument (id) {
            window.open('/print/document?id='+id, '_blank')
        },

        setAssignatory () {
            let data = {}
            data.ids = this.assignatoryCheckbox
            data.type = this.trans.id

            this.modalAssignatoryLoading = true
            axios.post(`${this.apiServerName}set_assignatory`, data)
                .then((res) => {
                    this.modalAssignatoryLoading = false
                    this.modalAssignatory = false
                    this.assignatoryAlertMsg = true
                })
                .catch((err) => {
                    this.modalAssignatoryLoading = false
                    this.alertError = true
                    this.alertErrorMsg = this.errorResponseMsg(err)
                })

        },
    },
    watch: {
        trans () {
            this.getDocuments()
        },
        searchResident (val) {
            this.$router.push({ query:Object.assign({}, this.$route.query, { search: val }) })
        },
        modalDocs (val) {
            if (!val) {
                this.documentID = 0
                this.modalResidentError = null
                this.modalResidentData = {}
                this.modalRespondentError = ''
                this.modalRespondent = ''
                for (let k in this.docs) {
                    this.docs[k].errors = []

                    switch (k) {
                        default:
                            this.docs[k].value = ''
                            break;
                        case 'amount':
                            this.docs[k].value = 0
                            break;

                        case 'schedule':
                        case 'issued_at':
                            this.docs[k].value = new Date()
                            break;
                    }
                }
            }
        },
        modalAssignatory (val) {
            if (val) {
                this.assignatoryCheckbox = []
                this.modalAssignatoryLoading = true
                axios.get(`${this.apiServerName}assignatory`)
                    .then((res) => {
                        this.modalAssignatoryLoading = false
                        this.assignatoryData = res.data

                        this.assignatoryData.forEach(el => {
                            if (el.assignatory.length >= 1) {
                                let type = el.assignatory.find(a => a.type == this.trans.id)
                                if (type !== undefined) this.assignatoryCheckbox.push(type.admin_officials_id)
                            }
                        })
                    })
                    .catch((err) => {
                        this.modalAssignatoryLoading = false
                        this.alertError = true
                        this.alertErrorMsg = this.errorResponseMsg(err)
                    })

            }
        }
    }
}
</script>