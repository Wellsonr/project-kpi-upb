<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| MIME TYPES
| -------------------------------------------------------------------
| This file contains an array of mime types. It is used by the
| Upload class to help identify allowed file types.
|
| Each extension maps to the MIME type(s) that the server's file
| inspection (finfo / mime_content_type) may report for that file.
| The Upload library rejects a file when its detected MIME type is
| not present in this list for the allowed extension, so several
| common variants are included per type.
*/

return array(
	'jpeg'  => array('image/jpeg', 'image/pjpeg'),
	'jpg'   => array('image/jpeg', 'image/pjpeg'),
	'jpe'   => array('image/jpeg', 'image/pjpeg'),
	'png'   => array('image/png', 'image/x-png'),
	'gif'   => 'image/gif',
	'bmp'   => array('image/bmp', 'image/x-windows-bmp'),
	'webp'  => 'image/webp',
	'svg'   => array('image/svg+xml', 'application/xml', 'text/xml'),

	'pdf'   => array('application/pdf', 'application/x-download', 'application/octet-stream'),
	'doc'   => array('application/msword', 'application/octet-stream'),
	'docx'  => array(
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'application/zip',
		'application/msword',
		'application/x-zip',
		'application/octet-stream',
	),
	'xls'   => array('application/vnd.ms-excel', 'application/msexcel', 'application/octet-stream'),
	'xlsx'  => array(
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'application/vnd.ms-excel',
		'application/zip',
		'application/octet-stream',
	),
	'ppt'   => array('application/vnd.ms-powerpoint', 'application/octet-stream'),
	'pptx'  => array(
		'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'application/zip',
		'application/octet-stream',
	),
	'txt'   => array('text/plain', 'text/x-log'),
	'csv'   => array('text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel', 'application/octet-stream'),
	'rtf'   => array('text/rtf', 'application/rtf'),

	'zip'   => array('application/zip', 'application/x-zip', 'application/x-zip-compressed', 'application/octet-stream'),
	'rar'   => array('application/x-rar', 'application/rar', 'application/x-rar-compressed', 'application/octet-stream'),
	'gz'    => array('application/x-gzip', 'application/gzip', 'application/octet-stream'),

	'mp4'   => array('video/mp4', 'application/octet-stream'),
	'mov'   => array('video/quicktime', 'application/octet-stream'),
	'avi'   => array('video/x-msvideo', 'video/avi', 'application/octet-stream'),
	'wmv'   => array('video/x-ms-wmv', 'application/octet-stream'),
	'webm'  => 'video/webm',
	'mkv'   => array('video/x-matroska', 'application/octet-stream'),

	'mp3'   => array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3', 'application/octet-stream'),
	'wav'   => array('audio/x-wav', 'audio/wave', 'audio/wav', 'application/octet-stream'),
	'ogg'   => array('audio/ogg', 'video/ogg', 'application/ogg'),
);
