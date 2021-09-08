<template>
    <transition name="bounce">
        <div v-if="modelValue" class="fixed z-50 inset-x-0 top-0 p-4 cursor-pointer" @click="closeAlert()">
            <div class="card w-1/3 mx-auto" :class="styleStat.color">
                <div class="flex items-center gap-4 font-medium">
                    <div class="flex-initial text-4xl"><i :class="styleStat.icon"></i></div>
                    <div class="flex-1 text-xl">{{ message }}</div>
                </div>
                <div class="italic text-center text-sm"> Click to close </div>
            </div>
        </div>
    </transition>
</template>
<script>
export default {
    props: {
        modelValue: {type:Boolean, default:false},
        status: {
            validator(v) {
                return ['info','success','warning','error'].includes(v)
            },
            default:'info'
        },
        message: String,
        duration: {type:Number, default:5000}
    },
    data () {
        return {
            timeout: Object
        }
    },
    methods: {
        closeAlert () {
            this.$emit('update:modelValue', false)
        }
    },
    computed: {
        styleStat () {
            let styles = {
                info: { color:'bg-blue-200 text-blue-600', icon:'fas fa-info-circle' },
                success: { color:'bg-green-200 text-green-600', icon:'fas fa-check-circle' },
                warning: { color:'bg-yellow-200 text-yellow-600', icon:'fas fa-exclamation-circle' },
                error: { color:'bg-yellow-200 text-yellow-600', icon:'fas fa-times' },
            }

            return styles[this.status]
        }
    },
    watch: {
        modelValue (val) {
            if (val) {
                this.timeout = setTimeout(this.closeAlert, this.duration)
            } else {
                clearTimeout(this.timeout)
            }
        }
    }

}
</script>
