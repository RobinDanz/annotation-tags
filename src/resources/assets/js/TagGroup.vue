<script>
import { MultiSelect } from 'uiv';
export default {
    components: {
        multiSelect: MultiSelect
    },
    props: {
        group: {
            type: Object,
            required: true
        },
        tags: {
            type: Array,
            required: true,
        },
        new: {
            type: Boolean,
            default: false,
        }
    },
    data() {
        return {
            hover: false,
            editing: false,
            oldName: '',
            oldTags: [],
            newName: '',
            newTags: [],
            tagSelected: [],
        }
    },
    methods: {
        doHover() {
            this.hover = true;
        },
        dontHover() {
            this.hover = false;
        },
        editThis() {
            this.editing = true;
            this.oldName = this.group.name;
            this.oldTags = this.group.tags;
        },
        revertThis() {
            this.editing = false;
            this.group.name = this.oldName;
            this.group.tags = this.oldTags;
        },
        deleteThis() {
            this.$emit('delete', this.group);
        },
        saveThis() {
            this.editing = false;
            this.$emit('save', this.group, () => this.editing = true);
        }
    },
    computed: {
        showEditButton() {
            return this.hover && !this.editing;
        },
        editTitle() {
            return 'Edit group ' + this.group.name;
        },
        deleteTitle() {
            return 'Remove group ' + this.group.name;
        },
        tagOptions() {
            return this.tags.map((tag) => {
                if(this.group.tags.includes(tag.value)) {
                    tag.disabled = false;
                }
                return tag;
            });
        }
    },
}
</script>

<template>
    <div class="panel panel-default group-container" @mouseover="doHover" @mouseleave="dontHover">
        
        <span v-if="editing">
            <div class="form-group">
                <input type="text" v-model="group.name" class="form-control input-sm"></input>
            </div>
            <div class="form-group">
                <multi-select 
                    v-model="group.tags"
                    :options="tagOptions"
                    selected-icon="fa fa-check"
                    filterable
                    collapse-selected
                >
                </multi-select>
            </div>
            
        </span>
        <span v-else>
            <div class="group-title row">
                <div class="col-xs-6">
                    <label>{{group.name}}</label>
                </div>
                <div class="col-xs-6 group-edit-button">
                    <button :title="editTitle" @click.stop="editThis" class="btn btn-default btn-xs"><span aria-hidden="true" class="fa fa-pencil-alt"></span></button>
                </div>
            </div>
            
            <label class="group-subtitle">{{ group.tags.length }} tags selected</label>
        </span>
        
        <span class="">
            <button v-show="editing" :title="deleteTitle" @click.stop="deleteThis" class="btn btn-danger btn-sm"><span aria-hidden="true" class="fa fa-trash"></span></button>
            <button v-show="editing" title="Save changes" @click.stop="saveThis" class="btn btn-success btn-sm"><span aria-hidden="true" class="fa fa-check"></span></button>
            <button v-show="editing" title="Revert changes" @click.stop="revertThis" class="btn btn-default btn-sm"><span aria-hidden="true" class="fa fa-times"></span></button>
        </span>
    </div>
</template>

<style scoped>
.group-container {
    padding: 5px;
}

.group-title {
    /* display: block; */
    font-size: large;
    margin-bottom: 20px;
}

.group-edit-button {
    display: flex;
    justify-content: end;
}
</style>