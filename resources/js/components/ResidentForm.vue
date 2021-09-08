<template>
<div>
    <transition name="bounce">
        <div class="modal" v-if="modalCropPic">
            <div class="content">
                <i class="fas fa-times close" @click="modalCropPic = false"></i>
                <div class="header">Crop Photo</div>
                <div>
                    <Cropper ref="imgCropper" :src="previewImage" class="h-80 bg-black" :stencil-props="{ aspectRatio: 10/10 }" />
                </div>
                <div class="text-center">
                    <button class="opacity-50 hover:opacity-100" @click="$refs.inputImg.click()">Change Photo</button>
                </div>

                <div class="text-right">
                    <button class="button bg-black text-white mr-2" @click="modalCropPic = false">Cancel</button>
                    <button class="button bg-green-500 text-white" @click="submitCroppedImage(); modalCropPic = false ">Submit</button>
                </div>
            </div>
        </div>
    </transition>

    <div class="flex gap-4 items-center">
        <div class="flex-initial"><button class="button bg-gray-300" @click="$router.go(-1)">Cancel</button></div>
        <div class="flex-1"><div class="header mb-0">{{ (infoID) ? 'Edit Resident' : 'Add Resident' }}</div></div>
        <div class="flex-initial">
            <button @click="submitForm()" class="button bg-green-500 text-white" :disabled="loaderShow">
                <i v-if="loaderShow" class="fas fa-circle-notch animate-spin"></i>
                {{ (infoID) ? 'Save Changes' : 'Submit' }}
            </button>
        </div>
    </div>

    <div class="card my-4">
        <div class="p-4 relative">
            <div class="absolute inset-0 bg-white bg-opacity-50" v-if="loaderShow"></div>

            <div class="flex items-center gap-4">
                <div class="flex-initial">
                    <input type="file" ref="inputImg" class="hidden" @change="uploadImage">

                    <div class="w-60 text-center relative" v-if="croppedImage != null">
                        <i class="fas fa-times-circle button-link absolute top right-1 text-2xl text-red-500" @click="croppedImage = null"></i>
                        <img :src="croppedImage" class="w-full border border-solid border-gray-300">
                        <a class="button-link" @click="showModalCropPic">Change Photo</a>
                    </div>
                    <div v-else>
                        <div class="flex items-center w-60 h-60 bg-gray-200" v-if="infoImage == '' || infoImage == null">
                            <div class="flex-1 text-center">
                                <a class="button-link" @click="showModalCropPic">Choose Photo</a>
                            </div>
                        </div>
                        <div class="w-60 text-center" v-else>
                            <img :src="'/images/profile_pic/'+infoImage" class="w-full">
                            <a class="button-link" @click="showModalCropPic">Change Photo</a>
                        </div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="header">General Information</div>
                    <div class="flex flex-col gap-4">
                        <div class="flex items-start gap-4">
                            <FieldInput class="flex-1" v-model="info.fname.value" :fErrors="info.fname.errors" :fLabel="info.fname.label+'*'" @onInput="validateInfo('fname')"/>
                            <FieldInput class="flex-1" v-model="info.mname.value" :fErrors="info.mname.errors" :fLabel="info.mname.label+'*'" @onInput="validateInfo('mname')"/>
                            <FieldInput class="flex-1" v-model="info.lname.value" :fErrors="info.lname.errors" :fLabel="info.lname.label+'*'" @onInput="validateInfo('lname')"/>
                        </div>
                        <div class="flex items-start gap-4">
                            <FieldInput class="flex-shrink w-40" fType="select" :fErrors="info.gender.errors" :fLabel="info.gender.label+'*'" :fOpts="genders" v-model="info.gender.value" @onInput="validateInfo('gender')"/>
                            <FieldInput class="flex-shrink w-40" fType="select" :fErrors="info.civil.errors" :fLabel="info.civil.label+'*'" :fOpts="civilStats" v-model="info.civil.value" @onInput="validateInfo('civil')"/>
                            <FieldInput class="flex-1" fType="date" :fErrors="info.bday.errors" :fLabel="info.bday.label+'*'" v-model="info.bday.value"/>
                            <FieldInput class="flex-shrink w-40" fType="number" :fErrors="info.years_res.errors" :fLabel="info.years_res.label+'*'" v-model="info.years_res.value" @onInput="validateInfo('years_res')"/>
                        </div>
                        <div class="flex items-start gap-4">
                            <FieldInput class="flex-1" v-model="info.home_num.value" :fErrors="info.home_num.errors" :fLabel="info.home_num.label" @onInput="validateInfo('home_num')"/>
                            <FieldInput class="flex-1" v-model="info.purok.value" :fErrors="info.purok.errors" :fLabel="info.purok.label+'*'" @onInput="validateInfo('purok')"/>
                            <FieldInput class="flex-1" v-model="info.brgy.value" :fErrors="info.brgy.errors" :fLabel="info.brgy.label+'*'" @onInput="validateInfo('brgy')"/>
                        </div>
                        <div class="flex items-start gap-4">
                            <FieldInput class="flex-1" v-model="info.muni.value" :fErrors="info.muni.errors" :fLabel="info.muni.label+'*'" @onInput="validateInfo('muni')"/>
                            <FieldInput class="flex-1" v-model="info.prov.value" :fErrors="info.prov.errors" :fLabel="info.prov.label+'*'" @onInput="validateInfo('prov')"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="header mt-8">Educational Background</div>
            <div class="flex flex-col gap-4">
                <div v-for="(educ, i) in otherInfo.education" :key="i">
                    <div class=" flex items-start gap-4">
                        <div class="flex-shrink w-32 font-medium">{{ educ.level.label }}</div>
                        <FieldInput class="flex-1" v-model="educ.name.value" :fErrors="educ.name.errors" :fLabel="educ.name.label" @onInput="validateOther('education').row(i)"/>
                        <FieldInput class="flex-1" v-model="educ.address.value" :fErrors="educ.address.errors" :fLabel="educ.address.label" @onInput="validateOther('education').item(i, 'address')"/>
                        <FieldInput class="flex-initial" fType="date" dateMinView="month" v-model="educ.year.value" :fErrors="educ.year.errors" :fLabel="educ.year.label"/>
                    </div>
                </div>
            </div>
                    
            <div class="header mt-8">Family Background</div>
            <div class="flex flex-col gap-4">
                <FieldInput v-model="info.father.value" :fErrors="info.father.errors" :fLabel="info.father.label" @onInput="validateInfo('father')"/>
                <FieldInput v-model="info.mother.value" :fErrors="info.mother.errors" :fLabel="info.mother.label" @onInput="validateInfo('mother')"/>
                <FieldInput v-model="info.spouse.value" :fErrors="info.spouse.errors" :fLabel="info.spouse.label" @onInput="validateInfo('spouse')"/>
                <div class="flex flex-col gap-2" v-for="(child, i) in otherInfo.children" :key="i">
                    <div class="flex items-end gap-4">
                        <FieldInput class="flex-1" v-model="child.name.value" :fErrors="child.name.errors" :fLabel="(i+1) +'. '+ child.name.label" @onInput="validateOther('children').row(i)"/>
                        <FieldInput class="flex-shrink w-40" fType="select" v-model="child.gender.value" :fErrors="child.gender.errors" :fLabel="child.gender.label" :fOpts="genders" @onInput="validateOther('children').item(i, 'gender')"/>
                        <FieldInput class="flex-shrink w-40" fType="select" v-model="child.civil.value" :fErrors="child.civil.errors" :fLabel="child.civil.label" :fOpts="civilStats" @onInput="validateOther('children').item(i, 'civil')"/>
                        <FieldInput class="flex-initial" fType="date" v-model="child.bday.value" :fErrors="child.bday.errors" :fLabel="child.bday.label"/>
                        <div class="flex-initial">
                            <button class="button bg-red-500 text-white text-sm" @click="otherInfo.children.splice(i, 1)"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="flex items-end gap-4">
                        <FieldInput class="flex-initial" fType="checkbox" v-model="child.has_school.value" fLabel="Currently studying?" />
                        <FieldInput class="flex-shrink w-60" fType="select" :fOpts="school_levels" :fDisabled="child.has_school.value ? false : true" v-model="child.school_level.value" :fErrors="child.school_level.errors" :fLabel="child.school_level.label" @onInput="validateOther('children').item(i, 'school_level')"/>
                        <FieldInput class="flex-1" :fDisabled="child.has_school.value ? false : true" v-model="child.school_name.value" :fErrors="child.school_name.errors" :fLabel="child.school_name.label" @onInput="validateOther('children').item(i, 'school_name')"/>
                    </div>
                </div>
                <div class="text-right">
                    <button class="button bg-blue-500 text-white" @click="addChildren()">Add Children</button>
                </div>
            </div>

            <div class="header mt-8">Business</div>
            <div class="flex flex-col gap-4">
                <div class="flex items-end gap-4" v-for="(buss, i) in otherInfo.business" :key="i">
                    <FieldInput class="flex-1" v-model="buss.name.value" :fErrors="buss.name.errors" :fLabel="buss.name.label" @onInput="validateOther('business').row(i)"/>
                    <FieldInput class="flex-1" v-model="buss.address.value" :fErrors="buss.address.errors" :fLabel="buss.address.label" @onInput="validateOther('business').item(i, 'address')"/>
                    <div class="flex-initial">
                        <button class="button bg-red-500 text-white text-sm" @click="otherInfo.business.splice(i, 1)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="text-right">
                    <button class="button bg-blue-500 text-white" @click="addBusiness()">Add Business</button>
                </div>
            </div>

            <div class="header mt-8">Work / Job History</div>
            <div class="flex flex-col gap-4">
                <div class="flex items-end gap-4" v-for="(job, i) in otherInfo.work" :key="i">
                    <FieldInput class="flex-1" v-model="job.title.value" :fErrors="job.title.errors" :fLabel="job.title.label" @onInput="validateOther('work').row(i)"/>
                    <FieldInput class="flex-1" v-model="job.company.value" :fErrors="job.company.errors" :fLabel="job.company.label" @onInput="validateOther('work').item(i, 'company')"/>
                    <FieldInput class="flex-initial" fType="date" dateMinView="month" v-model="job.start.value" :fErrors="job.start.errors" :fLabel="job.start.label"/>
                    <FieldInput class="flex-initial" fType="checkbox" v-model="job.upto_present.value" fLabel="Up to present" />
                    <FieldInput class="flex-initial" :fDisabled="job.upto_present.value" fType="date" dateMinView="month" v-model="job.end.value" :fErrors="job.end.errors" :fLabel="job.end.label"/>
                    <div class="flex-initial">
                        <button class="button bg-red-500 text-white text-sm" @click="otherInfo.work.splice(i, 1)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="text-right">
                    <button class="button bg-blue-500 text-white" @click="addWork()">Add Work</button>
                </div>
            </div>

            <div class="header mt-8">Assets</div>
            <div class="flex flex-col gap-4">
                <div class="flex items-end gap-4" v-for="(ass, i) in otherInfo.assets" :key="i">
                    <FieldInput class="flex-shrink w-40" fType="select" v-model="ass.type.value" :fErrors="ass.type.errors" :fLabel="ass.type.label" :fOpts="assetsType"/>
                    <div class="flex-1" v-if="ass.type.value == 'vehicle'">
                        <div class="flex items-end gap-4">
                            <FieldInput class="flex-1" v-model="ass.v_brand.value" :fErrors="ass.v_brand.errors" :fLabel="ass.v_brand.label" @onInput="validateOther('assets').item(i, 'v_brand')"/>
                            <FieldInput class="flex-1" v-model="ass.v_model.value" :fErrors="ass.v_model.errors" :fLabel="ass.v_model.label" @onInput="validateOther('assets').item(i, 'v_model')"/>
                            <FieldInput class="flex-1" v-model="ass.v_plate.value" :fErrors="ass.v_plate.errors" :fLabel="ass.v_plate.label" @onInput="validateOther('assets').item(i, 'v_plate')"/>
                            <FieldInput class="flex-1" v-model="ass.v_color.value" :fErrors="ass.v_color.errors" :fLabel="ass.v_color.label" @onInput="validateOther('assets').item(i, 'v_color')"/>
                        </div>
                    </div>
                    <div class="flex-1" v-if="ass.type.value == 'animal'">
                        <div class="flex items-end gap-4">
                            <FieldInput class="flex-1" v-model="ass.a_type.value" :fErrors="ass.a_type.errors" :fLabel="ass.a_type.label" @onInput="validateOther('assets').item(i, 'a_type')"/>
                            <FieldInput class="flex-1" fType="select" :fOpts="genders" v-model="ass.a_sex.value" :fErrors="ass.a_sex.errors" :fLabel="ass.a_sex.label" @onInput="validateOther('assets').item(i, 'a_sex')"/>
                            <FieldInput class="flex-1" v-model="ass.a_age.value" :fErrors="ass.a_age.errors" :fLabel="ass.a_age.label" @onInput="validateOther('assets').item(i, 'a_age')"/>
                            <FieldInput class="flex-1" v-model="ass.a_color.value" :fErrors="ass.a_color.errors" :fLabel="ass.a_color.label" @onInput="validateOther('assets').item(i, 'a_color')"/>
                            <FieldInput class="flex-1" v-model="ass.a_cert.value" :fErrors="ass.a_cert.errors" :fLabel="ass.a_cert.label" @onInput="validateOther('assets').item(i, 'a_cert')"/>
                        </div>
                    </div>
                    <div class="flex-1" v-if="ass.type.value == 'land'">
                        <div class="flex items-end gap-4">
                            <FieldInput class="flex-1" v-model="ass.l_type.value" :fErrors="ass.l_type.errors" :fLabel="ass.l_type.label" @onInput="validateOther('assets').item(i, 'l_type')"/>
                            <FieldInput class="flex-1" v-model="ass.l_hectar.value" :fErrors="ass.l_hectar.errors" :fLabel="ass.l_hectar.label" @onInput="validateOther('assets').item(i, 'l_hectar')"/>
                            <FieldInput class="flex-1" v-model="ass.l_loc.value" :fErrors="ass.l_loc.errors" :fLabel="ass.l_loc.label" @onInput="validateOther('assets').item(i, 'l_loc')"/>
                        </div>
                    </div>
                    <div class="flex-initial">
                        <button class="button bg-red-500 text-white text-sm" @click="otherInfo.assets.splice(i, 1)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="text-right">
                    <button class="button bg-blue-500 text-white" @click="addAssets()">Add Assets</button>
                </div>
            </div>
        </div>
        <AlertComponent v-model="errMsg" :message="errMessage" status="warning" />
        <AlertComponent v-model="succMsg" 
            :message="(infoID) ? 'Resident information updated successfully.' : 'Resident information added successfully.'" 
            status="success" />
    </div>

    <div class="flex gap-4 items-center">
        <div class="flex-initial"><button class="button bg-gray-300" @click="$router.go(-1)">Cancel</button></div>
        <div class="flex-1"><div class="header mb-0">{{ (infoID) ? 'Edit Resident' : 'Add Resident' }}</div></div>
        <div class="flex-initial">
            <button @click="submitForm()" class="button bg-green-500 text-white" :disabled="loaderShow">
                <i v-if="loaderShow" class="fas fa-circle-notch animate-spin"></i>
                {{ (infoID) ? 'Save Changes' : 'Submit' }}
            </button>
        </div>
    </div>

</div>
</template>

<script>
import { reactive } from 'vue'
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css'
import validator from 'validator'
import axios from 'axios'

import FormValidator from './Utils/FormValidator'
import AlertComponent from './Utils/AlertComponent.vue'
import FieldInput from './Utils/FieldInput.vue'

export default {
    props: ['apiServerName'],
    components: { FieldInput, AlertComponent, Cropper },
    data () {
        return {
            loaderShow: false,

            modalCropPic: false,
            previewImage: null,
            croppedImage: null,
            infoImage: '',
            infoID: null,

            genders: [
                {value:'male', text:'Male'},
                {value:'female', text:'Female'},
            ],
            civilStats: [
                {value:'single', text:'Single'},
                {value:'married', text:'Married'},
                {value:'lived in', text:'Lived In'},
                {value:'widowed', text:'Widowed'},
            ],
            school_levels: [
                {value:1, text:'Primary'},
                {value:2, text:'Secondary'},
                {value:3, text:'Tertiary'},
                {value:4, text:'Vocational'},
            ],
            assetsType: [
                {value:'vehicle', text:'Vehicle'},
                {value:'animal', text:'Animal'},
                {value:'land', text:'Land'},
            ],
            
            errMsg: false,
            errMessage: '',
            succMsg: false,
        }
    },
    setup () {
        const fv = FormValidator()
        const formData = reactive({
            hasError: false,
            data: {
                info: {},
                children: [],
                education: [],
                business: [],
                work: [],
                assets: [],
            }
        })

        // general info...
        const info = reactive({
            fname: { value:'', rule:'alpha', label:'First Name' },
            mname: { value:'', rule:'alpha', label:'Middle Name' },
            lname: { value:'', rule:'alpha', label:'Last Name' },
            gender: { value:'', rule:'alpha', label:'Gender' },
            civil: { value:'', rule:'alpha', label:'Civil Status' },
            bday: { value: new Date(), rule:'date', label:'Birthdate' },
            years_res: { value:'', rule:'numeric', label:'Years Resided' },
            home_num: { value:'', rule:'alpha', opts:{isReq:false, hasNum:true}, label:'Home Number' },
            purok: { value:'', rule:'alpha', opts:{hasNum:true, min:1}, label:'Purok' },
            brgy: { value:'San Bernabe', rule:'alpha', opts:{hasNum:true, min:3}, label:'Barangay' },
            muni: { value:'Diffun', rule:'alpha', opts:{hasNum:true, min:3}, label:'Municipality' },
            prov: { value:'Quirino', rule:'alpha', opts:{hasNum:true, min:3}, label:'Province' },

            father: { value:'', rule:'alpha', opts:{isReq:false, min:5}, label:"Father's Name" },
            mother: { value:'', rule:'alpha', opts:{isReq:false, min:5}, label:"Mother's Name" },
            spouse: { value:'', rule:'alpha', opts:{isReq:false, min:5}, label:"Spouse's Name" },
        })
        const validateInfo = (objKey='') => {
            if (info[objKey]) {
                let d = info[objKey]
                info[objKey].errors = fv.validate(d.value, d.label)[d.rule](d.opts)

            } else {
                for (let k in info) {
                    let d = info[k]
                    info[k].errors = fv.validate(d.value, d.label)[d.rule](d.opts)
                    formData.data.info[k] = info[k].value
                    if (info[k].errors.length >= 1) formData.hasError = true
                }
            }
        }

        // Other information in array...
        const otherInfo = reactive({})

        // Education...
        otherInfo.education = []
        const educ_levels = [
            {value:1, text:'Primary'},
            {value:2, text:'Secondary'},
            {value:3, text:'Tertiary'},
            {value:4, text:'Vocational'},
        ]
        educ_levels.forEach(function(el){
            otherInfo.education.push({
                level: { value:el.value, label:el.text },
                name: { value:'', rule:'alpha', opts:{isReq:false, min:2}, label:'School Name', main:true },
                address: { value:'', rule:'alpha', opts:{hasNum:true, min:5}, label:'School Address' },
                year: { value:new Date(), rule:'date', label:'Year Graduated' },
            })
        })

        // children...
        otherInfo.children = []
        const addChildren = (data={}) => {
            let d = {
                name: data.name || '',
                bday: data.bday || new Date(),
                gender: data.gender || '',
                civil: data.civil || '',
                has_school: data.has_school || false,
                school_level: data.school_level || '',
                school_name: data.school_name || ''
            }

            otherInfo.children.push({
                name: { value:d.name, rule:'alpha', opts:{isReq:false, min:5}, label:"Child's Name", main:true },
                bday: { value:d.bday, rule:'date', label:"Birthdate" },
                gender: { value:d.gender, rule:'alpha', label:"Gender" },
                civil: { value:d.civil, rule:'alpha', label:"Civil Status" },
                has_school: { value:Boolean(d.has_school) },
                school_level: { value:d.school_level, rule:'alpha', opts:{hasNum:true, min:1}, label:"School Level", cond:function(el){
                    return el.has_school.value
                }} ,
                school_name: { value:d.school_name, rule:'alpha', opts:{hasNum:true, min:3}, label:"School Name", cond:function(el){
                    return el.has_school.value
                }} ,
            })
        }

        // Business...
        otherInfo.business = []
        const addBusiness = (data={}) => {
            let d = {
                name: data.name || '',
                address: data.address || '',
            }
            otherInfo.business.push({
                name: { value:d.name, rule:'alpha', opts:{isReq:false, min:2}, label:'Business Name', main:true },
                address: { value:d.address, rule:'alpha', opts:{min:5, hasNum:true}, label:'Business Address' },
            })
        }

        // Work history...
        otherInfo.work = []
        const addWork = (data={}) => {
            let d = {
                title: data.title || '',
                company: data.company || '',
                start: data.start || new Date(),
                upto_present: data.upto_present || true,
                end: data.end || new Date(),
            }

            otherInfo.work.push({
                title: { value:d.title, rule:'alpha', opts:{isReq:false, hasNum:true}, label:'Job Title / Position', main:true },
                company: { value:d.company, rule:'alpha', opts:{hasNum:true}, label:'Company Name' },
                start: { value:d.start, rule:'date', label:'Date Started' },
                upto_present: { value:Boolean(d.upto_present) },
                end: { value:d.end, rule:'date', label:'Date Ended', cond:function(el){
                    return el.upto_present.value
                }},
            })
        }

        // Assets...
        otherInfo.assets = []
        const addAssets = (data={}) => {
            const vcond = (el) => { return (el.type.value == 'vehicle') ? true : false }
            const acond = (el) => { return (el.type.value == 'animal') ? true : false }
            const lcond = (el) => { return (el.type.value == 'land') ? true : false }

            let d = {
                type: data.type || '',
                
                v_brand: data.v_brand || '',
                v_model: data.v_model || '',
                v_plate: data.v_plate || '',
                v_color: data.v_color || '',

                a_type: data.a_type || '',
                a_sex: data.a_sex || '',
                a_color: data.a_color || '',
                a_age: data.a_age || '',
                a_cert: data.a_cert || '',

                l_type: data.l_type || '',
                l_hectar: data.l_hectar || '',
                l_loc: data.l_loc || '',
            }   

            otherInfo.assets.push({
                type: { value:d.type, rule:'alpha', opts:{isReq:false}, label:"Asset's Type", main:true },

                v_brand: { value:d.v_brand, rule:'alpha', opts:{hasNum:true}, label:'Brand', cond:vcond }, 
                v_model: { value:d.v_model, rule:'alpha', opts:{hasNum:true}, label:'Model', cond:vcond },
                v_plate: { value:d.v_plate, rule:'alpha', opts:{hasNum:true}, label:'Plate No.', cond:vcond },
                v_color: { value:d.v_color, rule:'alpha', label:'Color', cond:vcond },

                a_type: { value:d.a_type, rule:'alpha', label:'Animal Type', cond:acond },
                a_sex: { value:d.a_sex, rule:'alpha', label:'Sex', cond:acond },
                a_color: { value:d.a_color, rule:'alpha', label:'Color', cond:acond },
                a_age: { value:d.a_age, rule:'alpha', opts:{hasNum:true}, label:'Age', cond:acond },
                a_cert: { value:d.a_cert, rule:'alpha', opts:{hasNum:true}, label:'Certificate of Ownership', cond:acond },

                l_type: { value:d.l_type, rule:'alpha', label:'Land Type', cond:lcond },
                l_hectar: { value:d.l_hectar, rule:'alpha', opts:{hasNum:true}, label:'Hectars', cond:lcond },
                l_loc: { value:d.l_loc, rule:'alpha', opts:{hasNum:true}, label:'Location', cond:lcond } 
            })
        }

        // Validate arrays...        
        const validateOther = (infoArray) => {
            let array = otherInfo[infoArray]
            const item = (i, el) => {
                let data = array[i]
                let main = Object.keys(data).find(key => data[key].main === true)
                if (data[el].rule === undefined) return false

                if (!validator.isEmpty(data[main].value)) {
                    if (data[el].cond === undefined) {
                        data[el].errors = fv.validate(data[el].value, data[el].label)[data[el].rule](data[el].opts)
                    } else {
                        if (data[el].cond(data)) {
                            data[el].errors = fv.validate(data[el].value, data[el].label)[data[el].rule](data[el].opts)
                        } else {
                            data[el].errors = []
                        }
                    }
                } else {
                    data[el].errors = []
                }
                
                if (data[el].errors.length >= 1) formData.hasError = true
            }

            const row = (i) => { for(let k in array[i]) { item(i, k) } }
            const all = () => {
                formData.data[infoArray] = []
                array.forEach(function(el, i){ 
                    row(i)

                    let d = {}
                    for (let k in el) { d[k] = el[k].value }
                    formData.data[infoArray].push(d)
                }) 
            }

            return {item, row, all}
        }

        return { info, validateInfo, otherInfo, 
        addChildren, addBusiness, addWork, addAssets, validateOther, 
        formData }
    },
    created () {
        if (this.$route.params.id) {
            axios.get(`${this.apiServerName}resident?id=${this.$route.params.id}`)
                .then((res) => { this.setResidentInfo(res.data) })
                .catch((err) => { console.log(err) })

        } else {
            this.addEmptyArrayFields()
        }

    },
    methods: {
        setResidentInfo: function (data) {
            this.infoID = data.id
            this.infoImage = data.image
            for (let k in this.info) {
                if (data[k] !== null) this.info[k].value = data[k] 
            }
            
            let education = this.otherInfo.education
            data.education.forEach(function(dEl){
                education.forEach(function(tEl){
                    if (dEl.level == tEl.level.value) {
                        tEl.name.value = dEl.name
                        tEl.address.value = dEl.address
                        tEl.year.value = dEl.year
                    }
                })
            })

            let otherData = ['Children', 'Business', 'Work', 'Assets']
            otherData.forEach((el) => {
                let addFunc = this['add'+el]
                let infoData = data[el.toLowerCase()]

                if (infoData.length <= 0) addFunc()
                infoData.forEach(function(e){
                    addFunc(e)
                })
            })
        },
        resetResidentInfo () {
            for (let k in this.info) {
                if (!['bday','brgy','muni','prov'].includes(k)) {
                    this.info[k].value = ''
                }
            }

            this.infoImage = ''
            this.otherInfo.education.forEach(el => {
                el.name.value = ''
                el.address.value = ''
            })

            this.otherInfo.children = []
            this.otherInfo.business = []
            this.otherInfo.work = []
            this.otherInfo.assets = []
            this.addEmptyArrayFields()
        },

        showModalCropPic: function () {
            this.modalCropPic = true
            this.$refs.inputImg.click()
        },
        uploadImage(e){
            const image = e.target.files[0];
            const reader = new FileReader();
            reader.readAsDataURL(image);
            reader.onload = e =>{
                this.previewImage = e.target.result;
            };
        },
        submitCroppedImage() {
            const cropper = this.$refs.imgCropper.getResult()
            this.croppedImage = cropper.canvas.toDataURL()
        },

        addEmptyArrayFields () {
            this.addChildren()
            this.addBusiness()
            this.addWork()
            this.addAssets()
        },

        submitForm: function () {
            this.formData.hasError = false

            let others = ['education', 'children', 'business', 'work', 'assets']
            others.forEach(el => { this.validateOther(el).all() });
            this.validateInfo()

            if (this.formData.hasError) {
                this.errMsg = true
                this.errMessage = "Some fields have errors, please fill in the form correctly."
                return false
            }
            this.loaderShow = true

            const data = this.formData.data
            data.image = this.croppedImage
            if (this.infoID) data.id = this.infoID

            axios.post(`${this.apiServerName}modify_resident`, data)
                .then((res) => {
                    this.loaderShow = false
                    this.succMsg = true
                    if (this.infoID == null || this.infoID <=0 || this.infoID == '') {
                        this.resetResidentInfo()
                    }

                })
                .catch((err) => {
                    this.loaderShow = false
                    this.errMsg = true
                    this.errMessage = this.errorResponseMsg(err)
                })
        }
    }
}
</script>
\
