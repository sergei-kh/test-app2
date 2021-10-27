<template>
    <div class="modal-lg modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавить / списать</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form @submit.prevent="onSubmit">
                <div class="modal-body">
                    <div class="mb-3">
                        <p class="fw-bolder">Выберите склад:</p>
                        <div class="limited-box limited-box_static_small">
                            <div class="form-check mb-2" v-for="storage in storages" :key="storage.id">
                                <input class="form-check-input" type="radio"
                                       name="storage" :id="'storage' + storage.id"
                                       :value="storage.id" v-model="selected_storage">
                                <label class="form-check-label"
                                       :for="'storage' + storage.id">{{storage.name}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="fw-bolder">Выберите товары:</p>
                        <div class="limited-box limited-box_static_normal">
                            <ul class="list-group">
                                <li class="list-group-item mb-2 d-flex align-items-center"
                                    v-for="product in products" :key="product.id">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="product" :id="'product' + product.id"
                                               :value="product.id" v-model="selected_products">
                                        <label class="form-check-label"
                                               :for="'product' + product.id">{{product.name}}</label>
                                    </div>
                                    <vue-input-number v-model="product.count"></vue-input-number>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" :disabled="showLoader">
                        <span v-show="!showLoader">Сохранить</span>
                        <span class="spinner-border spinner-border_small text-light" role="status" v-show="showLoader">
                            <span class="visually-hidden">Загрузка...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    props: ['signal'],
    data: function () {
        return {
            showLoader: false,
            selected_storage: 0,
            storages: [],
            selected_products: [],
            products: [], // Products that are displayed in the list
        }
    },
    watch: {
        // When the modal changes visibility
        signal: function (val) {
            if (val) {
                this.$axios.get('/storage/products').then((response) => {
                    this.storages = response.data.storages;
                    this.products = response.data.products;
                    this.products.forEach((product) => {
                        this.$set(product, 'count', 1);
                    });
                });
            } else {
                this.selected_storage = 0;
                this.storages = [];
                this.selected_products = [];
                this.products = [];
            }
        },
        // When the storage changes
        selected_storage: function (id) {
            let storage = this.storages.find(fn => fn.id === id);
            if (storage !== undefined) {
                this.selected_products = [];
                this.products.forEach((product) => {
                    product.count = 1;
                });
                storage.products.forEach((productStorage) => {
                    this.selected_products.push(productStorage.id); // ID of products from storage
                    let productFromList = this.products.find(fn => fn.id === productStorage.id);
                    productFromList.count = productStorage.pivot.count;
                });
            }
        },
    },
    methods: {
        onSubmit: function (e) {
            if (this.selected_storage > 0) {
                let path = `/storage/${this.selected_storage}`;
                let fd = new FormData();
                let data = this.getData();
                fd.append('products', JSON.stringify(data));
                fd.append('_method', 'PUT');
                this.showLoader = true;
                let btn = e.submitter;
                btn.style.height = btn.offsetHeight + "px";
                btn.style.width = btn.offsetWidth + "px";
                this.$axios.post(path, fd).then((response) => {
                    this.showLoader = false;
                    btn.removeAttribute('style');
                    if (response.data.status) {
                        let storage = this.storages.find(fn => fn.id === response.data.id);
                        if (storage !== undefined) {
                            storage.products = response.data.products;
                        }
                    }
                });
            } else {
                alert('Необходимо выбрать склад');
            }
        },

        getData() {
            let output = [];
            this.selected_products.forEach((id) => {
                let product = this.products.find(fn => fn.id === id);
                output.push({
                    id: id,
                    count: product.count,
                });
            });
            return output;
        }
    }
}
</script>
