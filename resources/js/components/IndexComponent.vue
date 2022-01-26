<template>
    <div class="container py-5">
        <h2 class="text-center">پروژه تست استخدام یونیتک (حسین سلیمانی رابری)</h2>

        <div class="py-4 text-center">
            <router-link class="btn btn-lg btn-primary px-4 font-weight-bold" :to="{name: 'main.products'}">
                <i class="fa ml-1 fa-list"></i> لیست محصولات
            </router-link>

            <router-link class="btn btn-lg mx-2 px-4 btn-primary font-weight-bold" :to="{name: 'main.import'}">
                <i class="fa ml-1 fa-plus"></i> ثبت محصول جدید
            </router-link>
        </div>

        <div class="text-center py-4">
            <a class="font-samim-bold" target="_blank" style="text-decoration:underline;" href="https://github.com/hossien-soli/uniqtechco">
                <i class="fa fa-code"></i> دسترسی به سورس پروژه در گیت هاب
            </a>
        </div>

        <hr>

        <div class="alert font-samim-medium alert-primary">
            <i class="fa fa-question"></i>
            باتشکر از شما، این پروژه قرار بود از زبان انگلیسی و فارسی به صورت هم زمان پشتیبانی کند اما به دلیل نبود زمان کافی این اتفاق نیوفتاد. و در صورت تمایل این امکان میتواند به پروژه باز برای تست اضافه شود!
            <br class="mb-1">
            <i class="fa fa-arrow-left"></i>
            <span class="font-samim-bold">همچنین به دلیل نبود وقت کافی از آنجایی که این سیستم با ویو جی اس طراحی شده است و کمی زمان بر است بخش نمایش محصولات نیز تکمیل نشده است اما بخش ثبت محصول و وارد کردن محصول از دیجی کالا کاملا تکمیل شده است! همچنین در صورت تمایل به تکمیل بخش نمایش محصولات لطفا اطلاع دهدید تا تکمیل شود!</span>
            <br class="mb-1">
            <i class="fa fa-arrow-left"></i>
            <span>در این سیستم تمامی تصاویر محصول از دیجی کالا دریافت شده و در سشن ذخیره میشوند و سپس بعد از پیش نمایش محصول و فیلتر تصاویر و تکمیل اطلاعات محصول تصاویر از دیجی کالا دانلود شده و در سیستم داخلی ذخیره میشوند و همچنین اطلاعات فیلم در دیتابیس مربوطه ثبت میشوند!</span>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
