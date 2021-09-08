<template>
    <div>
        <div class="flex gap-4 items-center">
            <div class="flex-initial"><button title="Back" class="button bg-gray-300" @click="$router.go(-1)"><i class="fas fa-arrow-left"></i></button></div>
            <div class="flex-1"><div class="header mb-0">{{ info.fname }} {{ info.mname }} {{ info.lname }}</div></div>
            <div class="flex-initial">
                <div class="dropdown">
                    <button class="button bg-blue-500 text-white"><i class="fas fa-print"></i></button>
                    <div class="dropdown-content p-2 w-48 bg-white right-0">
                        <div class="menu-col">
                            <a @click="printResident()" class="item">Print Resident</a>
                            <a @click="printTransactions()" class="item">Print Transactions</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-initial">
                <router-link :to="`/admin/residents/${info.id}/edit`" class="button bg-green-500 text-white" >Edit</router-link>
            </div>
        </div>

        <div class="card mt-4">
            <div id="printable">
                <div class="flex gap-4 items-center">
                    <div class="flex-initial">
                        <img v-if="info.image" :src="`/images/profile_pic/${info.image}`" class="w-52">
                        <div v-else class="flex items-center w-52 h-52 bg-gray-300 text-center"><div class="flex-1">No Photo</div></div>
                    </div>
                    <div class="flex-1">
                        <div class="header">General Information</div>
                        <div class="flex flex-col gap-4">
                            <div class="flex gap-6">
                                <div class="flex-1">
                                    <div class="font-medium text-xl border-b border-gray-300">{{ info.name }}</div>
                                    <div class="text-sm text-gray-700">Name</div>
                                </div>
                            </div>
                            <div class="flex gap-6">
                                <div class="flex-1">
                                    <div class="font-medium text-xl capitalize border-b border-gray-300">{{ info.gender }}</div>
                                    <div class="text-sm text-gray-700">Gender</div>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-xl capitalize border-b border-gray-300">{{ info.civil }}</div>
                                    <div class="text-sm text-gray-700">Civil Status</div>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-xl border-b border-gray-300">{{ moment(info.bday).format('ll') }}</div>
                                    <div class="text-sm text-gray-700">Birthdate</div>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-xl border-b border-gray-300">{{ info.age }}</div>
                                    <div class="text-sm text-gray-700">Age</div>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-xl border-b border-gray-300">{{ info.years_res }}</div>
                                    <div class="text-sm text-gray-700">No. of years resided</div>
                                </div>
                            </div>
                            <div class="flex gap-6">
                                <div class="flex-1">
                                    <div class="font-medium text-xl border-b border-gray-300">{{ info.address }}</div>
                                    <div class="text-sm text-gray-700">Address</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="info.education && info.education.length >= 1">
                    <div class="header mt-8">Educational Background</div>
                    <div class="flex flex-col gap-4">
                        <div class="flex-1" v-for="(e, i) in info.education" :key="i">
                            <div class="flex gap-6 items-center">
                                <div class="flex-shrink w-32 text-xl">{{ educLevels[e.level] }}</div>
                                <div class="flex-1">
                                    <div class="font-medium text-lg border-b border-gray-300">{{ e.name }}</div>
                                    <div class="text-sm text-gray-700">School Name</div>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-lg border-b border-gray-300">{{ e.address }}</div>
                                    <div class="text-sm text-gray-700">School Address</div>
                                </div>
                                <div class="flex-initial">
                                    <div class="font-medium text-lg border-b border-gray-300">{{ moment(e.year).format('MMMM YYYY') }}</div>
                                    <div class="text-sm text-gray-700">Year Graduated</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="header mt-8">Family Background</div>
                <div class="flex flex-col gap-4">
                    <div class="flex gap-6 items-center">
                        <div class="flex-1">
                            <div class="font-medium text-lg border-b border-gray-300">{{ info.father || 'N/A' }}</div>
                            <div class="text-sm text-gray-700">Father's Name</div>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-lg border-b border-gray-300">{{ info.mother || 'N/A' }}</div>
                            <div class="text-sm text-gray-700">Mother's Name</div>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-lg border-b border-gray-300">{{ info.spouse || 'N/A' }}</div>
                            <div class="text-sm text-gray-700">Spouse's Name</div>
                        </div>
                    </div>
                    <div class="text-xl" v-if="info.children && info.children.length >= 1">Children</div>
                    <div class="flex gap-6 items-start" v-for="(c, i) in info.children" :key="i">
                        <div class="flex-shrink w-5">{{ i+1 }}.</div>
                        <div class="flex-1">
                            <div class="flex flex-col gap-2">
                                <div class="flex-1">
                                    <div class="flex items-start gap-6">
                                        <div class="flex-1">
                                            <div class="font-medium text-lg border-b border-gray-300">{{ c.name }}</div>
                                            <div class="text-sm text-gray-700">Child's Name</div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-medium text-lg capitalize border-b border-gray-300">{{ c.gender }}</div>
                                            <div class="text-sm text-gray-700">Gender</div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-medium text-lg capitalize border-b border-gray-300">{{ c.civil }}</div>
                                            <div class="text-sm text-gray-700">Civil Status</div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-medium text-lg border-b border-gray-300">{{ moment(c.bday).format('ll') }}</div>
                                            <div class="text-sm text-gray-700">Birthdate</div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="c.has_school" class="flex-1">
                                    <div class="flex gap-6 items-start">
                                        <div class="flex-initial">
                                            <div class="font-medium text-lg capitalize border-b border-gray-300">{{ c.school_level }}</div>
                                            <div class="text-sm text-gray-700">School Level</div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-medium text-lg border-b border-gray-300">{{ c.school_name }}</div>
                                            <div class="text-sm text-gray-700">School Name</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="info.business && info.business.length >= 1">
                    <div class="header mt-8">Business</div>
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-6" v-for="(b, i) in info.business" :key="i">
                            <div class="flex-1">
                                <div class="font-medium text-lg border-b border-gray-300">{{ b.name }}</div>
                                <div class="text-sm text-gray-700">Business Name</div>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-lg border-b border-gray-300">{{ b.address }}</div>
                                <div class="text-sm text-gray-700">Business Address</div>
                            </div>
                        </div>
                    </div>
                </div>


                <div v-if="info.work && info.work.length >= 1">
                    <div class="header mt-8">Job / Work History</div>
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-6" v-for="(w, i) in info.work" :key="i">
                            <div class="flex-1">
                                <div class="font-medium text-lg border-b border-gray-300">{{ w.title }}</div>
                                <div class="text-sm text-gray-700">Job Title / Position</div>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-lg border-b border-gray-300">{{ w.company }}</div>
                                <div class="text-sm text-gray-700">Company Name</div>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-lg border-b border-gray-300">{{ moment(w.start).format('MMMM YYYY') }}</div>
                                <div class="text-sm text-gray-700">Date Started</div>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-lg border-b border-gray-300">{{ (w.upto_present) ? 'Present' :moment(w.end).format('MMMM YYYY') }}</div>
                                <div class="text-sm text-gray-700">Date Ended</div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div v-if="info.assets && info.assets.length >= 1">
                    <div class="header mt-8">Assets</div>
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-6" v-for="(a, i) in info.assets" :key="i">
                            <div class="flex-shrink w-20 text-xl capitalize">{{ a.type }}</div>
                            
                            <div class="flex-1">
                                <div class="flex items-center gap-6">
                                    <div class="flex-1" v-for="(d, di) in a.description" :key="di">
                                        <div class="font-medium text-lg border-b border-gray-300 capitalize">{{ d.value }}</div>
                                        <div class="text-sm text-gray-700">{{ d.name }}</div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
<script>

import axios from 'axios'
import moment from 'moment'
export default {
    props: ['apiServerName'],
    data() {
        return {
            moment,
            info:Object,
            educLevels: {
                1:'Primary', 2:'Secondary', 3:'Tertiary', 4:'Vocational'
            }
        }
    },
    methods: {
        printResident () {
            let id = this.$route.params.id
            window.open('/print/resident?id='+id, '_blank')
        },
        printTransactions () {
            let id = this.$route.params.id
            window.open('/print/report?resident='+id, '_blank')
        }
    },
    created() {
        let id = this.$route.params.id
        axios.get(`${this.apiServerName}resident?id=${id}`)
            .then((res)=>{
                this.info = res.data
            })
            .catch((err)=>{
                console.log(err)
            })

    }
}
</script>