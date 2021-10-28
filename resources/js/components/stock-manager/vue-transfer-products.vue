<template>
    <div class="modal-lg modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Перенести товары</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="storage_from" class="form-label">Склад из которого перенести товары:</label>
                        <select class="form-select" id="storage_from" v-model="storageFrom">
                            <option :value="storage.id" v-for="storage in storages"
                                    :key="storage.id">{{storage.name}}</option>
                        </select>
                        <div class="limited-box limited-box_static_normal mt-3">
                            <ul class="list-group">
                                <li class="list-group-item mb-2 product-item"
                                    v-for="product in productsFrom" :key="product.id">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="product" :id="'product' + product.id"
                                                   v-model="selectedProducts"
                                                   :value="product.id">
                                            <label class="form-check-label product-item__title text-truncate"
                                                   :for="'product' + product.id">{{product.name}}</label>
                                        </div>
                                        <vue-input-number
                                            v-model="product.currentCount"
                                            @input="onInput(product)"
                                            :max="product.pivot.count"></vue-input-number>
                                    </div>
                                    <span class="product-item__count">
                                        Остаток на этом складе:
                                        {{getCountProduct(product.pivot.count, product.currentCount)}}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="storage_to" class="form-label">Склад в который переносятся товары:</label>
                        <select class="form-select" id="storage_to" v-model="storageTo">
                            <option :value="storage.id" v-for="storage in getStorageTo"
                                    :key="storage.id">{{storage.name}}</option>
                        </select>
                        <div class="limited-box limited-box_static_normal mt-3">
                            <ul class="list-group">
                                <li class="list-group-item"
                                    v-for="product in productsTo" :key="product.id">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">{{product.name}}</div>
                                        </div>
                                        <span class="badge bg-primary rounded-pill"
                                              title="Кол-во товаров на складе">{{product.pivot.count}}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :disabled="showLoader" @click="onSendData">
                    <span v-show="!showLoader">Сохранить</span>
                    <span class="spinner-border spinner-border_small text-light" role="status" v-show="showLoader">
                        <span class="visually-hidden">Загрузка...</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import {syncProducts, restoreStateStorage} from "../../helper/products";
export default {
    props: ['signal'],
    data: function () {
        return {
            showLoader: false,
            storages: [],
            storageFrom: 0,
            storageTo: 0,
            selectedProducts: [],
            productsFrom: [],
            productsTo: [],
        }
    },
    computed: {
        getStorageTo: function () {
            return this.storages.filter(fn => fn.id !== this.storageFrom);
        }
    },
    watch: {
        signal: function (val) {
            if (val) {
                this.$axios.get('/storage').then((response) => {
                    this.storages = response.data.storages;
                    this.storageFrom = 1;
                    this.storageTo = 2;
                });
            } else {
                this.reset();
            }
        },
        // When we changed the warehouse from which we transfer the products
        storageFrom: function (id) {
            if (id === this.storageTo) {
                let storage = this.storages.find(fn => fn.id === id);
                if (storage !== undefined) {
                    storage.products = restoreStateStorage(storage.products);
                    this.storageTo = null;
                }
            }
            if (this.storages.length) {
                let storage = this.storages.find(fn => fn.id === id);
                let products = storage.products;
                products.forEach((product) => {
                    this.$set(product, 'currentCount', 1);
                });
                this.selectedProducts = [];
                this.productsFrom = products;
                if (this.productsTo.length) {
                    this.productsTo = restoreStateStorage(this.productsTo);
                }
            }
        },
        // When we changed the warehouse to which we transfer the products
        storageTo: function (id, oldId) {
            let storage = this.storages.find(fn => fn.id === id);
            if (storage !== undefined) {
                if (oldId) {
                    let oldStorage = this.storages.find(fn => fn.id === oldId);
                    // Return the state of the previous warehouse to the original
                    oldStorage.products = restoreStateStorage(oldStorage.products);
                }
                this.productsTo = storage.products;
                this.syncStateStorage(this.selectedProducts);
            } else {
                this.productsTo = [];
            }
        },
        // When you selected a product using checkbox
        selectedProducts: function (arrayId) {
            if (this.storageTo !== undefined) {
                this.syncStateStorage(arrayId);
            }
        }
    },
    methods: {
        syncStateStorage: function (arrayId) {
            arrayId.forEach((id) => {
                let productFrom = this.productsFrom.find(fn => fn.id === id);
                let product = this.productsTo.find(fn => fn.id === id);
                if (product !== undefined && !product.updated && !product.created) {
                    product.pivot.count += productFrom.currentCount;
                    this.$set(product, 'increased', productFrom.currentCount);
                    this.$set(product, 'updated', true);
                } else if (product === undefined) {
                    let newProduct = JSON.parse(JSON.stringify(productFrom));
                    this.$set(newProduct, 'created', true);
                    newProduct.pivot.count = newProduct.currentCount;
                    this.productsTo.push(newProduct);
                }
            });
            syncProducts(this.selectedProducts, this.productsTo);
        },
        onInput: function (product) {
            if (this.selectedProducts.indexOf(product.id) !== -1) {
                if (this.productsTo.length) {
                    let productTo = this.productsTo.find(fn => fn.id === product.id);
                    if (productTo !== undefined) {
                        if (productTo.created) {
                            productTo.pivot.count = product.currentCount;
                        } else {
                            let countDefault = productTo.pivot.count - productTo.increased;
                            productTo.pivot.count = product.currentCount + countDefault;
                            this.$set(productTo, 'increased', product.currentCount);
                            this.$set(productTo, 'updated', true);
                        }
                    }
                }
            }
        },
        onSendData: function (e) {
            if (this.selectedProducts.length && this.productsTo.length) {
                let target = e.target;
                let btn = null;
                if (target.tagName === 'SPAN') {
                    btn = target.parentNode;
                } else {
                    btn = target;
                }
                this.showLoader = true;
                btn.style.height = btn.offsetHeight + "px";
                btn.style.width = btn.offsetWidth + "px";
                this.$axios.post('/storage/transfer', this.generationData()).then((response) => {
                    this.showLoader = false;
                    btn.removeAttribute('style');
                    if (response.data.status) {
                        this.storages = response.data.storages_updated;
                        this.selectedProducts = [];
                        this.updatedAfterSend();
                    }
                });
            }
        },
        getCountProduct: function (max, count) {
            return max - count;
        },
        reset: function () {
            this.storages = [];
            this.storageFrom = 0;
            this.storageTo = 0;
            this.productsFrom = [];
            this.productsTo = [];
        },
        generationData: function () {
            let dataFrom = [];
            let dataTo = [];
            let fd = new FormData();
            fd.append('from_storage', this.storageFrom);
            fd.append('to_storage', this.storageTo);
            this.selectedProducts.forEach((id) => {
                let product = this.productsFrom.find(fn => fn.id === id);
                if (product !== undefined) {
                    dataFrom.push({id: product.id, count: product.currentCount});
                }
            });
            this.productsTo.forEach((product) => {
                dataTo.push({id: product.id, count: product.pivot.count});
            });
            fd.append('data_from', JSON.stringify(dataFrom));
            fd.append('data_to', JSON.stringify(dataTo));
            return fd;
        },
        updatedAfterSend: function () {
            let storageFrom = this.storages.find(fn => fn.id === this.storageFrom);
            let productsFrom = storageFrom.products;
            productsFrom.forEach((product) => {
                this.$set(product, 'currentCount', 1);
            });
            this.productsFrom = productsFrom;
            if (this.storageTo !== undefined) {
                let storageTo = this.storages.find(fn => fn.id === this.storageTo);
                this.productsTo = storageTo.products;
            }
        }
    }
}
</script>
