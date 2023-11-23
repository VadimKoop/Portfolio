const {
	SelectControl,
	TextControl,
	ToggleControl,
} = wp.components;

const { __ } = wp.i18n;

// export function getStyleElement(type, props){

//     if(type == 'color'){
//         return ( 
//             <ColorPalette
// 				value={color}
// 				colors={ colors }
// 				onChange={ElementBGColor}
// 			/>
//         );
//     }


// }


export function getElement(field, props){
    let fieldName = field['name'];

    if(field['type'] == 'toggle'){
        return ( 
            <ToggleControl
                label={ __( field['label']) }
                // checked={ displayPostImage }
                onChange= { newValue => props.setAttributes({ [fieldName] : newValue } ) }
        />
        );
    }

    if(field['type'] == 'text' || field['type'] == 'number'){
        return (
            <TextControl
                label= { __( field['label']) }
                value={ props.attributes[field['name']] }
                onChange= { newValue => props.setAttributes({ [fieldName] : newValue } ) }
            />
        );
    }

    let options = [];
    
    if(field['type'] == 'select' || field['type'] == 'multi-select'){
        let FieldOptions = field['options'];
        

        for (var key in FieldOptions) {
            let singleOption = {
                value : key,
                label :  FieldOptions[key]
            };
            options.push(singleOption);
        }
    } 
    if(field['type'] == 'select'){ 
        return ( 
            <SelectControl
                label={ __( field['label']) }
                value={ props.attributes[field['name']] }
                onChange= { newValue => props.setAttributes({ [fieldName] : newValue } ) }
                options={ options }
            />
        );
    }

    if(field['type'] == 'multi-select'){
        return ( 
            <SelectControl
                multiple
                label={ __( field['label']) }
                value={ props.attributes[field['name']] }
                onChange= { newValue => props.setAttributes({ [fieldName] : newValue } ) }
                options={ options}
            />
        );
    }
}