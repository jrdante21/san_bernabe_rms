<template>
    <div>
        <div class="relative">
            <input type="text" v-model="name" class="w-full block input" maxlength="20" @input="getResidents()" placeholder="Search Resident...">
            <div class="absolute right-px top-0 pt-1 pr-2 text-lg">
                <i class="fas fa-spinner animate-spin" v-if="loading"></i>
                <span v-else>
                    <a v-if="name" class="cursor-pointer" @click="removeData()"><i class="fas fa-times"></i></a>
                    <i v-else class="fas fa-search"></i>
                </span>
            </div>
        </div>
        <div class="relative" v-if="isOpen">
            <div class="menu-col absolute bg-white shadow-md w-full">
                <a v-for="(d, i) in data" :key="i" class="item" @click="updateData(d)">
                    {{ d.fname+' '+d.mname+' '+d.lname }}
                </a>
                <div class="p-2" v-if="data.length <= 0 && showError">No residents found.</div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios'
export default {
    props: {
        modelValue: {type:[Object, String], default:{}},
        showError: {type:Boolean, default:true},
        nameOnly: {type:Boolean, default:false},
    },
    data () {
        return {
            name:'',
            data:[],
            loading:false,
            isOpen:false
        }
    },
    created () {
        this.setModelValue()
    },
    methods: {
        updateData (data) {
            data = (this.nameOnly) ? data.name : data;
            this.$emit('update:modelValue', data)
            this.$emit('onSelect', data)
            this.isOpen = false
        },
        removeData () {
            this.$emit('update:modelValue', {})
            this.$emit('onClear')
            this.name = ''
            this.isOpen = false
        },

        getResidents () {
            if (this.name == '') {
                this.isOpen = false
                return false
            }

            this.loading = true
            this.isOpen = true
            axios.get(`/api/residents?search=${this.name}`)
                .then((res)=>{
                    this.loading = false
                    this.data = res.data.data

                    if (!this.showError && this.data.length <= 0) {
                        this.$emit('update:modelValue', this.name)
                    }
                })
                .catch((err)=>{
                    this.loading = false
                    console.log(err)
                })
        },

        setModelValue () {
            let val = this.modelValue
            if (typeof val === 'object' && val.name !== undefined) {
                this.name = val.name
            } else if (typeof val === 'string') {
                this.name = val
            }
        }
    },
    watch: {
        modelValue () {
            this.setModelValue()
        }
    }
}
</script>