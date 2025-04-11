<script>
import { disable } from '@biigle/ol/rotationconstraint';
import {handleErrorResponse} from './import';

export default {
    props: {
    },
    data() {
        return {}
    },
    methods: {
        addTags() {
            alert('abcd')
        },
        test() {
            console.log('===============================');
            console.log(this.$parent);
            console.log(this.$parent.selectedAnnotations);
            console.log('===============================');
        }
    },
    computed: {
        selected() {
            return this.$parent.selectedAnnotations.length > 0;
        }
    }
};
</script>

<template>
   <div>
      Tags
      <button v-if="selected" class="btn btn-default" v-on:click="test">Add Tags</button>
   </div>
</template>
