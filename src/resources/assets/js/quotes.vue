<script>
import {handleErrorResponse} from './import';

export default {
    data() {
        return {
            quote: '',
        };
    },
    methods: {
        test() {
            alert('abcd')
        }
    }
};
</script>

<template>
   <div>
      <blockquote>
         Test 123
      </blockquote>
      <button class="btn btn-default" v-on:click="test">refresh</button>
   </div>
</template>
