<template>
    <div>
        <header-layout></header-layout>
        <router-view></router-view>
    </div>
</template>

<script>
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
import HeaderLayout from './HeaderLayout'

export default {
    name: "defaultTemplate",
    components: {
        HeaderLayout: HeaderLayout
    }
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');
</style>