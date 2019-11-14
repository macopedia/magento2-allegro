define([
    'Magento_Ui/js/form/element/textarea',
    'jquery',
    'wysiwygAdapter',
    'Macopedia_Allegro/js/allegro_offer/validation/description-subnet',
    'Macopedia_Allegro/js/allegro_offer/validation/description-tags',
    'Macopedia_Allegro/js/allegro_offer/validation/description-tags-attributes',
], function (textarea, $) {

    return textarea.extend({
        initNodeListener: function () {
            $.async({
                component: this,
                selector: this.elementSelector
            }, this.setElementNode.bind(this));
            return this;
        },

        setElementNode: function () {
            var uid = this.uid;
            tinymce.init({
                selector: '#' + uid,
                height: 500,
                menubar: false,
                plugins: [
                    'advlist lists preview',
                    'code',
                    'paste autosave'
                ],
                entity_encoding: 'raw',
                toolbar: 'undo redo | formatselect | bold | bullist numlist | code removeformat',
                advlist_bullet_styles: 'default',
                advlist_number_styles: 'default',
                block_formats: 'Paragraph=p;Header 1=h1;Header 2=h2',
                remove_linebreaks: true,
                formats: {
                    bold: [
                        {inline: 'b'}
                    ],
                    removeformat: [
                        {
                            selector: 'br,strong,span,em,i,img,div,body,strike,u,hr',
                            remove: 'all',
                            split: true,
                            expand: false,
                            block_expand: true,
                            deep: true
                        },
                        {
                            selector: '*',
                            attributes: ['style', 'class', 'id', 'data'],
                            split: false,
                            expand: false,
                            deep: true
                        }
                    ]
                },
                valid_elements: 'h1,h2,p,b,ul,ol,li',
                invalid_elements: 'br,strong,span,em,i,img,div,body,strike,u,hr',
                setup: function (editor) {
                    editor.on('PreProcess', function (e) {
                        if (e.content) {
                            e.content = e.content.replace(/<br\s?\/?><br\s?\/?>/gi, '</p><p>');
                        }
                    });

                    editor.on('change', function () {
                        editor.save();
                        $('#' + uid).trigger('change');
                    });

                    // handle submit button
                    $('#save').click(function () {
                        editor.save();
                        $('#' + uid).trigger('change');
                    });
                }
            });

        },

        initialize: function () {
            this._super();

            this.validation = this.validation || {};
            this.validation['allegro-offer-description-tags'] = true;
            this.validation['allegro-offer-description-tags-attributes'] = true;
            this.validation['allegro-offer-description-subnet'] = true;
            this.elementSelector = '.admin__control-textarea';

            this.initNodeListener();
        }
    });
});
