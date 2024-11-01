import './simple-share-follow-button-admin.scss';

import apiFetch from '@wordpress/api-fetch';

import { ToggleControl, TextControl, RadioControl, RangeControl } from '@wordpress/components';

import {
	render,
	useState,
	useEffect
} from '@wordpress/element';

import Credit from './components/credit';

const SimpleShareFollowButtonAdmin = () => {

	const simplesharefollowbuttonadmin_options = JSON.parse( simplesharefollowbuttonadmin_data.options );
	const simplesharefollowbuttonadmin_services = JSON.parse( simplesharefollowbuttonadmin_data.services );

	const [ urlItems, setUrlItems ] = useState( simplesharefollowbuttonadmin_options.url );
	const [ indexItems, setIndexItems ] = useState( simplesharefollowbuttonadmin_options.index );
	const [ alignItem, setAlignItem ] = useState( simplesharefollowbuttonadmin_options.align );
	const [ blankItem, setBlankItem ] = useState( simplesharefollowbuttonadmin_options.blank );
	const [ bodyopenItem, setBodyopenItem ] = useState( simplesharefollowbuttonadmin_options.body_open );

	useEffect( () => {
		apiFetch( {
			path: 'rf/simplesharefollowbutton_api/token',
			method: 'POST',
			data: {
				url: urlItems,
				index: indexItems,
				align: alignItem,
				blank: blankItem,
				body_open: bodyopenItem,
			}
		} ).then( ( response ) => {
			//console.log( response );
		} );
	}, [ urlItems, indexItems, alignItem, blankItem, bodyopenItem ] );

	const items_url = [];
	Object.keys( urlItems ).map(
		( key ) => {
			if( urlItems.hasOwnProperty( key ) ) {
				items_url.push(
					<td>
					<TextControl
						className="service_url"
						value={ urlItems[ key ] }
						onChange={ ( value ) =>
							{
								urlItems[ key ] = value;
								let data = Object.assign( {}, urlItems );
								setUrlItems( data );
							}
						}
					/>
					{ simplesharefollowbuttonadmin_text.attr + ' : ' + key }
					</td>
				);
			}
		}
	);
	//console.log( urlItems );

	const items_index = [];
	Object.keys( indexItems ).map(
		( key ) => {
			if( indexItems.hasOwnProperty( key ) ) {
				items_index.push(
					<td>
					<RangeControl
						max = { 8 }
						min = { 1 }
						value={ indexItems[ key ] }
						onChange={ ( value ) =>
							{
								indexItems[ key ] = value;
								let data = Object.assign( {}, indexItems );
								setIndexItems( data );
							}
						}
					/>
					{ simplesharefollowbuttonadmin_text.attr + ' : ' + key + '_index' }
					</td>
				);
			}
		}
	);
	//console.log( indexItems );

	const items = [];
	for ( let i = 0; i < 8; i++ ) {
		let service_txt = simplesharefollowbuttonadmin_services[ i ];
		if ( 'twitter' == service_txt ) {
			service_txt = 'X(twitter)';
		}
		items.push(
			<tr>
			<td>{ service_txt }</td>
			{ items_url[ i ] }
			{ items_index[ i ] }
			</tr>
		);
	}

	return (
		<div className="wrap">
		<h2>Simple Share Follow Button</h2>
			<Credit />
			<div className="wrap">
				<h2>{ simplesharefollowbuttonadmin_text.settings }</h2>
				<details className="detailsStyle" open>
				<summary className="summaryStyle">{ simplesharefollowbuttonadmin_text.follow }</summary>
				<div className="detailsdivStyle">
				{ simplesharefollowbuttonadmin_text.shortcode } : <code>[ssfbf]</code>
				<table border="1" cellspacing="0" cellpadding="5" bordercolor="#000000" className="tableStyle">
				<tr>
				<td align="center">{ simplesharefollowbuttonadmin_text.service }</td>
				<td align="center">URL</td>
				<td align="center">{ simplesharefollowbuttonadmin_text.index }</td>
				</tr>
				{ items }
				</table>
				<hr />
				<ToggleControl
					label={ simplesharefollowbuttonadmin_text.body_open }
					checked={ bodyopenItem }
					onChange={ ( value ) => setBodyopenItem( value ) }
				/>
				<p className="description">
				{ simplesharefollowbuttonadmin_text.body_open_description }
				</p>
				<hr />
				<RadioControl
					label={ simplesharefollowbuttonadmin_text.position }
					selected={ alignItem }
					options={ [
						{ label: simplesharefollowbuttonadmin_text.right + ' | ' + simplesharefollowbuttonadmin_text.attr + ' : flex-end', value: 'flex-end' },
						{ label: simplesharefollowbuttonadmin_text.center + ' | ' + simplesharefollowbuttonadmin_text.attr + ' : center', value: 'center' },
						{ label: simplesharefollowbuttonadmin_text.left + ' | ' + simplesharefollowbuttonadmin_text.attr + ' : flex-start', value: 'flex-start' },
					] }
					onChange={ ( value ) => setAlignItem( value ) }
				/>
				<hr />
				{ simplesharefollowbuttonadmin_text.blank + ' | ' + simplesharefollowbuttonadmin_text.attr + ' : blank' }
				<RangeControl
					className="space_icons"
					max={ 8 }
					min={ 1 }
					value={ blankItem }
					onChange={ ( value ) => setBlankItem( value ) }
				/>
				</div>
				</details>
				<details className="detailsStyle">
				<summary className="summaryStyle">{ simplesharefollowbuttonadmin_text.share }</summary>
				<div className="detailsdivStyle">
				<p className="description">
				{ simplesharefollowbuttonadmin_text.share_description }
				</p>
				<a className="aStyle" href={ simplesharefollowbuttonadmin_text.share_description_url } target="_blank" rel="noopener noreferrer">{ simplesharefollowbuttonadmin_text.share_description_url }</a>
				</div>
				</details>
			</div>
		</div>
	);

};

render(
	<SimpleShareFollowButtonAdmin />,
	document.getElementById( 'simplesharefollowbuttonadmin' )
);

