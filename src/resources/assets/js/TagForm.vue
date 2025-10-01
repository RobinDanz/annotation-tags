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
            <div class="col-xs-4 form-group">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Tag name" title="New tag name"
                        v-model="name" />
                    <span class="input-group-btn">
                        <button class="btn btn-success" type="submit" title="Add the new tag"
                            v-bind:disabled="hasNoName"><span class="fa fa-plus" aria-hidden="true"></span></button>
                    </span>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
import { randomColor } from './utils';

/**
 * A component for a form to manually create a new tag
 *
 * @type {Object}
 */
export default {
    data() {
        return {
            name: '',
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
                color: this.color.substr(1),
            };
            this.name = '';
            this.color = randomColor();
            this.$emit('submit', tag);

        },
    },
};
</script>