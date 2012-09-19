
(
	function(){
	
		tinymce.create(
			"tinymce.plugins.CurShortcodes",
			{
				init: function(d,e) {},
				createControl:function(d,e)
				{
				
					if(d=="cur_shortcodes_button"){
					
						d=e.createMenuButton( "cur_shortcodes_button",{
							title:"Insert Shortcode",
							icons:false
							});
							
							var a=this;
                            d.onRenderMenu.add(function(c,b){
c=b.addMenu({title:"Grid"});
	a.addSelectable(c, 'Row' , '[row]', '[/row]');
	a.addSelectable(c, 'Third' , '[third]', '[/third]');
	a.addSelectable(c, 'Half' , '[half]', '[/half]');
c=b.addMenu({title:"Color"});
	a.addSelectable(c, 'Teal' , '[teal]', '[/teal]');
	a.addSelectable(c, 'Red' , '[red]', '[/red]');
	a.addSelectable(c, 'Green' , '[green]', '[/green]');
	a.addSelectable(c, 'Yellow' , '[yellow]', '[/yellow]');
a.addSelectable(b, 'Button' , '[button link="" color="" size="" class=""]', '[/button]');
c=b.addMenu({title:"Featured Bio"});
	a.addSelectable(c, 'Featured-bio' , '[featured-bio]', '[/featured-bio]');
	a.addSelectable(c, 'Fb-image' , '[fb-image]', '[/fb-image]');
	a.addSelectable(c, 'Fb-content' , '[fb-content]', '[/fb-content]');

							});
						return d
					
					} // End IF Statement
					
					return null
				},
		
                addImmediate: function (d,e,a){
                    d.add({
                        title:e,
                        onclick:function(){ 
                            tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)
                        }
                    })
                },
                addSelectable: function (d,e,open,close,a){
                    d.add({
                        title: e,
                        onclick:function(){ 
                            //.execCommand( "mceInsertContent",false,a)
                            tinyMCE.activeEditor.selection.setContent(open + tinyMCE.activeEditor.selection.getContent() + close);
                        }
                    })
                }
				
			}
		);
		
		tinymce.PluginManager.add( "CurShortcodes", tinymce.plugins.CurShortcodes);
	}
)();
