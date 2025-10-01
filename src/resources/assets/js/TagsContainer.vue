<script>
import TagItem from './TagItem';
import TagForm from './TagForm';
import TagReport from './TagReport.vue';
import TagApi from './api/tags'
import { LoaderMixin, handleErrorResponse, Tabs, Tab } from './import'

/**
 * The panel for editing tags
 */
export default {
    mixins: [
        LoaderMixin
    ],
    data() {
        return {
            tags: []
        };
    },
    components: {
        tagItem: TagItem,
        tagForm: TagForm,
        tagReport: TagReport,
        tabs: Tabs,
        tab: Tab,
    },
    methods: {
        saveTag(tag, reject) {
            TagApi.update({ id: tag.id }, { name: tag.name, color: tag.color })
                .catch(function (response) {
                    reject();
                    handleErrorResponse(response);
                })
                .finally(this.finishLoading);
        },
        deleteTag(tag) {
            this.startLoading();
            TagApi.delete({ id: tag.id })
                .then(() => {
                    this.tagDeleted(tag);
                }, handleErrorResponse);
        },
        tagDeleted(tag) {
            for (let i = this.tags.length - 1; i >= 0; i--) {
                if (this.tags[i].id === tag.id) {
                    this.tags.splice(i, 1);
                    break;
                }
            }
        },
        createTag(tag) {
            if (this.loading) {
                return;
            }
            console.log(tag);
            this.startLoading();
            TagApi.save({}, tag)
                .then(this.tagCreated, handleErrorResponse)
                .finally(this.finishLoading);
        },
        tagCreated(response) {
            this.insertTag(response.data);
        },
        insertTag(tag) {
            let name = tag.name.toLowerCase();
            // add the tag to the array so the tags remain sorted by their name
            for (let i = 0, length = this.tags.length; i < length; i++) {
                if (this.tags[i].name.toLowerCase() >= name) {
                    this.tags.splice(i, 0, tag);
                    return;
                }
            }
            // If the function didn't return by now the tag is "smaller" than all
            // the other tags.
            this.tags.push(tag);
        }
    },
    watch: {

    },
    created() {
        this.tags = biigle.$require('tagsDisplay.tags');
    },
};
</script>
