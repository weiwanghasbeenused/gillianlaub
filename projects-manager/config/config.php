<?
$fields = array(
	'main' => array(
		'name1' => array(
			'displayName' => 'Project Name',
			'slug' => 'project-name',
			'var' => 'name1',
			'type' => 'text'
		),
		'deck' => array(
			'displayName' => 'Project Description',
			'slug' => 'project-description',
			'var' => 'deck',
			'type' => 'wysiwyg'
		),
		'address2' => array(
			'displayName' => 'Project Thumbnail',
			'slug' => 'thumbnail',
			'var' => 'address2',
			'type' => 'image'
		),
		'external' => array(
			'displayName' => 'Sections',
			'slug' => 'sections',
			'var' => 'external',
			'type' => 'order'
		),
		'rank' => array(
			'displayName' => 'Rank',
			'slug' => 'rank',
			'var' => 'rank',
			'type' => 'hidden'
		),
		'url' => array(
			'displayName' => 'Url',
			'slug' => 'url',
			'var' => 'url',
			'type' => 'text'
		)
	),
	'section' => array(
		'name1' => array(
			'displayName' => 'Section Name',
			'slug' => 'section-name',
			'var' => 'name1',
			'type' => 'text'
		),
		'address1' => array(
			'displayName' => 'Layout',
			'slug' => 'layout',
			'var' => 'address1',
			'type' => 'checkbox'
		),
		'address2' => array(
			'displayName' => 'Section Thumbnail',
			'slug' => 'thumbnail',
			'var' => 'address2',
			'type' => 'image'
		),
		'body' => array(
			'displayName' => 'Body',
			'slug' => 'body',
			'var' => 'body',
			'type' => 'wysiwyg'
		),
		'rank' => array(
			'displayName' => 'Rank',
			'slug' => 'rank',
			'var' => 'rank',
			'type' => 'hidden'
		),
		'url' => array(
			'displayName' => 'Url',
			'slug' => 'url',
			'var' => 'url',
			'type' => 'text'
		)
	),
);
$checkbox_options = array(
	'layout' => array(
		'grid' => array(
			'displayName' => 'Grid',
			'slug' => 'grid',
			'checked' => true
		),
		'scroll' => array(
			'displayName' => 'Scroll',
			'slug' => 'scroll',
			'checked' => false
		)
		
	),
);