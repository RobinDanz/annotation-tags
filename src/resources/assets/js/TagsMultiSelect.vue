<script>
import TagsApi from './api/tags';
import { LoaderMixin, handleErrorResponse } from './import'
import { randomColor } from './utils';

export default {
    name: 'TagsMultiSelect',
    mixins: [
        LoaderMixin
    ],
    data() {
        return {
            isOpen: false,
            options: [],
            selectedOptions: [],
            search: '',
            newOption: '',
            checked: true
        }
    },
    computed: {
        filteredOptions() {
            const term = this.search.toLowerCase()
            let vals = this.options.filter(opt =>
                opt.name.toLowerCase().includes(term)
            )
            .map(el => {
                let sel = this.selectedOptions.find(o => o.id == el.id);
                if(sel) {
                    el.value = sel.value;
                }
                return el;
            });
            return vals
        }
    },
    methods: {
        toggle() {
            this.isOpen = !this.isOpen
        },
        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.isOpen = false
            }
        },
        isSelected(option) {
            let selected = this.selectedOptions.some(o => o.id === option.id);
            return selected;
        },
        toggleSelection(option) {
            if (this.isSelected(option)) {
                this.selectedOptions = this.selectedOptions.filter(o => o.id !== option.id)
                this.detachTag(option); // Detach tags when deselected
            } else {
                this.selectedOptions.push(option)
                this.attachTag(option); // Attach tags when selected
            }
        },
        updateTagValue(option) {
            TagsApi.updateTag(
                { id: this.$parent.annotation.id },
                {
                    tag_id: option.id,
                    value: option.value,
                }
            )
            .then(resp => {
                this.selectedOptions.push(option);
            })
            .catch(function (response) {
                handleErrorResponse(response)
            });
        },
        // addOption() {
        //     const trimmed = this.newOption.trim()

        //     TagsApi.save({
        //         'name': trimmed,
        //         'color': randomColor(),
        //     }).then(resp => {
        //         let tag = this.formatTag(resp.data);
        //         if (trimmed && !this.options.some(o => o.id === tag.id)) {
        //             this.options.push(tag);
        //         }
        //     }).catch(function (response) {
        //         handleErrorResponse(response);
        //     })
        //     .finally(this.finishLoading);
        //     this.newOption = ''
        //     this.search = ''
        // },
        getAnnotationTags() {
            TagsApi.getAnnotation({ id: this.$parent.annotation.id }).then(resp => {
                for (let tag of resp.data) {
                    const formated = this.formatTag(tag);
                    this.selectedOptions.push(formated);
                }
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
        attachTag(option) {
            TagsApi.attachTag(
                { id: this.$parent.annotation.id },
                { tag_id: option.id }
            ).catch(function (response) {
                handleErrorResponse(response);
            })
        },
        detachTag(option) {
            TagsApi.detachTag(
                { id: this.$parent.annotation.id },
                { tag_id: option.id }
            )
            .then(resp => {
                option.value = undefined;
            })
            .catch(function (response) {
                handleErrorResponse(response);
            })
        },
        deleteTag(option) {
            this.options = this.options.filter(o => o.id !== option.id);
            this.selectedOptions = this.selectedOptions.filter(o => o.id !== option.id);
            TagsApi.delete({ id: option.id })
            .catch(function (response) {
                handleErrorResponse(response);
            })
        },
        formatTag(tag) {
            return {
                id: tag.id,
                name: tag.name,
                value: tag.pivot,
            }
        },
    },
    mounted() {
        this.getAllTags();
        this.getAnnotationTags();

        document.addEventListener('click', this.handleClickOutside);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.handleClickOutside)
    }
}
</script>
<template>
    <span class="dropdown" :class="{ open: isOpen }">
        <span class="dropdown-toggle" @click="toggle">
            <i class="fa fa-tags"></i>
            <span class="caret"></span>
        </span>
        <div class="dropdown-menu">
            <input v-model="search" class="form-control" type="text" placeholder="Rechercher..." />
            <ul class="list-unstyled">
                <li v-for="(option, index) in filteredOptions" :key="index">
                    <div class="checkbox">
                        <label class="tag-selected">
                            <input 
                                type="checkbox" 
                                :checked="isSelected(option)"
                                @change.prevent="toggleSelection(option)" 
                            >
                            {{ option.name }}
                        </label>
                        <input 
                            class="form-control tag-value" 
                            type="text"
                            v-model="option.value"
                            @change="updateTagValue(option)"
                        >
                    </div>
                </li>
                <li v-if="filteredOptions.length === 0" class="text-muted">Aucune option</li>
            </ul>
            <!-- <div class="input-group">
                <input v-model="newOption" type="text" class="form-control" placeholder="Nouvelle option" />
                <span class="input-group-btn">
                    <button class="btn btn-success" @click="addOption" :disabled="!newOption.trim()">
                        <span aria-hidden="true" class="fa fa-plus"></span>
                    </button>
                </span>
            </div> -->
        </div>
    </span>
</template>

<style scoped>
.checkbox {
    display: flex;
}

.dropdown-toggle {
    margin: 3px;
    float: right;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: -33px;
    z-index: 1000;
    padding: 10px 3px 10px 10px;
    padding-bottom: 3px;
    width: 200px;
    border-radius: 4px;
}

.list-unstyled {
    margin: 10px 0px 3px 0px;
    max-height: 150px;
    overflow-y: auto;
}

.tag-value {
    margin-left: 5px;
    margin-right: 2px;
}

.tag-selected {
    margin: auto;
}
</style>