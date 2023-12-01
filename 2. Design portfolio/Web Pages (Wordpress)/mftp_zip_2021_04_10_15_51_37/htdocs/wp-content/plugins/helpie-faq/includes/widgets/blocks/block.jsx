/**
 * Helpie FAQ Block
 */


import edit from './components/edit.jsx';

const {
    __
} = wp.i18n;

// Register block controls
const {
    registerBlockType,
} = wp.blocks;


/**
 * Register block
 *
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          Block itself, if registered successfully,
 *                             otherwise "undefined".
 */
registerBlockType(
    'helpie-faq/helpie-faq', // Block name. Must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
    {
        title: __('Helpie FAQ Block'), // Block title. __() function allows for internationalization.
        icon: 'list-view', // Block icon from Dashicons. https://developer.wordpress.org/resource/dashicons/.
        category: 'common', // Block category. Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.

        edit,

        // Render via PHP
        save() {
            // return <div>Hello</div>;
            return null;
        },
    }
);