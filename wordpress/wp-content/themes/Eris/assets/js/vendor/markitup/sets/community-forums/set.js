// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
mySettings = {
    nameSpace:       "xbbcode", // Useful to prevent multi-instances CSS conflict
	previewParserPath:	'', // path to your XBBCode parser
	onShiftEnter:	{keepDefault:false, replaceWith:'[br /]\n'},
	onCtrlEnter:	{keepDefault:false, openWith:'\n[p]', closeWith:'[/p]\n'},
	onTab:			{keepDefault:false, openWith:'	 '},
	markupSet: [
		{name:'Bold', className:'markItUp_B', key:'B', openWith:'[b]', closeWith:'[/b]' },
		{name:'Italic', className:'markItUp_I', key:'I', openWith:'[i]', closeWith:'[/i]' },
		{name:'Stroke through', className:'markItUp_Strike', key:'S', openWith:'[s]', closeWith:'[/s]' },
		{separator:'---------------' },
		{name:'Paragraph', className: 'markItUp_P', openWith:'[p(!( class="[![Class]!]")!)]', closeWith:'[/p]' },
		{name:'Ul', className:'markItUp_UL', openWith:'[ul]\n', closeWith:'[/ul]\n' },
		{name:'Ol', className:'markItUp_OL', openWith:'[ol]\n', closeWith:'[/ol]\n' },
		{name:'Li', className:'markItUp_LI', openWith:'[li]', closeWith:'[/li]' },
		{separator:'---------------' },
		{name:'Quotes', className: 'markItUp_Quote', openWith:'[quote]', closeWith:'[/quote]'},
		{name:'Code', className: 'markItUp_Code', openWith:'[code]', closeWith:'[/code]'}, 
		
		{name:'Clean', className:'clean', replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } },
	]
}