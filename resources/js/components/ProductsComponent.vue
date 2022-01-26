<template>
    <div class="container text-center py-5">
        <h2>این بخش هنوز آماده نشده است لطفا به بخش ثبت محصول بروید!</h2>
        <router-link class="btn btn-primary font-samim-bold px-5 mt-3" :to="{name: 'main.index'}">
            <i class="fa fa-arrow-right ml-1"></i> برگشت به عقب
        </router-link>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
