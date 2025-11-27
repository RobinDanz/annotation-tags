<template>
    <form v-on:submit.prevent="submit">
        <div class="row">
            <div class="col-xs-12 help-block">
                To add a new tag, choose a color and a name.
            </div>
            <div class="col-xs-4 form-group">
                <div class="input-group">
                    <input type="color" class="form-control" title="Label color" v-model="color" />
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" title="Get a random color"
                            v-on:click="refreshColor"><span class="fa fa-sync-alt" aria-hidden="true"></span></button>
                    </span>
                </div>
            </div>
            <div class="col-xs-4">
                <input type="text" class="form-control" placeholder="Tag name" title="New tag name" v-model="name" />
            </div>
            <div class="col-xs-4 form-group">
                <input type="text" class="form-control" placeholder="Tag value" title="New tag value" v-model="value" />
            </div>
            <div class="col-xs-12 help-block">
                Optionnaly select a set of labels to use this tag on.
            </div>
            <div class="col-xs-4 form-group">
                <multi-select 
                    id="allowed-labels"
                    v-model="labels"
                    :options="labelsOptions"
                    selected-icon="fa fa-check"
                    filterable
                    collapse-selected
                    title="Allowed labels"
                >
                </multi-select>
            </div>
            
        </div>
        <div class="row">
            <div class="col-xs-4 form-group">
                <button class="btn btn-success" 
                    type="submit" 
                    title="Add the new tag"
                    v-bind:disabled="hasNoName"
                >
                    Create new tag
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import { randomColor } from './utils';
import { MultiSelect } from 'uiv'

/**
 * A component for a form to manually create a new tag
 *
 * @type {Object}
 */
export default {
    components: {
        multiSelect: MultiSelect
    },
    data() {
        return {
            name: '',
            value: '',
            labels: [],
            labelsOptions: [],
            color: randomColor(),
        }
    },
    computed: {
        hasNoName() {
            return !this.name;
        },
    },
    methods: {
        refreshColor() {
            this.color = randomColor();
        },
        submit() {
            let tag = {
                name: this.name,
                value: this.value,
                color: this.color.substr(1),
                label_ids: this.labels,
            };

            this.name = '';
            this.value = '';
            this.color = randomColor();
            this.labels = [];
            this.$emit('submit', tag);
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
    }
};
</script>

<style scoped>
:deep(.dropdown-menu) {
    max-height: 400px;
    overflow-y: scroll;
}
</style>