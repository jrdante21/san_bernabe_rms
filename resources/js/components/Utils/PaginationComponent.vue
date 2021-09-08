<template>
    <div v-if="showPagination">
        <div class="menu menu-bordered">
            <a v-for="(l,i) in noNullLinks" 
                :key="i" class="item" 
                :class="{'bg-blue-500 text-white pointer-events-none':l.active}"
                @click="queryParamPage(l.url)">
                <span v-html="l.label"></span>
            </a>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        links: {type:Array, default:[]},
        totalData: {type:Number, default:10},
        perPage: {type:Number, default:10},
    },
    methods: {
        queryParamPage (url) {
            if (url === null) return false

            url = new URL(url)
            let page = new URLSearchParams(url.search)
            page = page.get('page')
            if (page === null) return false
            
            this.$router.push({ query:Object.assign({}, this.$route.query, { page: page }) })
        },
    },
    computed: {
        showPagination () {
            return (this.totalData <= this.perPage) ? false : true
        },
        noNullLinks () {
            return this.links.filter(l => l.url !== null)
        }
    }
}
</script>