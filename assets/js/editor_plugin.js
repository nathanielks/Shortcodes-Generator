
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
c=b.addMenu({title:"Color"});
	a.addSelectable(c, 'Teal' , '[teal]', '[/teal]');
	a.addSelectable(c, 'Darkblue' , '[darkblue]', '[/darkblue]');
	a.addSelectable(c, 'Green' , '[green]', '[/green]');
	a.addSelectable(c, 'Grey' , '[grey]', '[/grey]');
	a.addSelectable(c, 'Lightest-grey' , '[lightest-grey]', '[/lightest-grey]');
c=b.addMenu({title:"Grid"});
	a.addSelectable(c, 'Row' , '[row]', '[/row]');
	a.addSelectable(c, 'Quarter' , '[quarter]', '[/quarter]');
	a.addSelectable(c, 'Third' , '[third]', '[/third]');
	a.addSelectable(c, 'Half' , '[half]', '[/half]');
	a.addSelectable(c, 'Two-thirds' , '[two-thirds]', '[/two-thirds]');
c=b.addMenu({title:"Color-block"});
	a.addSelectable(c, 'Color-block' , '[color-block class="" size="" shortcode="" color="" headline_size="" headline_class=""]', '[/color-block]');
	a.addSelectable(c, 'Headline' , '[headline color="" size="" class=""]', '[/headline]');
	a.addSelectable(c, 'Content' , '[content]', '[/content]');
	a.addSelectable(c, 'Footer' , '[footer]', '[/footer]');
a.addSelectable(b, 'Button' , '[button link="" color="" size="" class=""]', '[/button]');
a.addSelectable(b, 'Small' , '[small]', '[/small]');

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
