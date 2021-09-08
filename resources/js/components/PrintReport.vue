<template>
    <div class="dropdown w-full">
        <button class="button w-full text-white bg-blue-500">Print Report</button>
        <div class="dropdown-content p-4 w-full bg-white">
            <div class="menu-col">
                <a class="item" @click="printReport(0)">All</a>
                <a class="item" v-for="(t, i) in transactionTypes" @click="printReport(t.id)" :key="i">{{ t.title }}</a>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['transactionTypes'],
    methods: {
        printReport (id) {
            let param = (id <= 0) ? '' : '?type='+id
            window.open('/print/report'+param, '_blank')
        }
    }
}
</script>