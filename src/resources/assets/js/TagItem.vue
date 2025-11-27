
<template>
    <li class="label-tree-label" :class="classObject">
        <div class="label-tree-label__name" @mouseover="doHover" @mouseleave="dontHover">
            <span v-if="editing">
                <input type="color" class="form-control input-sm label-tree-color-input" v-model="newColor" />
                <input type="text" v-model="newName" v-on:keydown.enter="saveThis" class="form-control input-sm label-tree-label__name-input">
                <input type="text" v-model="newValue" v-on:keydown.enter="saveThis" class="form-control input-sm label-tree-label__name-input">
                <multi-select
                    size="sm"
                    v-model="newAllowedLabels"
                    :options="labelsOptions"
                    selected-icon="fa fa-check"
                    filterable
                    collapse-selected
                ></multi-select>
            </span>
            <span v-else>
                <span v-show="showColor" class="label-tree-label__color" :style="colorStyle"></span>
                <span v-text="tag.name" @mouseenter="dontHover"></span>
            </span>
            <span class="label-tree-label__buttons">
                <span>
                    <button v-show="showEditButton" :title="editTitle" @click.stop="editThis" class="btn btn-default btn-xs"><span aria-hidden="true" class="fa fa-pencil-alt"></span></button>
                    <button v-show="editing" :title="deleteTitle" @click.stop="deleteThis" class="btn btn-danger btn-sm"><span aria-hidden="true" class="fa fa-trash"></span></button>
                    <button v-show="editing" title="Save changes" @click.stop="saveThis" class="btn btn-success btn-sm"><span aria-hidden="true" class="fa fa-check"></span></button>
                    <button v-show="editing" title="Revert changes" @click.stop="revertThis" class="btn btn-default btn-sm"><span aria-hidden="true" class="fa fa-times"></span></button>
                </span>
            </span>
        </div>
        
    </li>
</template>

<script>
import { MultiSelect } from 'uiv';

/**
 * A component that displays a single tag.
 *
 * @type {Object}
 */
export default {
    name: 'tag-item',
    components: {
        multiSelect: MultiSelect
    },
    data() {
        return {
            hover: false,
            editing: false,
            oldValue: '',
            oldName: '',
            oldColor: '',
            newValue: '',
            newName: '',
            newColor: '',
            oldAllowedLabels: [],
            newAllowedLabels: [],
            labelsOptions: [],
        };
    },
    props: {
        tag: {
            type: Object,
            required: true
        }
    },
    computed: {
        showColor() {
            return true;
        },
        classObject() {
            return {
                'label-tree-label--editing': this.editing,
            };
        },
        colorStyle() {
            return {
                'background-color': '#' + this.tag.color,
            };
        },
        showEditButton() {
            return this.hover && !this.editing;
        },
        editTitle() {
            return 'Edit tag ' + this.tag.name;
        },
        deleteTitle() {
            return 'Remove tag ' + this.tag.name;
        },
    },
    methods: {
        editThis() {
            this.editing = true;
            this.oldValue = this.tag.value;
            this.oldName = this.tag.name;
            this.oldColor = this.tag.color;
            this.oldAllowedLabels = this.tag.label_ids;

            this.newValue = this.tag.value;
            this.newName = this.tag.name;
            this.newColor = '#' + this.tag.color;
            this.newAllowedLabels = this.tag.label_ids;
        },
        doHover() {
            this.hover = true;
        },
        dontHover() {
            this.hover = false;
        },
        saveThis() {
            this.tag.name = this.newName;
            this.tag.value = this.newValue;
            this.tag.color = this.newColor.substr(1);
            this.tag.label_ids = this.newAllowedLabels;
            this.editing = false;

            if (
                this.oldName !== this.tag.name 
                || this.oldValue !== this.tag.value 
                || this.oldColor !== this.tag.color
                || this.oldAllowedLabels !== this.tag.label_ids
            ) {
                this.emitSave(this.tag, () => this.editing = true);
            }
        },
        revertThis() {
            this.editing = false;
            this.tag.name = this.oldName;
            this.tag.value = this.oldValue;
            this.tag.color = this.oldColor;
            this.tag.label_ids = this.oldAllowedLabels; 
        },
        deleteThis() {
            this.emitDelete(this.tag);
        },
        emitDelete(tag) {
            this.$emit('delete', tag);
        },
        emitSave(tag, reject) {
            this.$emit('save', tag, reject);
        },
    },
    created() {
        const labels = biigle.$require('tagsDisplay.labels');

        labels.forEach((label) => {
            this.labelsOptions.push(
                {
                    value: label.id,
                    label: label.name
                }
            )
        });
    },
};
</script>

<style scoped>
:deep(.dropdown-menu) {
    max-height: 400px;
    overflow-y: scroll;
}
</style>