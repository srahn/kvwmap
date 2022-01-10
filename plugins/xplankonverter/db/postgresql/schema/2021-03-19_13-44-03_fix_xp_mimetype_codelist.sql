BEGIN;
	 /* this disables all triggers for this session */
	SET session_replication_role = replica;
	UPDATE
		xplan_gml.xp_mimetypes
	SET
		codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeTypes.xml'
	WHERE
		codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml';	

	/* fix reference in existing plans */
	/* set on xp_plan base-type to set it for bp,fp,rp,so and lp */
	UPDATE
		xplan_gml.xp_plan
	SET
		externereferenz[1].referenzmimetype.codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeTypes.xml'
	WHERE
		((externereferenz[1]).referenzmimetype).codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml';
		
	UPDATE
		xplan_gml.xp_plan
	SET
		externereferenz[2].referenzmimetype.codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeTypes.xml'
	WHERE
		(((externereferenz[2]).referenzmimetype).codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml');
		
	UPDATE
		xplan_gml.xp_plan
	SET
		externereferenz[3].referenzmimetype.codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeTypes.xml'
	WHERE
		((externereferenz[3]).referenzmimetype).codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml';
		
	UPDATE
		xplan_gml.xp_plan
	SET
		externereferenz[4].referenzmimetype.codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeTypes.xml'
	WHERE
		(((externereferenz[4]).referenzmimetype).codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml');
		
	UPDATE
		xplan_gml.xp_plan
	SET
		externereferenz[5].referenzmimetype.codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeTypes.xml'
	WHERE
		(((externereferenz[5]).referenzmimetype).codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml');

	UPDATE
		xplan_gml.xp_plan
	SET
		externereferenz[6].referenzmimetype.codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeTypes.xml'
	WHERE
		(((externereferenz[6]).referenzmimetype).codespace = 'https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml');
	/* this enables all triggers again, would alternatively happen automatically at the end of the session */
	SET session_replication_role = DEFAULT;  
COMMIT;