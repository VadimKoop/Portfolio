import produce from "immer";
import {
    getElement
} from './elements.jsx';
import ElementsList from './elementsList.jsx';


const {
    Component,
    Fragment
} = wp.element;
const {
    __
} = wp.i18n;

const {
    ServerSideRender,
    PanelBody
} = wp.components;

const {
    InspectorControls
} = wp.editor;

class FAQBlockEdit extends Component {
    constructor() {
        super(...arguments);
    }

    onChange(fieldName, newValue) {
        this.props.setAttributes({
            [fieldName]: newValue
        });
    }

    getQuerySettings() {
        let ControlsArray = [];

        for (var key in BlockFields) {
            if (BlockFields.hasOwnProperty(key)) {
                let singleField = BlockFields[key];
                let element = getElement(singleField, this.props);
                ControlsArray.push(element);
            }
        }

        return ControlsArray;
    }

    onChangeStyle(fieldName, newValue, element) {
        let styleOld = {};
        if (this.props.attributes.style) {
            styleOld = this.props.attributes.style;
        }

        const style = produce(styleOld, draftStyle => {

            if (!draftStyle[element]) {
                draftStyle[element] = {};
            }
            draftStyle[element][fieldName] = newValue;
        });

        // style[fieldName] = newValue;
        this.props.setAttributes({
            'style': style
        });
    }

    getStyleSettings() {

        let MainArray = [];
        let Elements = BlockFields.style;


        for (var elementKey in Elements) {

            let StylesArray = [];
            let StylesFields = Elements[elementKey]['styleProps'];

            for (var key in StylesFields) {
                if (StylesFields.hasOwnProperty(key)) {
                    let singleField = StylesFields[key];
                    let value = '';

                    if( this.props.attributes.style && this.props.attributes.style[elementKey] && this.props.attributes.style[elementKey][key] ){
                        value = this.props.attributes.style[elementKey][key];
                    }
                    
                    const element = (<ElementsList value={value} field={singleField} elementKey={elementKey} onChangeStyle={this.onChangeStyle.bind(this)} />);
                    StylesArray.push(element);
                }
            }

            const SingleStylePanel = (
                <PanelBody initialOpen={false} title={Elements[elementKey]['label'] + " Settings"}>
					{ StylesArray }
				</PanelBody>
            );
            MainArray.push(SingleStylePanel);
        }

        return MainArray;
    }

    render() {
        // ensure the block attributes matches this plugin's name

        const inspectorControls = (
            <InspectorControls>
				<PanelBody title="Query Settings">
					{ this.getQuerySettings() }
				</PanelBody>
				{ this.getStyleSettings() }
			</InspectorControls>
        );

        return (
            <Fragment>
				{ inspectorControls }
				<ServerSideRender
					block='helpie-faq/helpie-faq'
					attributes={ this.props.attributes }
				/>
			</Fragment>
        );
    }
}


export default FAQBlockEdit;