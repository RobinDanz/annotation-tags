
<template>
    <li class="label-tree-label" :class="classObject">
        <div class="label-tree-label__name" @mouseover="doHover" @mouseleave="dontHover">
            <span v-if="editing">
                <input type="color" class="form-control input-sm label-tree-color-input" v-model="newColor" />
                <input type="text" v-model="newName" v-on:keydown.enter="saveThis" class="form-control input-sm label-tree-label__name-input">
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
import { randomColor } from './utils';

/**
 * A component that displays a single tag.
 *
 * @type {Object}
 */
export default {
    name: 'tag-item',
    data() {
        return {
            hover: false,
            editing: false,
            oldName: '',
            oldColor: '',
            newName: '',
            newColor: '',
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
            this.oldName = this.tag.name;
            this.oldColor = this.tag.color;
            this.newName = this.tag.name;
            this.newColor = '#' + this.tag.color;
        },
        doHover() {
            this.hover = true;
        },
        dontHover() {
            this.hover = false;
        },
        saveThis() {
            this.tag.name = this.newName;
            this.tag.color = this.newColor.substr(1);
            this.editing = false;

            if (this.oldName !== this.tag.name || this.oldColor !== this.tag.color) {
                this.emitSave(this.tag, () => this.editing = true);
            }
        },
        revertThis() {
            this.editing = false;
            this.tag.name = this.oldName;
            this.tag.color = this.oldColor;
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
        console.log(randomColor());
    },
};
</script>
