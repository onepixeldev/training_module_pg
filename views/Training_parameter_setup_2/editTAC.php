<form class="form-horizontal" method="post">
    <div class="modal-header btn-success">
        <h4 class="modal-title" id="myModalLabel">Edit Record</h4>
    </div>
    <div class="modal-body">
        <div id="alert">
        	<b>Note : </b> ( <b><font color="red">*</font></b> ) <b><font color="red">compulsory fields</font></b><br>&nbsp; <span id="note"></span>
    	</div>
        <div class="form-group">
            <label class="col-md-3 control-label"><b>Code</b></label>
            <div class="col-md-9">
				<input name="form[code]" class="form-control" type="text" value="<?php echo $tac_code?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label"><b>Description</b> <b><font color="red">* </font></b></label>
            <div class="col-md-9">
				<input name="form[description]" placeholder="Description" class="form-control" type="text" value="<?php echo $tac_desc->TAC_DESC?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label"><b>Order</b> <b><font color="red">* </font></b></label>
            <div class="col-md-4">
				<input name="form[order]" placeholder="List Priority (number)" class="form-control" type="text" value="<?php echo $tac_desc->TAC_ORDERING?>">
            </div>
        </div>

		<div class="form-group">
			<label class="col-md-3 control-label"><b>Status </b><b><font color="red">* </font></b></label>
			<div class="col-md-4">
                <?php echo form_dropdown('form[status]', array(''=>'---Please Select---','Y'=>'ACTIVE','N'=>'INACTIVE'), $tac_desc->TAC_STATUS, 'class="selectpicker form-control width-50"')?>
			</div>
		</div>

    </div>
    <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-hand-o-left"></i> Close</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
    </div>
</form>

<script>	
	$(document).ready(function(){	
		$('form').submit(function (e) { 
			e.preventDefault();
			var data = $('form').serialize();
			msg.wait('#alert');
			msg.wait('#alertFooter');
			
			$('.btn').attr('disabled', 'disabled');
			$.ajax({
				type: 'POST',
				url: '<?php echo $this->lib->class_url('updateTAC')?>',
				data: data,
				dataType: 'JSON',
				success: function(res) {
					msg.show(res.msg, res.alert, '#alert');
					//msg.show(res.msg, res.alert, '#alertFooter');
					
					if (res.sts == 1) {
						setTimeout(function () {
							location = '<?php echo $this->lib->class_url('viewTabFilter','s2')?>';
						}, 1000);
					} else {
						$('.btn').removeAttr('disabled');
					}
				},
				error: function() {
					$('.btn').removeAttr('disabled');
					msg.danger('Please contact administrator.', '#alert');
					//msg.danger('Please contact administrator.', '#alertFooter');
				}
			});	
		});  				

	});   	  
</script>