<template>
    <div>
        <div class="flex gap-4 items-center mb-4">
            <div class="flex-1"><div class="header mb-0">Residents</div></div>
            <div class="flex-initial">
                <div class="dropdown">
                    <button class="button bg-blue-500 text-white">Print Residents</button>
                    <div class="dropdown-content p-2 w-60 bg-white right-0">
                        <div class="flex flex-col gap-2">
                            <FieldInput class="flex-1" fType="checkbox" v-model="printPerPurok" fLabel="Print per purok"/>
                            <FieldInput class="flex-1" fType="checkbox" v-model="printWithAssets" fLabel="Print with assets"/>
                            <div class="flex-1">
                                <button @click="printResidents()" class="button w-full bg-blue-500 text-white">Go</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-initial">
                <router-link to="/admin/residents/add" class="button bg-green-500 text-white">Add Resident</router-link>
            </div>
        </div>
        <div class="card relative">
            <LoaderComponent v-if="residentsLoading"/>
            <FieldInput class="w-1/3 mb-4" v-model="searchResident" fLabel="Search Resident"/>
            <table class="w-full table-auto text-left border-collapse">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Date Created</th>
                        <th>Last Modified</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(r, i) in residents" :key="i">
                        <td>{{ r.name }}</td>
                        <td>{{ r.address }}</td>
                        <td>{{ moment(r.created_at).format('ll') }}</td>
                        <td>{{ moment(r.updated_at).fromNow() }}</td>
                        <td>
                            <div class="flex gap-1 justify-end text-sm">
                                <router-link :to="`/admin/residents/${r.id}`" class="button bg-gray-300">View</router-link>
                                <div class="flex-initial">
                                    <div class="dropdown">
                                        <button class="button bg-blue-500 text-white"><i class="fas fa-print"></i></button>
                                        <div class="dropdown-content p-2 w-48 bg-white right-0">
                                            <div class="menu-col">
                                                <a @click="printResident(r.id)" class="item">Print Resident</a>
                                                <a @click="printTransactions(r.id)" class="item">Print Transactions</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <router-link :to="`/admin/residents/${r.id}/edit`" class="button bg-green-500 text-white" title="Edit"><i class="fas fa-edit"></i></router-link>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <PaginationComponent class="inline-block mt-4" :links="pagination.links" :totalData="pagination.total" :perPage="pagination.per_page"/>
        </div>
        <AlertComponent v-model="alertError" :message="alertErrorMsg" status="warning" />
    </div>
</template>
<script>
import axios from 'axios'
import moment from 'moment'
import FieldInput from './Utils/FieldInput.vue'
import PaginationComponent from './Utils/PaginationComponent.vue'
import LoaderComponent from './Utils/LoaderComponent.vue'
import AlertComponent from './Utils/AlertComponent.vue'

export default {
    inject: ['errorResponseMsg'],
    props: ['apiServerName'],
    components : {FieldInput, PaginationComponent, LoaderComponent, AlertComponent},
    data () {
        return {
            moment,

            alertError: false,
            alertErrorMsg: null,

            residents:[],
            residentsLoading: false,

            pagination:[],
            searchResident: '',

            printPerPurok: false,
            printWithAssets: false,
        }
    },
    methods: {
        getResidents () {
            let page = this.$route.query.page || 1
            let search = this.$route.query.search || ''

            this.residentsLoading = true
            axios.get(`${this.apiServerName}residents?page=${page}&search=${search}`)
                .then((res)=>{
                    this.residentsLoading = false
                    this.residents = res.data.data
                    this.pagination = res.data
                })
                .catch((err)=>{
                    this.residentsLoading = false
                    this.alertError = true
                    this.alertErrorMsg = this.errorResponseMsg(err)
                })
        },
        printTransactions (id) { window.open('/print/report?resident='+id, '_blank') },
        printResident (id) { window.open('/print/resident?id='+id, '_blank') },
        printResidents () {
            let per_purok = (this.printPerPurok) ? 1 : 0
            let with_assets = (this.printWithAssets) ? 1 : 0
            window.open(`/print/residents?per_purok=${per_purok}&with_assets=${with_assets}`, '_blank')
        }
    },
    created () {
        this.getResidents()
        this.searchResident = this.$route.query.search
        this.$watch(() => this.$route.query, () => {
            this.getResidents()
        })
    },
    watch: {
        searchResident (val) {
            this.$router.push({ query:Object.assign({}, this.$route.query, { search: val }) })
        },
    }
}
</script>