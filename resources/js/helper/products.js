/**
 * Synchronizes products.
 * @param selectedId
 * @param products
 */
export function syncProducts(selectedId, products) {
    products.forEach((product, index) => {
        if (product.updated) {
            if (!selectedId.length) {
                product.pivot.count = product.pivot.count - product.increased;
                product.updated = false;
            } else {
                let findIndex = selectedId.findIndex(function (id) {
                    if (product.id === id) {
                        return true;
                    }
                });
                if (findIndex === -1) {
                    product.pivot.count = product.pivot.count - product.increased;
                    product.updated = false;
                }
            }
        } else if (product.created) {
            if (!selectedId.length) {
                products.splice(index, 1);
            } else {
                let findIndex = selectedId.findIndex(function (id) {
                    if (product.id === id) {
                        return true;
                    }
                });
                if (findIndex === -1) {
                    products.splice(index, 1);
                }
            }
        }
    });
}

/**
 * Restores the state of products in stock
 * @param products
 */
export function restoreStateStorage(products) {
    let removeId = [];
    let output = products.slice();
    output.forEach((product) => {
        if (product.updated) {
            product.pivot.count = product.pivot.count - product.increased;
            product.updated = false;
        } else if (product.created) {
            removeId.push(product.id);
        }
    });
    if (removeId.length) {
        return output.filter(function (element) {
            return removeId.indexOf(element.id) === -1;
        });
    } else {
        return output;
    }
}
