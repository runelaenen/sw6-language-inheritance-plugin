import template from './sw-cms-page-form.html.twig';
import './sw-cms-page-form.scss';

export default {
    template,

    data() {
        return {
            laeLanguageInheritanceModal: null,
        };
    },

    methods: {
        onSelectLanguage(languageId) {
            if (!this.laeLanguageInheritanceModal) {
                this.laeLanguageInheritanceModal = null;
                return;
            }

            if (!this.laeLanguageInheritanceModal.config) {
                this.laeLanguageInheritanceModal.config = {};
            }
            this.laeLanguageInheritanceModal.config.laeLanguageInheritance = {
                value: languageId,
                source: 'static',
            };

            if (languageId === Shopware.Context.api.languageId) {
                this.laeLanguageInheritanceModal.config.laeLanguageInheritance =
                    null;
            }

            this.laeLanguageInheritanceModal = null;
        },
    },
};
