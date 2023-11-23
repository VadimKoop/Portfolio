const {
    Component
} = wp.element;

const {
    BaseControl,
    DropdownMenu
} = wp.components;

const {
    InspectorControls,
    ColorPalette,
    FontSizePicker,
    PanelColorSettings,
} = wp.editor;

const {
    __
} = wp.i18n;

class ElementsList extends Component {
    constructor() {
        super(...arguments);
    }

    capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }



    getStyleElement(field, element) {
        let label = this.capitalizeFirstLetter(field);

        if (field == 'border') {
            field = 'border-color';
        }

        if (field == 'color' || field == 'background' || field == 'border-color') {
            const colors = [{
                    name: 'red',
                    color: '#e44f51'
                },
                {
                    name: 'white',
                    color: '#fff'
                },
                {
                    name: 'blue',
                    color: '#1a98ce'
                },
            ];
            return (
                <BaseControl label={ __(  label ) }>
					<ColorPalette
                        colors = {colors}
						value={this.props.value}
						label= {field}
						onChange={value => this.props.onChangeStyle(field, value, element )}
					/>
				</BaseControl>
            );
        }

        if (field == 'text-align') {
            return (
                <BaseControl label={ __(  label ) }>
					<DropdownMenu
						icon="align-left"
						label="Select a direction"
						controls={ [
							{
								title: 'Left',
								icon: 'align-left',
								onClick: () => this.props.onChangeStyle(field, 'left', element )
							},
							{
								title: 'Center',
								icon: 'align-center',
								onClick: () => this.props.onChangeStyle(field, 'center', element )
							},
							{
								title: 'Right',
								icon: 'align-right',
								onClick: () => this.props.onChangeStyle(field, 'right', element )
							},
						] }
					/>
				</BaseControl>
            );
        }
        // if(field['type'] == 'fontSize'){
        // 	let fontSize = 16;
        // 	return (
        // 		<FontSizePicker
        // 			// fontSizes={ fontSizes }
        // 			value={ fontSize }
        // 			fallbackFontSize={ fontSize }
        // 			onChange={value => this.props.onChangeStyle( field['name'], value + 'px' , element  )}
        // 		/>
        // 	);
        // }


    }

    render() {
        const element = this.getStyleElement(this.props.field, this.props.elementKey);
        return (
            <div>{element}</div>
        );
    }
}


export default ElementsList;