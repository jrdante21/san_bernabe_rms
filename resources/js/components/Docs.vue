<template>
    <div>
        <DocsBusiness :apiServerName="apiServerName" :trans="type" v-if="['buss_permit','buss_clearance'].includes(typeName)" />
        <DocsCertification :apiServerName="apiServerName" :trans="type" v-else-if="typeName == 'certification'" />
        <DocsSummon :apiServerName="apiServerName" :trans="type" v-else-if="typeName == 'summons'" />
        <DocsBorrowed :apiServerName="apiServerName" :trans="type" v-else-if="typeName == 'borrowed_materials'" />
        <DocsAll :apiServerName="apiServerName" :trans="type" v-else/>
    </div>
</template>
<script>
import DocsAll from './DocsAll.vue'
import DocsBusiness from './DocsBusiness.vue'
import DocsCertification from './DocsCertification.vue'
import DocsSummon from './DocsSummon.vue'
import DocsBorrowed from './DocsBorrowed.vue'
export default {
    props: ['transactionTypes', 'typeName', 'apiServerName'],
    components: {DocsAll, DocsBusiness, DocsCertification, DocsSummon, DocsBorrowed},
    data () {
        return {
            type:Object
        }
    },
    created () {
        this.checkTransactionTypes()
    },
    methods: {
        checkTransactionTypes () {
            let type = this.transactionTypes.find(t => t.name == this.typeName)
            if (type === undefined) {
                this.$router.push({name:'NotFound'})
            } else {
                this.type = type
            }
        },
    },
    watch: {
        typeName () {
            this.checkTransactionTypes()
        }
    }
}
</script>