<template>
    <div class="d-flex align-items-center ms-3 product-count product-count_limit">
        <label>кол-во:</label>
        <input type="number" class="form-control ms-3 form-control-sm product-count__input"
               :value="valueNumber" @input="onInput" @keypress="onKeypress">
    </div>
</template>
<script>
import {toInt} from "../helper/number";
export default {
    model: {
        prop: 'valueNumber',
        event: 'input'
    },
    props: {
        valueNumber: Number,
        max: Number,
    },
    methods: {
        onInput: function (e) {
            let target = e.target;
            let value = toInt(target.value);
            if (this.max !== undefined) {
                let max = toInt(this.max);
                if (value > 0 && value <= max) {
                    this.$emit('input', value);
                } else if (value > max) {
                    this.$emit('input', max);
                    target.value = max;
                } else {
                    this.$emit('input', 1);
                    target.value = 1;
                }
            } else {
                if (value > 0) {
                    this.$emit('input', value);
                } else {
                    this.$emit('input', 1);
                    target.value = 1;
                }
            }
        },
        onKeypress: function (e) {
            let key = e.key;
            if (key === '.' || key === ',' || key === '-' || key === '+' || key === 'e') {
                e.preventDefault();
            }
        }
    }
}
</script>
