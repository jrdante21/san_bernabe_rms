<template>
    <div>
        <router-view :apiServerName="apiServerName"></router-view>
    </div>
</template>
<script>
export default {
    props: ['apiServerName'],
}
</script>