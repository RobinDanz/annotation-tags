<script>
import TagsApi from './api/tags';
import TagGroupApi from './api/tagGroup';
import { LoaderMixin, handleErrorResponse } from './import'
import { MultiSelect } from 'uiv';

export default {
    name: 'TagsMultiSelect',
    mixins: [
        LoaderMixin
    ],
    components: {
        multiSelect: MultiSelect
    },
    props: {
        annotationId: {
            type: Number,
            required: true,
        }
    },
    data() {
        return {
            options: [],
            selectedOptions: [],
        }
    },
    watch: {
    },
    methods: {
        getAnnotationTags() {
            TagsApi.getAnnotation({ id: this.annotationId }).then(resp => {
                for (let tag of resp.data) {
                    this.selectedOptions.push(tag.id);
                }
                this.disableOptions();
            }).catch(function (response) {
                handleErrorResponse(response);
            })
        },
        getAllTags() {
            TagsApi.get().then(resp => {
                for (let tag of resp.data) {
                    this.options.push(this.formatTag(tag));
                }
            }).catch(function (response) {
                handleErrorResponse(response);
            })
        },
        getTagGroups() {
            TagGroupApi.get().then(resp => {
                this.groups = resp.data
            }).catch(handleErrorResponse);
        },
        updateRelation(ids) {
            TagsApi.updateRelation(
                { id: this.annotationId },
                { tags: ids }
            ).then(this.disableOptions).catch(function (response) {
                handleErrorResponse(response);
            })
        },
        formatTag(tag) {
            const t = {
                value: tag.id,
                label: tag.name,
                group: '',
                disabled: false
            }

            if (tag.tag_group_id) {
                const group = this.groups.find(g => g.id === tag.tag_group_id);
                t.group = group.name;
            }
            return t;
        },
        selectedChange(value) {
            this.updateRelation(value);
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
        }
    },
    mounted() {
        this.getTagGroups();
        this.getAllTags();
        this.getAnnotationTags();
    }
}
</script>
<template>
    <span>
       <multi-select 
            ref="refSelect"
            v-model="selectedOptions"
            :options="options"
            v-on:change="selectedChange"
            size="sm"
            selected-icon="fa fa-check"
            filterable
            collapse-selected
        >
        </multi-select>
    </span>
</template>

<style scoped>
:deep(.dropdown-menu) {
    right: 0;
    left: unset;
    max-height: 300px;
    overflow-y: scroll;
}
</style>