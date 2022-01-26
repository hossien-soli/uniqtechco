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

        <h3 v-show="loading" class="py-5 text-center">
            <i class="fa ml-1 fa-spin fa-spinner"></i> در حال بارگذاری ...
        </h3>

        <div v-if="productLoaded">
            <div>
                <div v-for="(imgLink,index) in productInfo.imageArr" :class="['d-inline-block p-2 m-1 border',imageFilter[index]?'border-success':'border-danger']">
                    <img width="140" :src="imgLink" />

                    <div class="text-center py-2">
                        <button :disabled="loading" @click="imageFilterDo(index,1)" class="btn w-50 btn-sm btn-success">
                            <i class="fa fa-check"></i>
                        </button>

                        <button :disabled="loading" @click="imageFilterDo(index,0)" class="btn w-25 btn-sm btn-danger">
                            <i class="fa fa-times"></i>
                        </button>

                        <div class="mt-2">
                            <span v-if="!imageFilter[index]" class="badge badge-danger">
                                <i class="fa fa-trash-alt"></i> حذف شد
                            </span>
                            <span v-if="imageFilter[index]" class="badge badge-success">
                                <i class="fa fa-reply"></i> ثبت خواهد شد
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 row">
                <div class="form-group col-md-6">
                    <label for="productName">نام محصول</label>
                    <input v-model="productInfo.name" type="text" :disabled="loading" id="productName" class="form-control" maxlength="255" placeholder="نام محصول را وارد کنید ...">
                </div>

                <div class="form-group pr-md-1 col-md-6">
                    <label for="productAlternateName">نام جایگزین محصول</label>
                    <input v-model="productInfo.alternateName" :disabled="loading" type="text" id="productAlternateName" maxlength="255" class="form-control" placeholder="نام جایگزین محصول را وارد کنید ...">
                </div>
            </div>

            <div class="form-group">
                <label for="productDescription">توضیحات محصول</label>
                <textarea v-model="productInfo.description" :disabled="loading" rows="7" class="form-control" placeholder="توضیحات محصول را وارد کنید ..." id="productDescription"></textarea>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="productPrice">قیمت محصول</label>
                    <input v-model="productInfo.price" :disabled="loading" type="number" id="productPrice" class="form-control" placeholder="قیمت محصول را وارد کنید ...">
                </div>
            </div>

            <button @click="finalizeImport" :disabled="loading" class="btn px-lg-5 btn-primary" type="button">
                <i v-show="!finalizeLoading" class="fa ml-1 fa-check"></i>
                <i v-show="finalizeLoading" class="fa ml-1 fa-spin fa-spinner"></i>
                <span v-show="!finalizeLoading" class="font-samim-bold">تایید و ثبت محصول</span>
                <span class="font-samim-medium" v-show="finalizeLoading">لطفا صبر کنید ...</span>
            </button>

            <button @click="finalizeImportAndPublish" :disabled="loading" class="btn mx-2 px-lg-5 btn-success" type="button">
                <i v-show="!finalizeLoading" class="fa ml-1 fa-check-double"></i>
                <i v-show="finalizeLoading" class="fa ml-1 fa-spin fa-spinner"></i>
                <span v-show="!finalizeLoading" class="font-samim-bold">ثبت و انتشار محصول</span>
                <span class="font-samim-medium" v-show="finalizeLoading">لطفا صبر کنید ...</span>
            </button>

            <button @click="cancelImport" :disabled="loading" class="btn px-lg-5 btn-secondary" type="button">
                <i class="fa ml-1 fa-times"></i>
                <span class="font-samim-bold">انصراف و نادیده گرفتن محصول</span>
            </button>
        </div>

        <br><br><br><br>
    </div>
</template>

<script>
    export default {
        mounted () {
            $('#digikalaLink').focus();
            enInput();
            this.checkSession();
        },

        data () {
            return {
                digikalaLink: '',
                loading: false,
                importLoading: false,
                finalizeLoading: false,
                productLoaded: false,
                productInfo: {
                    name: '',
                    alternateName: '',
                    imageArr: [],
                    description: '',
                    price: '',
                },
                imageFilter: [],
                publishFlag: false,
            };
        },

        methods: {
            checkSession () {
                this.loading = true;
                ajaxGet('/ajax/import-product/check-session').done(function (resp) {
                    this.loading = false;
                    if (resp.ok) {
                        let data = resp.data;
                        if (data.has) {
                            let info = data.info;
                            this.productInfo.name = info.name;
                            this.productInfo.alternateName = info.alternate_name;
                            this.productInfo.imageArr = info.image_arr;
                            this.productInfo.description = info.description;
                            this.productInfo.price = info.price;
                            this.digikalaLink = data.digikala_link;
                            this.imageFilter = [];
                            if (info.image_arr) {
                                for (var i=0;i<info.image_arr.length;i++) {
                                    this.imageFilter.push(1);
                                }
                            }
                            this.productLoaded = true;
                        }
                    }
                    else {
                        swalWarning(resp.msg);
                    }
                }.bind(this)).fail(function (err) {
                    this.loading = false;
                    swalConnectionError();
                }.bind(this));
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
                        this.productInfo.name = data.name;
                        this.productInfo.alternateName = data.alternate_name;
                        this.productInfo.imageArr = data.image_arr;
                        this.productInfo.description = data.description;
                        this.productInfo.price = data.price;
                        swalSuccess('اطلاعات محصول با موفقیت از دیجی کالا دریافت شد. لطفا اطلاعات را تکمیل کرده و محصول را ثبت کنید!');
                        this.imageFilter = [];
                        if (data.image_arr) {
                            for (var i=0;i<data.image_arr.length;i++) {
                                this.imageFilter.push(1);
                            }
                        }
                        this.productLoaded = true;
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

            imageFilterDo (index,action) {
                this.imageFilter[index] = action;
                this.$forceUpdate();
            },

            finalizeImportAndPublish () {
                this.publishFlag = true;
                this.finalizeImport();
            },

            resetForm () {
                this.digikalaLink = '';
                this.productInfo.name = '';
                this.productInfo.alternateName = '';
                this.productInfo.imageArr = [];
                this.productInfo.description = '';
                this.productInfo.price = '';
                this.imageFilter = [];
                this.publishFlag = false;
                this.productLoaded = false;               
            },

            finalizeImport () {
                if (!this.productInfo.name) {
                    swalWarning('لطفا نام محصول را وارد کنید!',false);
                    return;
                }

                if (!this.productInfo.price && this.productInfo.price !== 0 && this.productInfo.price !== '0') {
                    swalWarning('لطفا قیمت محصول را وارد کنید!',false);
                    return;
                }

                let imageFilterStr = '';
                for (var i=0;i<this.imageFilter.length;i++) {
                    imageFilterStr += this.imageFilter[i] + '#';
                }

                let data = {
                    product_name: this.productInfo.name,
                    product_alternate_name: this.productInfo.alternateName,
                    product_description: this.productInfo.description,
                    product_price: this.productInfo.price,
                    image_filter: imageFilterStr,
                    also_publish: this.publishFlag ? '1' : '0',
                };

                this.loading = true;
                this.finalizeLoading = true;
                ajaxPost('/ajax/import-product/finalize',data).done(function (resp) {
                    this.loading = false;
                    this.finalizeLoading = false;
                    if (resp.ok) {
                        swalSuccess('محصول با موفقیت در سیستم ثبت شد!');
                        this.resetForm();
                    }
                    else {
                        swalWarning(resp.msg);
                        if (resp.reset) {
                            this.resetForm();
                        }
                    }
                }.bind(this)).fail(function (err) {
                    this.loading = false;
                    this.finalizeLoading = false;
                    swalConnectionError();
                }.bind(this));
            },

            cancelImport () {
                this.loading = true;
                ajaxPost('/ajax/import-product/cancel').done(function (resp) {
                    this.loading = false;
                    if (resp.ok) {
                        this.resetForm();
                    }
                    else {
                        swalWarning(resp.msg);
                    }
                }.bind(this)).fail(function (err) {
                    this.loading = false;
                    swalConnectionError();
                }.bind(this));
            },
        },
    }
</script>
