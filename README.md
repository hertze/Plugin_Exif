Plugin_Exif
===========

# A rudimentary EXIF Plugin for Statamic

This plugin tries to read EXIF-data from a supplied file path, in order to create an {{ exif }} tag pair, with access to some of the EXIF items.

## Usage example

	<!-- Get Exif from photo -->
	{{ exif imagepath='{photo}' }}
		{{ if model }} <!-- If model exists render all Exif -->
			<ul class="exif">
				<li>{{ camera }}</li>
				<li>{{ flenght }}</li>
				<li>{{ aperture }}</li>
				<li>{{ exposure }}</li>
				<li>{{ iso }}</li>
			</ul>	
		{{ endif }}
	{{ /exif }} 