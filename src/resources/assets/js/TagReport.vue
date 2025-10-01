<template>
    <form >
        <input type="file" name="annotation" v-on:change="handleFile">
        <button @click="createReport">Click !</button>
    </form>
</template>

<script>
import TagApi from './api/tags'
import { LoaderMixin, handleErrorResponse } from './import'

/**
 * A component to handle tag report generation
 *
 * @type {Object}
 */
export default {
    mixins: [LoaderMixin],
    data() {
        return {
            error: false,
            success: false
        }
    },
    computed: {

    },
    methods: {
        handleSuccess() {
            this.error = false;
            this.success = true;
        },
        handleError(response) {
            this.success = false;
            let knownError = response.body.errors && response.body.errors.file;
            if (knownError) {
                if (Array.isArray(knownError)) {
                    this.error = knownError[0];
                } else {
                    this.error = knownError;
                }
            } else {
                this.handleErrorResponse(response);
            }
        },
        handleFile(event) {
            this.startLoading();
            console.log(event);
            let data = new FormData();
            data.append('file', event.target.files[0]);
            console.log(data);
            TagApi.createReport(data)
                .then(this.handleSuccess)
                .catch(this.handleError)
                .finally(this.finishLoading);
        },
        createReport() {
            TagApi.createReport({}).then(console.log, handleErrorResponse);
        }
    },
};
</script>