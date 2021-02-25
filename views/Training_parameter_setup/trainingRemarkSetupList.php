<p>
<div class="well">
	<div class="row">
		<table class="table table-bordered table-hover" id="tbl_list_rs">
		<thead>
		<tr>
			<th class="text-center">No</th>
			<th class="text-center">Remark</th>
			<th class="text-center">Action</th>
		</tr>
		</thead>
		<tbody>
		<?php
			// $no = 0;
			
			foreach ($training_remark_setup_list as $rs) {
				
				echo '
				<tr>
					<td class="text-center col-md-1">' . $rs->TRS_SEQ . '</td>
					<td class="text-left">' . $rs->TRS_REMARK . '</td>
					<td class="text-center col-md-2">
						<button type="button" class="btn btn-success btn-xs edit_rs" title="Edit Record"><i class="fa fa-edit"></i> Edit</button>
						<button type="button" class="btn btn-danger btn-xs delete_rs" title="Delete Record"><i class="fa fa-trash"></i> Delete</button>
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
	$('.delete_rs').click(function () {
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
				url: '<?php echo $this->lib->class_url('delRS')?>',
				data: {'trsSEQ' : recCode},
				success: function(res) {
					$('#myModalis .modal-content').hide().html(res).fadeIn();
				}
			});
		}
	});

	// EDIT page
	$('.edit_rs').click(function () {
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
				url: '<?php echo $this->lib->class_url('editRS')?>',
				data: {'trsSEQ' : recCode},
				success: function(res) {
					$('#myModalis .modal-content').hide().html(res).fadeIn();
				}
			});
		}
	});
</script>