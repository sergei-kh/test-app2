<template>
    <div class="modal fade" :id="id" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
        <slot :signal="signal"></slot>
    </div>
</template>
<script>
import {Modal} from 'bootstrap'
export default {
    props: ['id', 'triggerId'],
    data: function () {
        return {
            bootstrap_modal: null,
            signal: false,
        }
    },
    mounted: function () {
        this.bootstrap_modal = new Modal(this.$el, {
            keyboard: false,
        });
        let btn = document.getElementById(this.triggerId);
        btn.addEventListener('click', () => {
            this.bootstrap_modal.show();
        });
        this.$el.addEventListener('show.bs.modal', () => {
            this.signal = true;
        });
        this.$el.addEventListener('hide.bs.modal', () => {
            this.signal = false;
        });
    }
}
</script>
