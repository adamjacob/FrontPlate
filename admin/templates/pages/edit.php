<form id="page_attributes" class="edit-page-form">

	<input class="gray-input-field" name="title" placeholder="Page Title" value="<%=page.title%>" />

	<div class="half">
		<input class="gray-input-field" name="slug" placeholder="Page Slug" value="<%=page.slug%>" />
		<select class="gray-input-field" name="template" placeholder="Page Template" value="<%=page.template%>">
			<option value="">No Selected Template</option>
			<?php

				foreach(glob('../../../template/*.php') as $file) {

					$filename = trim( basename($file, ".php").PHP_EOL );

			?>

				<option value="<?=$filename?>"<% if('<?=$filename?>' == page.template){ %> selected="selected"<% } %>><?=ucfirst($filename)?> Template</option>

			<?php

				}

			?>

		</select>
	</div>
</form>
<form id="page_metas" class="edit-page-form">

	<% _.each(template_meta, function(item){ %>

		<% if(item.type == 'text'){ %>

			<div class="field">
				<h4><%=item.name.replace('_', ' ')%></h4>
				<% if( !_.isUndefined(page.meta[item.name]) ) { %>
				
					<textarea name="<%=item.name%>"><%=page.meta[item.name].value%></textarea>
				
				<% }else{ %>
				
					<textarea name="<%=item.name%>"></textarea>
				
				<% } %>
			</div>

		<% }else if(item.type == 'string'){ %>

			<div class="field">
				<h4><%=item.name.replace('_', ' ')%></h4>
				<% if( !_.isUndefined(page.meta[item.name]) ){ %>
				
					<input name="<%=item.name%>" value="<%=page.meta[item.name].value%>" type="text" />
				
				<% }else{ %>
				
					<input name="<%=item.name%>" type="text" />
				
				<% } %>
			</div>

		<% }else{ %>

		<div><%=item.name%>:<%=item.type%></div>

		<% } %>

	<% }); %>

	<!--<% _.each(page.meta, function(metaItem){ %>

		<div><%=metaItem.key%>:<%=metaItem.value%></div>

	<% }); %>-->

</form>
<form class="edit-page-form">

	<input type="button" class="save-button" id="saveModel" value="Save Changes" />
	<input type="button" class="delete-button" id="deleteModel" value="Delete Page" />

</form>