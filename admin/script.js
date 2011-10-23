$(document).ready(function(){
	/* The following code is executed once the DOM is loaded */

	$(".todoList").sortable({
		axis		: 'y',				// Only vertical movements allowed
		containment	: 'window',			// Constrained by the window
		update		: function(){		// The function is called after the todos are rearranged
		
			// The toArray method returns an array with the ids of the todos
			var arr = $(".todoList").sortable('toArray');
			
			
			// Striping the todo- prefix of the ids:
			
			arr = $.map(arr,function(val,key){
				return val.replace('todo-','');
			});
			
			// Saving with AJAX
			$.get('ajax.php',{action:'rearrange',positions:arr});
		},
		
		/* Opera fix: */
		
		stop: function(e,ui) {
			ui.item.css({'top':'0','left':'0'});
		}
	});
	
	// A global variable, holding a jQuery object 
	// containing the current todo item:
	
	var currentTODO;
	
	// Configuring the delete confirmation dialog
	$("#dialog-confirm").dialog({
		resizable: false,
		height:130,
		modal: true,
		autoOpen:false,
		buttons: {
			'Delete item': function() {
				
				$.get("ajax.php",{"action":"delete","id":currentTODO.data('id')},function(msg){
					currentTODO.fadeOut('fast');
				})
				
				$(this).dialog('close');
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		}
	});

	// When a double click occurs, just simulate a click on the edit button:
	$('.todo .text').live('dblclick',function(){
		//$(this).find('a.edit').click();
		//$(this).find('a.edit').click();

		var container = currentTODO.find('.text');

                if(!currentTODO.data('origText'))
                {
                        // Saving the current value of the ToDo so we can
                        // restore it later if the user discards the changes:

                        currentTODO.data('origText',container.text());
                }
                else
                {
                        // This will block the edit button if the edit box is already open:
                        return false;
                }

                $('<input type="text">').val(container.text()).appendTo(container.empty());

                // Appending the save and cancel links:
                container.append(
                        '<div class="editTodo">'+
                                '<a class="saveChanges" href="#">Save</a> or <a class="discardChanges" href="#">Cancel</a>'+
                        '</div>'
                );
	});
	
	$('.todo .date').live('dblclick',function(){   

                var container = currentTODO.find('.date');

                if(!currentTODO.data('origDate'))
                {
                        // Saving the current value of the ToDo so we can
                        // restore it later if the user discards the changes:

                        currentTODO.data('origDate',container.text());
                }
                else
                {   
                        // This will block the edit button if the edit box is already open:
                        return false;
                }

                $('<input type="date">').val(container.text()).appendTo(container.empty());

                // Appending the save and cancel links:
                container.append(
                        '<div class="editTodo">'+
                                '<a class="saveChangesDate" href="#">Save</a> or <a class="discardChangesDate" href="#">Cancel</a>'+
                        '</div>'
                );
        });

	$('.todo .time').live('dblclick',function(){   

                var container = currentTODO.find('.time');

                if(!currentTODO.data('origTime'))
                {
                        // Saving the current value of the ToDo so we can
                        // restore it later if the user discards the changes:

                        currentTODO.data('origTime',container.text());
                }
                else
                {   
                        // This will block the edit button if the edit box is already open:
                        return false;
                }

                $('<input type="time">').val(container.text()).appendTo(container.empty());

                // Appending the save and cancel links:
                container.append(
                        '<div class="editTodo">'+
                                '<a class="saveChangesTime" href="#">Save</a> or <a class="discardChangesTime" href="#">Cancel</a>'+
                        '</div>'
                );
        });
/*
	$('.todo .number').live('dblclick',function(){   

                var container = currentTODO.find('.text');

                if(!currentTODO.data('origText'))
                {
                        // Saving the current value of the ToDo so we can
                        // restore it later if the user discards the changes:

                        currentTODO.data('origText',container.text());
                }
                else
                {   
                        // This will block the edit button if the edit box is already open:
                        return false;
                }

                $('<input type="text">').val(container.text()).appendTo(container.empty());

                // Appending the save and cancel links:
                container.append(
                        '<div class="editTodo">'+
                                '<a class="saveChangesNumber" href="#">Save</a> or <a class="discardChangesNumber" href="#">Cancel</a>'+
                        '</div>'
                );
        });
*/

	// If any link in the todo is clicked, assign
	// the todo item to the currentTODO variable for later use.

	$('.todo a').live('click',function(e){
									   
		currentTODO = $(this).closest('.todo');
		currentTODO.data('id',currentTODO.attr('id').replace('todo-',''));
		
		e.preventDefault();
	});

	$('.todo form').live('click',function(e){
		currentTODO = $(this).closest('.todo');
	        currentTODO.data('id',currentTODO.attr('id').replace('todo-',''));
	               
	        e.preventDefault();
	});

	$('.todo').live('click',function(e){
                currentTODO = $(this).closest('.todo');
                currentTODO.data('id',currentTODO.attr('id').replace('todo-',''));
                       
                e.preventDefault();
        });
		
	// Listening for a click on a delete button:

	$('.todo a.delete').live('click',function(){
		$("#dialog-confirm").dialog('open');
	});
	
	// Listening for a click on a Team List
	
//	$('.todo .team').live('click',function(){
	
	// $.get("ajax.php",{'action':'new','text':'New Todo Item. Doubleclick to Edit.','rand':Math.random()},function(msg){
//	}	
	// Listening for a click on a edit button
	
	$('.todo a.edit').live('click',function(){

		var container = currentTODO.find('.text');
		
		if(!currentTODO.data('origText'))
		{
			// Saving the current value of the ToDo so we can
			// restore it later if the user discards the changes:
			
			currentTODO.data('origText',container.text());
		}
		else
		{
			// This will block the edit button if the edit box is already open:
			return false;
		}
		
		$('<input type="text">').val(container.text()).appendTo(container.empty());
		
		// Appending the save and cancel links:
		container.append(
			'<div class="editTodo">'+
				'<a class="saveChanges" href="#">Save</a> or <a class="discardChanges" href="#">Cancel</a>'+
			'</div>'
		);
		
	});
	
	// The cancel edit link:
	
	$('.todo a.discardChanges').live('click',function(){
		currentTODO.find('.text')
					.text(currentTODO.data('origText'))
					.end()
					.removeData('origText');
	});
	
	// The save changes link:
	
	$('.todo a.saveChanges').live('click',function(){
		var text = currentTODO.find("input[type=text]").val();
		
		$.get("ajax.php",{'action':'edit','id':currentTODO.data('id'),'text':text});
		
		currentTODO.removeData('origText')
					.find(".text")
					.text(text);
	});

        // The cancel edit link:

        $('.todo a.discardChangesDate').live('click',function(){
                currentTODO.find('.date')
                                        .text(currentTODO.data('origDate'))
                                        .end()
                                        .removeData('origDate');
        });

        // The save changes link:

        $('.todo a.saveChangesDate').live('click',function(){
                var date = currentTODO.find("input[type=text]").val();

                $.get("ajax.php",{'action':'editdate','id':currentTODO.data('id'),'date':date});

                currentTODO.removeData('origDate')    
                                        .find(".date")
                                        .text(date);  
        });

        // The cancel edit link:

        $('.todo a.discardChangesTime').live('click',function(){
                currentTODO.find('.time')
                                        .text(currentTODO.data('origTime'))
                                        .end()
                                        .removeData('origTime');
        });

        // The save changes link:

        $('.todo a.saveChangesTime').live('click',function(){
                var time = currentTODO.find("input[type=text]").val();

                $.get("ajax.php",{'action':'edittime','id':currentTODO.data('id'),'time':time});

                currentTODO.removeData('origTime')    
                                        .find(".time")
                                        .text(time);  
        });

	
	$('.todo form.refereeteam1').change(function(){
		var team = currentTODO.find("#referee1Select").val();
		$.get("ajax.php",{'action':'editreferee1team','id':currentTODO.data('id'),'team':team});
	});
        $('.todo form.refereeteam2').change(function(){
                var team = currentTODO.find("#referee2Select").val();
                $.get("ajax.php",{'action':'editreferee2team','id':currentTODO.data('id'),'team':team});
        });
        $('.todo form.tableteam1').change(function(){
                var team = currentTODO.find("#table1Select").val();
                $.get("ajax.php",{'action':'edittable1team','id':currentTODO.data('id'),'team':team});
        });
        $('.todo form.tableteam2').change(function(){
                var team = currentTODO.find("#table2Select").val();
                $.get("ajax.php",{'action':'edittable2team','id':currentTODO.data('id'),'team':team});
        });
        $('.todo form.tableteam3').change(function(){
                var team = currentTODO.find("#table3Select").val();            
                $.get("ajax.php",{'action':'edittable3team','id':currentTODO.data('id'),'team':team});
        });



	// The Add New ToDo button:
	
	var timestamp=0;
	$('#addButton').click(function(e){

		// Only one todo per 1 seconds is allowed:
		if((new Date()).getTime() - timestamp<1000) return false;
		
		$.get("ajax.php",{'action':'new','text':'New Todo Item. Doubleclick to Edit.','rand':Math.random()},function(msg){

			// Appending the new todo and fading it into view:
			$(msg).hide().appendTo('.todoList').fadeIn();
		});

		// Updating the timestamp:
		timestamp = (new Date()).getTime();
		
		e.preventDefault();
	});
	
}); // Closing $(document).ready()
