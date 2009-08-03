<?php

/**
 * Form input hook that adds an Open Layers map format to Semantic Forms
 *
 * @file SM_OpenLayersFormInput.php
 * @ingroup SemanticMaps
 * 
 * @author Jeroen De Dauw
 */

if( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

final class SMOpenLayersFormInput extends SMFormInput {
	
	/**
	 * @see MapsMapFeature::setMapSettings()
	 *
	 */
	protected function setMapSettings() {
		global $egMapsOpenLayersZoom;
		
		$this->elementNamePrefix = 'open_layer';
		$this->showAddresFunction = 'showOLAddress';	

		$this->earthZoom = 1;
		$this->defaultZoom = $egMapsOpenLayersZoom;		
	}	
	
	/**
	 * @see MapsMapFeature::doMapServiceLoad()
	 *
	 */
	protected function doMapServiceLoad() {
		global $egOpenLayersOnThisPage;
		
		MapsOpenLayers::addOLDependencies($this->output);
		$egOpenLayersOnThisPage++;	

		$this->elementNr = $egOpenLayersOnThisPage;
	}	
	
	/**
	 * @see MapsMapFeature::addSpecificMapHTML()
	 *
	 */
	protected function addSpecificMapHTML() {
		global $wgJsMimeType;
		
		$controlItems = MapsOpenLayers::createControlsString($this->controls);
		
		$layerItems = MapsOpenLayers::createLayersStringAndLoadDependencies($this->output, $this->layers);	
		
		$width = $this->width . 'px';
		$height = $this->height . 'px';			
		
		$this->output .="
		<div id='".$this->mapName."' style='width: $width; height: $height; background-color: #cccccc;'></div>  
		
		<script type='$wgJsMimeType'>/*<![CDATA[*/
		addLoadEvent(makeFormInputOpenLayer('".$this->mapName."', '".$this->coordsFieldName."', ".$this->centre_lat.", ".$this->centre_lon.", ".$this->zoom.", ".$this->marker_lat.", ".$this->marker_lon.", [$layerItems], [$controlItems]));
		/*]]>*/</script>";			
	}
	
	/**
	 * @see SMFormInput::manageGeocoding()
	 *
	 */
	protected function manageGeocoding() {	
		$this->enableGeocoding = false;
	}
	
}
