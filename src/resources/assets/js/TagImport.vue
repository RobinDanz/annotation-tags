<template>
    <div class="form-group">
        <label>Select a file</label>
        <input type="file" name="import" v-on:change="handleFile">
        <span class="help-block">
            The file must be a textfile with a tags separated by newline
        </span>
        <button class="btn btn-success" @click="importTags" :disabled="!data">Click !</button>
    </div>
</template>

<script>
import TagApi from './api/tags.js'
import { LoaderMixin } from './import'

/**
 * A component to handle tag import
 *
 * @type {Object}
 */
export default {
    mixins: [LoaderMixin],
    data() {
        return {
            error: false,
            success: false,
            data: null
        }
    },
    methods: {
        handleSuccess() {
            this.error = false;
            this.success = true;
            this.$emit('refresh');
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

            this.data = event.target.files[0]
            console.log(this.data);
        },
        handleFinally() {
            this.data = null;
            this.finishLoading();
        },
        importTags() {
            let formData = new FormData();
            formData.append('file', this.data);
            TagApi.importTags(formData)
                .then(this.handleSuccess)
                .catch(this.handleError)
                .finally(this.handleFinally);
        }
    },
};
</script>