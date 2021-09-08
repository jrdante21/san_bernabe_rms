<template>
    <div>
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
                        <div class="flex-1">
                            <div class="mb-2">
                                <input type="checkbox" id="chckOtherMaterials" v-model="otherMaterials"> <label for="chckOtherMaterials">Check if other materials</label>
                            </div>
                            <FieldInput v-if="otherMaterials" v-model="docs.material.value" :fErrors="docs.material.errors" :fLabel="docs.material.label" @onInput="validateData('material')"/>
                            <FieldInput v-else fType="select" :fOpts="materials" v-model="docs.material.value" :fErrors="docs.material.errors" :fLabel="docs.material.label" @onInput="validateData('material')"/>
                        </div>
                        <div class="flex-1">
                            <div class="flex gap-4 items-center">
                                <FieldInput fType="select" class="flex-1" :fOpts="statuses" v-model="docs.status.value" :fErrors="docs.status.errors" :fLabel="docs.status.label" @onInput="validateData('status')"/>
                                <FieldInput fType="number" class="flex-1" v-model="docs.amount.value" :fErrors="docs.amount.errors" :fLabel="docs.amount.label" @onInput="validateData('amount')"/>
                                <FieldInput fType="number" class="flex-1" v-model="docs.quantity.value" :fErrors="docs.quantity.errors" :fLabel="docs.quantity.label" @onInput="validateData('quantity')"/>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex gap-4 items-center">
                                <FieldInput fType="date" class="flex-1" v-model="docs.paid_at.value" :fErrors="docs.paid_at.errors" :fLabel="docs.paid_at.label" @onInput="validateData('paid_at')"/>
                                <FieldInput fType="date" class="flex-1" v-model="docs.returned_at.value" :fErrors="docs.returned_at.errors" :fLabel="docs.returned_at.label" @onInput="validateData('returned_at')"/>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex gap-4 items-center">
                                <FieldInput class="flex-1" v-model="docs.or_num.value" :fErrors="docs.or_num.errors" :fLabel="docs.or_num.label" @onInput="validateData('or_num')"/>
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
                            <th>OR No.</th>
                            <th>Materials</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Date Issued</th>
                            <th>Last Modified</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(d, i) in documents.data" :key="i">
                            <td>{{ d.resident.name }}</td>
                            <td>{{ d.or_num }}</td>
                            <td>({{ d.quantity }}) {{ d.material }}</td>
                            <td>{{ d.amount_format }}</td>
                            <td class="font-bold capitalize">
                                <span v-if="d.status == 'good'" class="text-green-600">{{ d.status }}</span>
                                <span v-else class="text-red-500">{{ d.status }}</span>
                            </td>
                            <td>
                                <span v-if="d.remarks.stat == 1" class="font-bold text-green-600">{{ d.remarks.text }}</span>    
                                <span v-else-if="d.remarks.stat == 2" class="font-bold text-yellow-600">{{ d.remarks.text }}</span>    
                                <span v-else class="font-bold text-red-500">{{ d.remarks.text }}</span>
                                <div class="text-sm italic">
                                    Date Paid: {{ (moment(d.paid_at).isValid()) ? moment(d.paid_at).format('ll') : 'N/A' }} <br>
                                    Date Returned: {{ (moment(d.returned_at).isValid()) ? moment(d.returned_at).format('ll') : 'N/A' }} <br>
                                </div> 
                            </td>
                            <td>{{ moment(d.issued_at).format('ll') }}</td>
                            <td>{{ moment(d.updated_at).fromNow() }}</td>
                            <td>
                                <div class="flex gap-1 justify-end text-sm">
                                    <button title="Edit" @click="editDocuments(d)" class="button bg-green-500 text-white"><i class="fas fa-edit"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="documents.data && documents.data.length <= 0">
                            <td colspan="9">No data found.</td>
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
            material: { value:'', rule:'alpha', opts:{hasNum:true}, label:'Material' },
            status: { value:'', rule:'alpha', label:'Status' },
            amount: { value:0, rule:'numeric', label:'Amount' },
            quantity: { value:0, rule:'numeric', label:'Quantity' },
            or_num: { value:'', rule:'alpha', opts:{hasNum:true}, label:'Receipt Number' },
            paid_at: { value: '', opts:{isReq:false}, rule:'date', label:'Date Paid' },
            returned_at: { value: '', opts:{isReq:false}, rule:'date', label:'Date Returned' },
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

            statuses: [
                {value:'good', text:'Good'},
                {value:'damaged', text:'Damaged'},
                {value:'lost', text:'Lost'},
            ],
            materials: [
                {value:'table wood', text:'table wood'}, 
                {value:'table plastic', text:'table plastic'}, 
                {value:'chairs (green)', text:'chairs (green)'}, 
                {value:'chairs (white)', text:'chairs (white)'}, 
                {value:'chairs (brown)', text:'chairs (brown)'}, 
                {value:'tent or module', text:'tent or module'},
                {value:'sound system', text:'sound system'}, 
                {value:'tela', text:'tela'}, 
                {value:'spoon', text:'spoon'}, 
                {value:'serving spoon big', text:'serving spoon big'}, 
                {value:'serving spoon small', text:'serving spoon small'}, 
                {value:'fork', text:'fork'}, 
                {value:'knife small', text:'knife small'},
                {value:'knife big', text:'knife big'}, 
                {value:'plates plastic small', text:'plates plastic small'}, 
                {value:'plates plastic big', text:'plates plastic big'}, 
                {value:'plates glass big', text:'plates glass big'}, 
                {value:'plated glass small', text:'plated glass small'},
                {value:'drinking glass plastic', text:'drinking glass plastic'}, 
                {value:'drinking glass glass', text:'drinking glass glass'}, 
                {value:'bowl plastic big', text:'bowl plastic big'}, 
                {value:'bowl plastic small', text:'bowl plastic small'}, 
                {value:'bowl glass big', text:'bowl glass big'},
                {value:'bowl plastic small', text:'bowl plastic small'}, 
                {value:'basketball', text:'basketball'}, 
                {value:'net volleyball', text:'net volleyball'}, 
                {value:'volleyball', text:'volleyball'}, 
                {value:'big kaserola', text:'big kaserola'}, 
                {value:'small kaserola', text:'small kaserola'}, 
                {value:'silyasi', text:'silyasi'}, 
                {value:'tabo', text:'tabo'}, 
                {value:'timba', text:'timba'}, 
                {value:'kitchen', text:'kitchen'}, 
                {value:'sound system', text:'sound system'}, 
                {value:'educational', text:'educational'}, 
                {value:'sports', text:'sports'}, 
                {value:'agriculture', text:'agriculture'}
            ],
            otherMaterials: false,

            alertError: false,
            alertErrorMsg: null,
            alertSuccess: false,

            modalDocs: false,
            modalLoading: false,
            modalResidentData: {},
            modalResidentError: '',

            documents: Object,
            documentsLoading: false,
            documentID: 0,
            
            searchResident: ''
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
            axios.get(`${this.apiServerName}borrowed?page=${page}&search=${search}`)
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
                this.docs[k].value = data[k]
            }

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

            if (error >= 1 || this.modalResidentData.id === undefined) {
                this.alertError = true
                this.alertErrorMsg = "Some fields have errors, please fill in the form correctly."
                if (this.modalResidentData.id === undefined) this.modalResidentError = "Resident is required."
                return false
            }

            data.resident_id = this.modalResidentData.id
            data.returned_at = moment(data.returned_at).format('YYYY-MM-DD')
            data.paid_at = moment(data.paid_at).format('YYYY-MM-DD')
            data.issued_at = moment(data.issued_at).format('YYYY-MM-DD')

            this.modalLoading = true
            axios.post(`${this.apiServerName}modify_borrowed`,{data:data, id:this.documentID})
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
        }
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
                for (let k in this.docs) {
                    this.docs[k].errors = []

                    switch (k) {
                        default:
                            this.docs[k].value = ''
                            break;
                        case 'amount':
                            this.docs[k].value = 0
                            break;
                        case 'issued_at':
                            this.docs[k].value = new Date()
                            break;
                    }
                }
            }
        }
    }
}
</script>