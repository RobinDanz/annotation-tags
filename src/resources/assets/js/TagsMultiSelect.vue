<script>
import TagsApi from './api/tags';
import { LoaderMixin, handleErrorResponse } from './import'

export default {
    name: 'TagsMultiSelect',
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
            return this.options.filter(opt =>
                opt.name.toLowerCase().includes(term)
            )
        },
    },
    methods: {
        test() {
            return true;
        },
        toggle() {
            this.isOpen = !this.isOpen
        },
        isSelected(option) {
            let selected = this.selectedOptions.some(o => o.id === option.id);
            console.log(selected)
            return selected;
        },
        toggleSelection(option) {
            console.log('Toggling selection for:', option);
            if (this.isSelected(option)) {
                this.selectedOptions = this.selectedOptions.filter(o => o.id !== option.id)
                this.detachTag(option); // Detach tags when deselected
            } else {
                this.selectedOptions.push(option)
                this.attachTag(option); // Attach tags when selected
            }
        },
        addOption() {
            const trimmed = this.newOption.trim()

            TagsApi.save({
                'name': trimmed
            }).then(resp => {
                let tag = this.formatTag(resp.data);
                console.log('Tag ajouté:', tag);
                if (trimmed && !this.options.some(o => o.id === tag.id)) {
                    this.options.push(tag);
                }
            })
            this.newOption = ''
            this.search = ''
        },
        getAnnotationTags() {
            TagsApi.getAnnotation({ id: this.$parent.annotation.id }).then(resp => {
                for (let tag of resp.data) {
                    this.selectedOptions.push(this.formatTag(tag));
                }
                console.log('Tags pour annotation:', this.selectedOptions);
            }).catch(error => {
                console.error('Erreur lors de la récupération des annotations:', error)
            })
        },
        getAllTags() {
            TagsApi.get().then(resp => {
                for (let tag of resp.data) {
                    this.options.push(this.formatTag(tag));
                }
                console.log('Tags récupérés:', this.options);
            }).catch(error => {
                console.error('Erreur lors de la récupération des tags:', error)
            })
        },
        attachTag(option) {
            TagsApi.attachTag(
                { id: this.$parent.annotation.id },
                { tag_id: option.id }
            ).then(resp => {
                console.log('Tags attachés:', resp.data);
            }).catch(error => {
                console.error('Erreur lors de l\'attachement des tags:', error)
            })
        },
        detachTag(option) {
            TagsApi.detachTag(
                { id: this.$parent.annotation.id },
                { tag_id: option.id }
            ).then(resp => {
                console.log('Tags détachés:', resp.data);
            }).catch(error => {
                console.error('Erreur lors du détachement des tags:', error)
            })
        },
        deleteTag(option) {
            this.options = this.options.filter(o => o.id !== option.id);
            this.selectedOptions = this.selectedOptions.filter(o => o.id !== option.id);
            TagsApi.delete({ id: option.id }).then(resp => {
                console.log('Tag supprimé:', resp.data);
            }).catch(error => {
                console.error('Erreur lors de la suppression du tag:', error)
            })
        },
        formatTag(tag) {
            return {
                id: tag.id,
                name: tag.name
            }
        }
    },
    mounted() {
        this.getAllTags();
        this.getAnnotationTags();
    }
}
</script>
<template>
    <span class="dropdown" :class="{ open: isOpen }">
        <!-- <button class="btn btn-sm btn-default dropdown-toggle" style="margin: 3px" @click="toggle">
            <i class="fa fa-tags"></i>
            <span class="caret"></span>
        </button> -->
        <span class="dropdown-toggle" style="margin: 3px" @click="toggle">
            <i class="fa fa-tags"></i>
            <span class="caret"></span>
        </span>
        <div class="dropdown-menu" style="padding: 10px; width: auto; position: unset;">
            <input v-model="search" class="form-control" type="text" placeholder="Rechercher..." />
            <ul class="list-unstyled" style="margin: 10px 0; max-height: 150px; overflow-y: auto;">
                <li v-for="(option, index) in filteredOptions" :key="index">
                    <div @click.prevent="toggleSelection(option)">
                        <!-- <input type="checkbox" :checked="isSelected(option)" /> -->
                         <input type="checkbox" :checked="isSelected(option)" />
                        {{ option.name }}
                        <button @click.prevent="deleteTag(option)" type="button" title="Detach this tag from the annotation" class="close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </li>
                <li v-if="filteredOptions.length === 0" class="text-muted">Aucune option</li>
            </ul>
            <div class="input-group">
                <input v-model="newOption" type="text" class="form-control" placeholder="Nouvelle option" />
                <span class="input-group-btn">
                    
                    <button class="btn btn-success" @click="addOption" :disabled="!newOption.trim()">
                        <span aria-hidden="true" class="fa fa-plus"></span>
                    </button>
                </span>
            </div>
        </div>
    </span>
</template>

<style scoped>

</style>