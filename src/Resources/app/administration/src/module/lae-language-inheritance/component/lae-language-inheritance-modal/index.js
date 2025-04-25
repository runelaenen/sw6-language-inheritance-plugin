import template from './lae-language-inheritance-modal.html.twig';

export default {
    template,

    props: {
        initLanguage: {
            required: false,
            default: null,
            type: String,
        },
    },

    data() {
        return {
            selectedLanguage:
                this.initLanguage ?? Shopware.Context.api.systemLanguageId,
        };
    },

    methods: {
        onModalClose() {
            this.$emit('modal-close');
        },

        selectLanguage() {
            this.$emit('select-language', this.selectedLanguage);
        },

        resetLanguage() {
            this.$emit('select-language', null);
        },
    },
};
