<template>
    <div class="">
        <div class="form-group">
            <label>Create group of mutually exclusive tags</label>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-4">
                        <input type="text" class="form-control" placeholder="Group name" title="New group name" v-model="newGroup.name" />
                    </div>
                    <div class="">
                        <multi-select
                            v-model="newGroup.tags"
                            :options="tagsOptions"
                            selected-icon="fa fa-check"
                            filterable
                            collapse-selected
                        ></multi-select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-success" @click="createGroup" :disabled="createButtonDisabled">Create new group</button>
            </div>
            <div>
                <tag-group 
                    v-for="group in groups"
                    :key="group.id"
                    :group="group"
                    :tags="tagsOptions"
                    v-on:delete="deleteGroup"
                    v-on:save="saveGroup"
                ></tag-group>
            </div>
        </div>
    </div>
</template>

<script>
import { MultiSelect } from 'uiv';
import TagGroup from './TagGroup.vue'
import { LoaderMixin, handleErrorResponse } from './import';
import TagGroupApi from './api/tagGroup'

export default {
    mixins: [
        LoaderMixin
    ],
    components: {
        multiSelect: MultiSelect,
        tagGroup: TagGroup,
    },
    props: {
        tags: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            labelSelected: [],
            labelOptions: [],
            groups: [],
            newGroup: {
                name: '',
                tags: [],
            }
        }
    },
    methods: {
        buildLabelOptions(label) {
            const option = {};
            option.value = label.id;
            option.label = label.name;

            return option
        },
        groupCreated(response) {
            const group = {
                id: response.data.id,
                name: response.data.name,
                tags: response.data.tags.map((tag) => tag.id),
            }
            this.groups.push(group);

            this.newGroup.name = '';
            this.newGroup.tags = [];
        },
        groupDeleted(group) {
            this.groups = this.groups.filter((gr) => gr.id != group.id);
        },
        createGroup() {
            if (this.loading) {
                return;
            }
            this.startLoading();
            TagGroupApi.save({}, this.newGroup)
                .then(this.groupCreated, handleErrorResponse)
                .finally(() => {
                    this.finishLoading();
                    this.refreshTags();
                });
        },
        deleteGroup(group) {
            TagGroupApi.delete({id: group.id})
                .then(this.groupDeleted(group), handleErrorResponse)
                .finally(() => {
                    this.finishLoading();
                    this.refreshTags();
                });
        },
        refreshTags() {
            this.$emit('refresh');
        },
        saveGroup(group, reject) {
            console.log('save:', group);
            TagGroupApi.update({id: group.id}, {name: group.name, tags: group.tags})
                .catch((response) => {
                    console.log(response);
                    reject();
                    this.handleErrorResponse(response);
                })
                .finally(() => {
                    console.log('refresh');
                    this.refreshTags();
                
                })
        },
        mapTags(tags) {
            return tags.map((tag) => {
                return {
                    value: tag.id,
                    label: tag.name,
                    disabled: tag.tag_group_id ? true : false
                };
            });
        }
    },
    computed: {
        tagsOptions() {
            return this.mapTags(this.tags);
        },
        createButtonDisabled() {
            return this.newGroup.name === '' || this.newGroup.tags.length == 0;
        }
    },
    mounted() {
        const labels = biigle.$require('tagsDisplay.labels');
        labels.forEach((label) => {
            this.labelOptions.push(this.buildLabelOptions(label));
        });

        this.groups = biigle.$require('tagsDisplay.groups');
    }
}
</script>

<style scoped>
:deep(.dropdown-menu) {
    max-height: 400px;
    overflow-y: scroll;
}
</style>