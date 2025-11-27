<template>
    <div class="annotation-tags">
        <div class="form-group">
            <multi-select
                class="tags-multi-select"
                ref="refSelect"
                v-model="selectedOptions"
                v-on:change="selectedOptionsChanged"
                :options="options"
                :disabled="isDisabled"
                selected-icon="fa fa-check"
                filterable
            >
            </multi-select>
        </div>
    </div>
    
</template>

<script>
import TagsApi from './api/tags';
import { LoaderMixin, handleErrorResponse } from './import'
import { MultiSelect } from 'uiv';

export default {
    mixins: [
        LoaderMixin
    ],
    components: {
        multiSelect: MultiSelect
    },
    data() {
        return {
            tags: [],
            options: [],
            selectedOptions: [],
        }
    },
    props: {
        annotations: {
            type: Array,
            required: true,
        }
    },
    methods: {
        refreshOptions(annotation) {
            this.options = [];
            this.tags.forEach((tag) => {
                if(tag.label_ids.length === 0 || tag.label_ids.includes(annotation.labels[0].label_id))
                this.options.push({
                    value: tag.id,
                    label: tag.name,
                    group: tag.group ? tag.group.name : '',
                    disabled: false
                });
            });
        },
        disableOptions() {
            const selectedGroups = this.selectedOptions
            .map(val => {
                const option = this.options.find(o => o.value === val);
                return option ? option.group : null;
            })
            .filter(group => group);

            this.options.forEach(option => {
                option.disabled = selectedGroups.includes(option.group) && !this.selectedOptions.includes(option.value);
            });
        },
        selectedOptionsChanged(tagIds) {
            if (this.selectedAnnotations.length == 1) {
                const annotationId = this.selectedAnnotations[0].id;
                this.updateRelation(tagIds, annotationId);
            }
        },
        getTags() {
            TagsApi.get().then(resp => {
                this.tags = resp.data;
            }).catch(function (response) {
                handleErrorResponse(response);
            })
        },
        getAnnotationTags(annotationId) {
            TagsApi.getAnnotation({ id: annotationId }).then(resp => {
                for (let tag of resp.data) {
                    this.selectedOptions.push(tag.id);
                }
                this.disableOptions();
            }).catch(function (response) {
                handleErrorResponse(response);
            });
        },
        updateRelation(tagIds, annotationId) {
            TagsApi.updateRelation(
                { id: annotationId },
                { tags: tagIds }
            ).then(this.disableOptions).catch(function (response) {
                handleErrorResponse(response);
            });
        }
    },
    computed: {
        selectedAnnotations() {
            return this.annotations.filter((a) => a.selected);
        },
        isDisabled() {
            return this.selectedAnnotations.length !== 1;
        },
    },
    watch: {
        selectedAnnotations()  {
            this.selectedOptions = [];
            if (this.selectedAnnotations.length == 1) {
                const annotationId = this.selectedAnnotations[0].id;
                this.refreshOptions(this.selectedAnnotations[0]);
                this.getAnnotationTags(annotationId);
            }
        }
    },
    mounted() {
        this.getTags();
    }
}
</script>

<style scoped>

:deep(.dropdown-menu) {
    max-height: 300px;
    overflow-y: scroll;
    position: unset;
}

.annotation-tags {
    padding: 0 1em 1em;
}

.tags-multi-select {
    display: flex;
    flex-direction: column-reverse;
}
</style>