/*
* last update: 2006-08-24
*/

editAreaLoader.load_syntax["twig"] = {
	'DISPLAY_NAME' : 'twig'
	,'COMMENT_SINGLE' : {}
	,'COMMENT_MULTI' : {'<!--' : '-->', '{#' : '#}'}
	,'KEYWORD_CASE_SENSITIVE' : true
	,'KEYWORDS' : {
            'statements' : [
			'include', 'for', 'if', 'elseif', 'else', 'endfor', 
                        'endif', 'extends', 'block', 'endblock', 'trans', 
                        'endtrans'
		]
             ,'reserved' : [
			'{{', '}}', '{%', '%}'
			
		]
	}
	,'OPERATORS' :[
	]
	,'DELIMITERS' :[
		'(', ')', '{%', '%}', '{{', '}}', '.'
	]
	,'REGEXPS' : {
		'doctype' : {
			'search' : '()(<!DOCTYPE[^>]*>)()'
			,'class' : 'doctype'
			,'modifiers' : ''
			,'execute' : 'before' // before or after
		}
		,'tags' : {
			'search' : '(<)(/?[a-z][^ \r\n\t>]*)([^>]*>)'
			,'class' : 'tags'
			,'modifiers' : 'gi'
			,'execute' : 'before' // before or after
		}
		,'attributes' : {
			'search' : '( |\n|\r|\t)([^ \r\n\t=]+)(=)'
			,'class' : 'attributes'
			,'modifiers' : 'g'
			,'execute' : 'before' // before or after
		}
	}
	,'STYLES' : {
		'COMMENTS': 'color: #AAAAAA;'
		,'QUOTESMARKS': 'color: #cdb37f;'
		,'KEYWORDS' : {
			'reserved' : 'color: #FF0000;'
			,'functions' : 'color: #0040FD;'
			,'statements' : 'color: #00a0a0;'
			}
		,'OPERATORS' : 'color: #E775F0;'
		,'DELIMITERS' : 'color: #bb6637;'
		,'REGEXPS' : {
			'attributes': 'color: #B1AC41;'
			,'tags': 'color: #E62253;'
			,'doctype': 'color: #8DCFB5;'
			,'test': 'color: #00FF00;'
		}	
	}		
};
