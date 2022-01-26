<template>
    <div class="container text-center py-5">
        <h2>این بخش هنوز آماده نشده است لطفا به بخش ثبت محصول بروید!</h2>
        <router-link class="font-samim-bold" :to="{name: 'main.index'}">
            <i class="fa fa-arrow-left"></i> برگشت به غقب
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
