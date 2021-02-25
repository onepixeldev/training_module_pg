<p>
<div class="well">
	<div class="row">
		<table class="table table-bordered table-hover" id="tbl_list_secl">
		<thead>
		<tr>
			<th class="text-center">Code</th>
			<th class="text-center">Description</th>
			<th class="text-center">Status</th>
			<th class="text-center">Action</th>
		</tr>
		</thead>
		<tbody>
		<?php
			// $no = 0;
			
			foreach ($training_sector_level_list as $tsecl) {
				if ($tsecl->TSL_STATUS == 'Y') {
					$tslStatus = 'ACTIVE';
				} elseif ($tsecl->TSL_STATUS == 'N') {
					$tslStatus = 'INACTIVE';
				} else {
					$tslStatus = '';
				}
				

				echo '
				<tr>
					<td class="text-center col-md-1">' . $tsecl->TSL_CODE . '</td>
					<td class="text-left">' . $tsecl->TSL_DESC . '</td>
					<td class="text-center col-md-1">' . $tslStatus . '</td>
					<td class="text-center col-md-2">
						<button type="button" class="btn btn-success btn-xs edit_tsecl" title="Edit Record"><i class="fa fa-edit"></i> Edit</button>
						<button type="button" class="btn btn-danger btn-xs delete_tsecl" title="Delete Record"><i class="fa fa-trash"></i> Delete</button>
					</td>
				</tr>
				';
			}
		?>
		</tbody>
		</table>	
	</div>
</div>
</p>
<script>

	// DELETE page
	$('.delete_tsecl').click(function () {
		var thisBtn = $(this);
		var td = thisBtn.parent().siblings();
		var recCode = td.eq(0).html().trim();
		//alert(recCode);
		
		if (recCode) {
			$('#myModalis .modal-content').empty();
			$('#myModalis').modal('show');
			$('#myModalis').find('.modal-content').html('<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" style="color:black"></i></center>');

			$.ajax({
				type: 'POST',
				url: '<?php echo $this->lib->class_url('delTSECL')?>',
				data: {'codeTSECL' : recCode},
				success: function(res) {
					$('#myModalis .modal-content').hide().html(res).fadeIn();
				}
			});
		}
	});

	// EDIT page
	$('.edit_tsecl').click(function () {
		var thisBtn = $(this);
		var td = thisBtn.parent().siblings();
		var recCode = td.eq(0).html().trim();
		//alert(recCode);
		
		if (recCode) {
			$('#myModalis .modal-content').empty();
			$('#myModalis').modal('show');
			$('#myModalis').find('.modal-content').html('<center><i class="fa fa-spinner fa-spin fa-3x fa-fw" style="color:black"></i></center>');

			$.ajax({
				type: 'POST',
				url: '<?php echo $this->lib->class_url('editTSECL')?>',
				data: {'codeTSECL' : recCode},
				success: function(res) {
					$('#myModalis .modal-content').hide().html(res).fadeIn();
				}
			});
		}
	});
</script>