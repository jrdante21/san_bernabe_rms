<template>
    <div :class="{'field-error':hasErrors, 'text-gray-500 cursor-not-allowed':fDisabled }">
        <div v-if="['text', 'number', 'password'].includes(fType)">
            <label>{{ fLabel }}</label>
            <input :disabled="fDisabled" :type="fType" :value="modelValue" class="block w-full input" @input="updateInput" @blur="$emit('onInput')" @keyup="$emit('onInput')"/>
        </div>

        <div v-else-if="['radio', 'checkbox'].includes(fType)">
            <!-- Boolean value -->
            <input v-if="typeof modelValue === 'boolean'" :disabled="fDisabled" :type="fType" v-model="modelValue" :id="fieldID" @click="updateCheckbox" class="input mr-2"/>
            
            <!-- String value -->
            <input v-else :disabled="fDisabled" :type="fType" :value="modelValue" :id="fieldID" @click="updateInput" class="input mr-2"/>

            <label :for="fieldID" class="cursor-pointer hover:underline">{{ fLabel }}</label>
        </div>

        <div v-else-if="fType == 'textarea'">
            <label>{{ fLabel }}</label>
            <textarea rows="3" :disabled="fDisabled" :value="modelValue" class="block w-full input" @input="updateInput" @blur="$emit('onInput')" @keyup="$emit('onInput')"></textarea>
        </div>

        <div v-else-if="fType == 'select'">
            <label>{{ fLabel }}</label>
            <select :disabled="fDisabled" :value="modelValue" @input="updateInput" @change="$emit('onInput')" class="block w-full input">
                <option v-for="(opt, i) in fOpts" :key="i" :value="opt.value">{{ opt.text }}</option>
            </select>
        </div>

        <div v-else-if="fType == 'date'">
            <label>{{ fLabel }}</label>

            <div class="flex gap-2">
                <div class="flex-1" v-if="['time','day','month'].includes(dateMinView)">
                    <select :disabled="fDisabled" @change="updateDate" class="block w-full input" v-model="setDate.month">
                        <option v-for="(month, i) in dateMonths" :key="i" :value="(i+1)<=9 ? '0'+(i+1): (i+1)">{{ month }}</option>
                    </select>
                </div>

                <div class="flex-shrink w-20" v-if="['time','day'].includes(dateMinView)">
                    <select :disabled="fDisabled" @change="updateDate" class="block w-full input" v-model="setDate.day">
                        <option v-for="(day, i) in dateDays" :key="i" :value="day<=9 ? '0'+day: day">{{ day }}</option>
                    </select>
                </div>

                <div class="flex-shrink w-32" v-if="['time','day','month','year'].includes(dateMinView)">
                    <select :disabled="fDisabled" @change="updateDate" class="block w-full input" v-model="setDate.year">
                        <option v-for="(year, i) in dateYears" :key="i">{{ year }}</option>
                    </select>
                </div>

                <div class="flex-shrink w-40" v-if="dateMinView == 'time'">
                    <input type="time" :disabled="fDisabled" @change="updateDate" class="block w-full input" v-model="setDate.time">
                </div>
            </div>

        </div>

        <div class="text-red-500" v-for="(err, i) in fErrors" :key="i">
            {{ err }}
        </div>
    </div>
</template>
<script>
import moment from 'moment'
export default {
    props: {
        modelValue: {type:[String, Date, Number, Boolean], default:''},
        fDisabled: {type:Boolean, default:false},
        fLabel: String,
        fType: {type:String, default:'text'},
        fOpts: {type:Array, default: []},
        fErrors: {type:Array, default: []},
        captalize: {type:Boolean, default:true},

        // For date type only...
        dateMinView: {
            validator(v) {
                return ['time', 'day', 'month', 'year'].includes(v)
            },
            default:'day'
        },
        dateMaxYear: {type:[String, Number], default:moment().format('YYYY')},
        dateMinYear: {type:[String, Number], default:1870}
    },
    data () {
        return {
            dateMonths: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            fieldID: 'field_'+Date.now(),
        }
    },
    methods: {
        updateInput: function (e) {
            let val = e.target.value
            val = (this.fType == 'text') ? this.capitalizeStr(val) : val
            this.$emit('update:modelValue', val)
        },
        updateDate: function () {
            let dt = this.setDate
            if (['month','year'].includes(this.dateMinView)) dt.day = '01'
            if (this.dateMinView == 'year') dt.month = '01'
            
            let format = moment(`${dt.year}-${dt.month}-${dt.day} ${dt.time}`, 'YYYY-MM-DD HH:mm')
            this.$emit('update:modelValue', format._d)
        },

        // For boolean checkbox or radio only...
        updateCheckbox: function (e) {
            this.$emit('update:modelValue', e.target.checked)
        },

        capitalizeStr: function (s) {
            if (!this.captalize) return s

            if (typeof s !== 'string') return ''
            return s.charAt(0).toUpperCase() + s.slice(1)
        }
    },
    computed: {
        hasErrors () {
            return (this.fErrors.length >= 1) ? true : false
        },

        // For date type only...
        setDate() {
            let date = {}
            date.day = moment(this.modelValue).format('DD')
            date.month = moment(this.modelValue).format('MM')
            date.year = moment(this.modelValue).format('YYYY')
            date.time = moment(this.modelValue).format('HH:mm')
            return date
        },
        dateDays () {
            if (!moment(this.modelValue).isValid()) return 0
            return moment(this.modelValue).daysInMonth()
        },
        dateYears () {
            let years = []
            for (let i = this.dateMinYear; i <= this.dateMaxYear; i++) { 
                years.push(i) 
            }
            years.reverse()
            return years
        }
    }
}
</script>
