kampungan.importPluginLanguagePack('preview','en,tr,cs,de,el,fr_ca,it,ko,pt,sv,zh_cn,fa,fr,pl,pt_br,nl,da,he,nb,hu,ru,ru_KOI8-R,ru_UTF-8,nn,es,cy,is,zh_tw,zh_tw_utf8,sk');var kampungan_PreviewPlugin={getInfo:function(){return{longname:'Preview',author:'Moxiecode Systems',authorurl:'http://kampungan.moxiecode.com',infourl:'http://kampungan.moxiecode.com/kampungan/docs/plugin_preview.html',version:kampungan.majorVersion+"."+kampungan.minorVersion};},getControlHTML:function(cn){switch(cn){case"preview":return kampungan.getButtonHTML(cn,'lang_preview_desc','{$pluginurl}/images/preview.gif','mcePreview');}return"";},execCommand:function(editor_id,element,command,user_interface,value){switch(command){case"mcePreview":var previewPage=kampungan.getParam("plugin_preview_pageurl",null);var previewWidth=kampungan.getParam("plugin_preview_width","550");var previewHeight=kampungan.getParam("plugin_preview_height","600");if(previewPage){var template=new Array();template['file']=previewPage;template['width']=previewWidth;template['height']=previewHeight;kampungan.openWindow(template,{editor_id:editor_id,resizable:"yes",scrollbars:"yes",inline:"yes",content:kampungan.getContent(),content_css:kampungan.getParam("content_css")});}else{var win=window.open("","mcePreview","menubar=no,toolbar=no,scrollbars=yes,resizable=yes,left=20,top=20,width="+previewWidth+",height="+previewHeight);var html="";var c=kampungan.getContent();var pos=c.indexOf('<body'),pos2;if(pos!=-1){pos=c.indexOf('>',pos);pos2=c.lastIndexOf('</body>');c=c.substring(pos+1,pos2);}html+=kampungan.getParam('doctype');html+='<html xmlns="http://www.w3.org/1999/xhtml">';html+='<head>';html+='<title>'+kampungan.getLang('lang_preview_desc')+'</title>';html+='<base href="'+kampungan.settings['base_href']+'" />';html+='<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';html+='<link href="'+kampungan.getParam("content_css")+'" rel="stylesheet" type="text/css" />';html+='</head>';html+='<body dir="'+kampungan.getParam("directionality")+'">';html+=c;html+='</body>';html+='</html>';win.document.write(html);win.document.close();}return true;}return false;}};kampungan.addPlugin("preview",kampungan_PreviewPlugin);