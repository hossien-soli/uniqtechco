<template>
    <div class="container py-5">
        <h2 class="text-center">ثبت محصول جدید با وارد کردن از دیجی کالا</h2>
        
        <div class="row py-4 justify-content-center">
            <div class="col-lg-10 col-md-11">
                <form @submit.prevent="importProduct">
                    <div class="form-group">
                        <label for="digikalaLink">لینک محصول در دیجی کالا</label>
                        <input v-model="digikalaLink" :disabled="loading" type="text" class="form-control en-input" id="digikalaLink" placeholder="لینک محصول در دیجی کالا را در این باکس وارد کنید ...">
                    </div>

                    <div class="row">
                        <div class="col-7">
                            <button :disabled="loading" class="btn btn-block btn-primary" type="submit">
                                <i v-show="!importLoading" class="fa ml-1 fa-check"></i>
                                <i v-show="importLoading" class="fa ml-1 fa-spin fa-spinner"></i>
                                <span class="font-samim-bold" v-show="!importLoading">دریافت و نمایش اطلاعات فیلم</span>
                                <span class="font-samim-medium" v-show="importLoading">لطفا صبر کنید ...</span>
                            </button>
                        </div>

                        <div class="col-5 pr-0">
                            <router-link :class="['btn font-samim-bold btn-block btn-secondary',loading?'disabled':'']" :to="{name: 'main.index'}">
                                <i class="fa ml-1 fa-arrow-right"></i> انصراف و برگشت به عقب
                            </router-link>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <h3>
            <i class="fa fa-spin fa-spinner"></i> در حال بارگذاری ...
        </h3>
    </div>
</template>

<script>
    export default {
        mounted () {
            $('#digikalaLink').focus();
            enInput();
        },

        data () {
            return {
                digikalaLink: '',
                loading: false,
                importLoading: false,
                productLoaded: false,
                productInfo: {
                    name: '',
                    alternateName: '',
                    imageArr: '',
                    description: '',
                    price: '',
                },
            };
        },

        methods: {
            checkSession () {
                this.loading = true;
                ajaxGet('/ajax/import-product/check-session').done(function (resp) {
                    this.loading = false;
                    if (resp.ok) {
                        
                    }
                    else {
                        swalWarning(resp.msg);
                    }
                }).fail(function (err) {
                    this.loading = false;
                    swalConnectionError();
                });
            },

            importProduct () {
                if (!this.digikalaLink) {
                    swalWarning('لطفا لینک محصول را وارد کنید!',false);
                    return;
                }

                this.loading = true;
                this.importLoading = true;
                let data = {digikala_link: this.digikalaLink};
                ajaxPost('/ajax/import-product',data).done(function (resp) {
                    this.loading = false;
                    this.importLoading = false;
                    if (resp.ok) {
                        let data = resp.data;
                        this.productLoaded = true;
                        this.productInfo.name = data.name;
                        this.productInfo.alternateName = data.alternate_name;
                        this.productInfo.imageArr = data.image_arr;
                        this.productInfo.description = data.description;
                        this.productInfo.price = data.price;
                        swalSuccess('اطلاعات محصول با موفقیت از دیجی کالا دریافت شد. لطفا اطلاعات را تکمیل کرده و محصول را ثبت کنید!');
                    }
                    else {
                        swalWarning(resp.msg);
                    }
                }.bind(this)).fail(function (err) {
                    this.loading = false;
                    this.importLoading = false;
                    swalConnectionError();
                }.bind(this));
            },
        },
    }
</script>
